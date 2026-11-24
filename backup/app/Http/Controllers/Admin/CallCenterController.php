<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Library;
use App\Models\LibraryCallLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CallCenterController extends Controller
{
    /**
     * Call Center Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');

        $query = Library::query();

        if (!$isSuperAdmin) {
            $query->whereHas('assignedAdmins', function ($q) use ($user) {
                $q->where('admin_id', $user->id);
            });
        }

        $totalLibraries = $query->count();
        
        // Let's get the latest call log for each library
        // A complex query or just PHP side processing if small, 
        // but for scalability let's do simple queries based on relation if needed.
        // For dashboard stats, we'll use subqueries or just pull them.
        
        $assignedLibraryIds = $query->pluck('id');
        // Calls today
        $callsTodayQuery = LibraryCallLog::with('library')->whereDate('called_at', Carbon::today());
        $todayFollowUpsQuery = LibraryCallLog::whereDate('next_followup_date', Carbon::today());

        if (!$isSuperAdmin) {
            $callsTodayQuery->where('admin_id', $user->id);
            $todayFollowUpsQuery->where('admin_id', $user->id);
        } else {
            // For Super Admin, we could either show all calls across the platform or just assigned.
            // Usually, Super Admin wants to see all calls on the dashboard.
            // If they want only assigned, we can add: $callsTodayQuery->whereIn('library_id', $assignedLibraryIds);
        }

        $callsToday = $callsTodayQuery->get();

        $todayTotalCalls = $callsToday->count();
        $todayCompleted = $callsToday->where('status', 'Converted')->count();
        $todayFollowUps = $todayFollowUpsQuery->count();
        
        // To get counts based on latest status, we can use a subquery to get the latest call log id per library
        $latestLogIds = LibraryCallLog::whereIn('library_id', $assignedLibraryIds)
            ->selectRaw('MAX(id) as id')
            ->groupBy('library_id')
            ->pluck('id');

        $latestLogs = LibraryCallLog::whereIn('id', $latestLogIds)->get();

        $calledCount = $latestLogs->count();
        $pendingCount = $totalLibraries - $calledCount;
        
        $statusCounts = $latestLogs->groupBy('status')->map->count();

        $followUpRequired = $statusCounts->get('Call Back Later', 0) + $statusCounts->get('No Response', 0);
        $interested = $statusCounts->get('Interested', 0);
        $notInterested = $statusCounts->get('Not Interested', 0);
        $callBackLater = $statusCounts->get('Call Back Later', 0);
        $noResponse = $statusCounts->get('No Response', 0);
        $converted = $statusCounts->get('Converted', 0);

        return view('admin.call-center.dashboard', compact(
            'totalLibraries', 'pendingCount', 'calledCount', 'followUpRequired',
            'interested', 'notInterested', 'callBackLater', 'noResponse', 'converted',
            'todayTotalCalls', 'todayCompleted', 'todayFollowUps', 'callsToday'
        ));
    }

    /**
     * Library Call List
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');

        $query = Library::with(['district', 'state', 'latestCallLog', 'assignedAdmins']);

        if (!$isSuperAdmin) {
            $query->whereHas('assignedAdmins', function ($q) use ($user) {
                $q->where('admin_id', $user->id);
            });
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('contact_person_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'Pending') {
                $query->doesntHave('callLogs');
            } else {
                $query->whereHas('latestCallLog', function ($q) use ($status) {
                    $q->where('status', $status);
                });
            }
        }

        if ($request->filled('follow_up_date')) {
            $date = $request->follow_up_date;
            $query->whereHas('latestCallLog', function ($q) use ($date) {
                $q->whereDate('next_followup_date', $date);
            });
        }

        $libraries = $query->paginate(15);

        return view('admin.call-center.index', compact('libraries'));
    }

    /**
     * Store Call Remark
     */
    public function storeCallLog(Request $request)
    {
        $request->validate([
            'library_id' => 'required|exists:libraries,id',
            'status' => 'required|string',
            'remark' => 'required|string',
            'next_followup_date' => 'nullable|date',
            'next_followup_time' => 'nullable',
        ]);

        $user = Auth::user();

        // Check if follow up date is required
        if (in_array($request->status, ['Call Back Later', 'No Response']) && !$request->next_followup_date) {
            return response()->json(['success' => false, 'message' => 'Follow up date is required for this status.'], 422);
        }

        $log = LibraryCallLog::create([
            'library_id' => $request->library_id,
            'admin_id' => $user->id,
            'status' => $request->status,
            'remark' => $request->remark,
            'called_at' => now(),
            'next_followup_date' => $request->next_followup_date,
            'next_followup_time' => $request->next_followup_time,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Call remark saved successfully.']);
        }

        return redirect()->back()->with('success', 'Call remark saved successfully.');
    }

    /**
     * View Call History
     */
    public function history($libraryId)
    {
        $library = Library::findOrFail($libraryId);
        
        $user = Auth::user();

        $history = LibraryCallLog::with('admin')
            ->where('library_id', $libraryId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'library' => $library,
            'history' => $history
        ]);
    }

    /**
     * Call Center Report (Total Calling History by Sub-admin)
     */
    public function report(Request $request)
    {
        $user = Auth::user();

        // If not super admin, they can only see their own report, or maybe nothing.
        // Assuming Super Admin can see all, sub-admins see their own.
        $query = LibraryCallLog::with('admin')
            ->selectRaw('admin_id, 
                         count(*) as total_calls, 
                         sum(case when status = "Interested" then 1 else 0 end) as interested_calls,
                         sum(case when status = "Converted" then 1 else 0 end) as converted_calls,
                         sum(case when status = "Call Back Later" then 1 else 0 end) as callback_calls,
                         sum(case when status = "Not Interested" then 1 else 0 end) as not_interested_calls,
                         sum(case when status = "No Response" then 1 else 0 end) as no_response_calls,
                         MAX(called_at) as last_call_at')
            ->groupBy('admin_id');

        if (!$user->hasAnyRole(['Super Admin', 'Admin'])) {
            $query->where('admin_id', $user->id);
        }

        $reports = $query->get();

        return view('admin.call-center.report', compact('reports'));
    }
}
