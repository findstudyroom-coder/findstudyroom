<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Coaching;
use App\Models\CoachingLead;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Services\CoachingSetupService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CoachingAdminManagementController extends Controller
{
    public function __construct()
    {
        CoachingSetupService::ensureSchema();
    }

    /**
     * List all Coaching Admins
     */
    public function index(Request $request)
    {
        $query = Admin::whereNotNull('coaching_id')->with('coaching');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('mobile', 'LIKE', "%{$search}%")
                  ->orWhereHas('coaching', function($cq) use ($search) {
                      $cq->where('coaching_name', 'LIKE', "%{$search}%")
                         ->orWhere('city', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('coaching_id')) {
            $query->where('coaching_id', $request->coaching_id);
        }

        if ($request->filled('status')) {
            $query->where('status', (int)$request->status);
        }

        $admins = $query->latest('id')->paginate(15)->withQueryString();

        $stats = [
            'total'     => Admin::whereNotNull('coaching_id')->count(),
            'active'    => Admin::whereNotNull('coaching_id')->where('status', 1)->count(),
            'inactive'  => Admin::whereNotNull('coaching_id')->where('status', 0)->count(),
            'coachings' => Coaching::count(),
        ];

        $coachings = Coaching::orderBy('coaching_name')->get();

        return view('admin.coachings.admins.index', compact('admins', 'stats', 'coachings'));
    }

    /**
     * Show create coaching admin form
     */
    public function create()
    {
        $coachings = Coaching::orderBy('coaching_name')->get();
        // Coachings without admin
        $assignedIds = Admin::whereNotNull('coaching_id')->pluck('coaching_id')->toArray();

        return view('admin.coachings.admins.create', compact('coachings', 'assignedIds'));
    }

    /**
     * Store new coaching admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'coaching_id' => 'required|exists:coachings,id',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:admins,email',
            'mobile'      => 'nullable|string|max:20',
            'password'    => 'required|string|min:6|confirmed',
            'status'      => 'nullable|boolean',
        ]);

        $admin = Admin::create([
            'coaching_id' => $validated['coaching_id'],
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'mobile'      => $validated['mobile'] ?? '',
            'status'      => $request->has('status') ? 1 : 0,
            'password'    => Hash::make($validated['password']),
            'image'       => '',
        ]);

        $role = Role::firstOrCreate(['name' => 'Coaching Admin', 'guard_name' => 'web']);
        $admin->assignRole($role);

        $coaching = Coaching::find($validated['coaching_id']);
        $cName = $coaching ? $coaching->coaching_name : 'Coaching';

        return redirect()->route('coachings.admins.index')
            ->with('success', "Coaching Admin account for '{$cName}' created successfully! Login: {$admin->email}");
    }

    /**
     * Edit coaching admin
     */
    public function edit($id)
    {
        $admin = Admin::whereNotNull('coaching_id')->findOrFail($id);
        $coachings = Coaching::orderBy('coaching_name')->get();

        return view('admin.coachings.admins.edit', compact('admin', 'coachings'));
    }

    /**
     * Update coaching admin
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::whereNotNull('coaching_id')->findOrFail($id);

        $validated = $request->validate([
            'coaching_id' => 'required|exists:coachings,id',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:admins,email,' . $id,
            'mobile'      => 'nullable|string|max:20',
            'password'    => 'nullable|string|min:6|confirmed',
            'status'      => 'nullable|boolean',
        ]);

        $data = [
            'coaching_id' => $validated['coaching_id'],
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'mobile'      => $validated['mobile'] ?? '',
            'status'      => $request->has('status') ? 1 : 0,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('coachings.admins.index')
            ->with('success', "Coaching admin '{$admin->name}' updated successfully.");
    }

    /**
     * Quick password reset
     */
    public function resetPassword(Request $request, $id)
    {
        $admin = Admin::whereNotNull('coaching_id')->findOrFail($id);

        $request->validate([
            'new_password' => 'required|string|min:6',
        ]);

        $admin->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()
            ->with('success', "Password for {$admin->name} ({$admin->email}) was successfully reset.");
    }

    /**
     * Toggle active/inactive status
     */
    public function toggleStatus($id)
    {
        $admin = Admin::whereNotNull('coaching_id')->findOrFail($id);
        $admin->status = $admin->status ? 0 : 1;
        $admin->save();

        $state = $admin->status ? 'Activated' : 'Deactivated';
        return redirect()->back()->with('success', "Coaching Admin '{$admin->name}' is now {$state}.");
    }

    /**
     * Delete coaching admin
     */
    public function destroy($id)
    {
        $admin = Admin::whereNotNull('coaching_id')->findOrFail($id);
        $admin->delete();

        return redirect()->back()->with('success', 'Coaching Admin account deleted successfully.');
    }

    /**
     * Main Admin: View All Coaching Leads Across Institutes + Global Analytics
     */
    public function allLeads(Request $request)
    {
        $baseQuery = CoachingLead::with('coaching');

        $stats = [
            'total'      => (clone $baseQuery)->count(),
            'new'        => (clone $baseQuery)->where('status', CoachingLead::STATUS_NEW)->count(),
            'contacted'  => (clone $baseQuery)->where('status', CoachingLead::STATUS_CONTACTED)->count(),
            'interested' => (clone $baseQuery)->where('status', CoachingLead::STATUS_INTERESTED)->count(),
            'converted'  => (clone $baseQuery)->where('status', CoachingLead::STATUS_CONVERTED)->count(),
            'closed'     => (clone $baseQuery)->where('status', CoachingLead::STATUS_CLOSED)->count(),
            'coachings'  => Coaching::count(),
        ];

        $query = CoachingLead::with('coaching');

        if ($request->filled('coaching_id')) {
            $query->where('coaching_id', $request->coaching_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('course_interested', 'LIKE', "%{$search}%")
                  ->orWhereHas('coaching', function($cq) use ($search) {
                      $cq->where('coaching_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('enquiry_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('enquiry_date', '<=', $request->date_to);
        }

        $leads = $query->latest('id')->paginate(20)->withQueryString();

        // Comparative lead volume per coaching institute for Chart
        $coachingChartData = Coaching::withCount('leads')
            ->orderByDesc('leads_count')
            ->take(8)
            ->get();

        $coachings = Coaching::orderBy('coaching_name')->get();
        $statuses = CoachingLead::getStatuses();

        return view('admin.coachings.leads.all', compact('leads', 'stats', 'coachings', 'statuses', 'coachingChartData'));
    }

    /**
     * Export all leads across coachings
     */
    public function exportAllLeads(Request $request): StreamedResponse
    {
        $query = CoachingLead::with('coaching');

        if ($request->filled('coaching_id')) {
            $query->where('coaching_id', $request->coaching_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('enquiry_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('enquiry_date', '<=', $request->date_to);
        }

        $leads = $query->latest('id')->get();
        $filename = "All_Coaching_Leads_" . now()->format('Y-m-d_His') . ".csv";

        return response()->streamDownload(function () use ($leads) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Lead ID',
                'Coaching Institute',
                'Student Name',
                'Phone',
                'Email',
                'Course Interested',
                'Slot',
                'Enquiry Date',
                'Status',
                'Source',
                'Notes',
                'Created At',
            ]);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->id,
                    $lead->coaching ? $lead->coaching->coaching_name : 'N/A',
                    $lead->student_name,
                    $lead->phone,
                    $lead->email ?: 'N/A',
                    $lead->course_interested ?: 'General',
                    $lead->slot ?: '',
                    optional($lead->enquiry_date)->format('Y-m-d') ?: optional($lead->created_at)->format('Y-m-d'),
                    $lead->status,
                    $lead->source ?: 'Website',
                    $lead->notes ?: '',
                    optional($lead->created_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
