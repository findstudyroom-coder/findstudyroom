<?php

namespace App\Http\Controllers\CoachingAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coaching;
use App\Models\CoachingLead;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    protected function getCoachingId(Request $request): int
    {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            return (int)($request->get('coaching_id') ?: ($user->coaching_id ?: Coaching::value('id')));
        }
        return (int)$user->coaching_id;
    }

    public function index(Request $request)
    {
        $coachingId = $this->getCoachingId($request);
        $coaching = Coaching::findOrFail($coachingId);

        $baseQuery = CoachingLead::forCoaching($coachingId);

        $stats = [
            'total'      => (clone $baseQuery)->count(),
            'new'        => (clone $baseQuery)->where('status', CoachingLead::STATUS_NEW)->count(),
            'contacted'  => (clone $baseQuery)->where('status', CoachingLead::STATUS_CONTACTED)->count(),
            'interested' => (clone $baseQuery)->where('status', CoachingLead::STATUS_INTERESTED)->count(),
            'converted'  => (clone $baseQuery)->where('status', CoachingLead::STATUS_CONVERTED)->count(),
            'closed'     => (clone $baseQuery)->where('status', CoachingLead::STATUS_CLOSED)->count(),
        ];

        $query = CoachingLead::forCoaching($coachingId);

        // Search filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('course_interested', 'LIKE', "%{$search}%")
                  ->orWhere('slot', 'LIKE', "%{$search}%")
                  ->orWhere('notes', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Course filter
        if ($request->filled('course')) {
            $query->where('course_interested', $request->course);
        }

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('enquiry_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('enquiry_date', '<=', $request->date_to);
        }

        $leads = $query->latest('id')->paginate(15)->withQueryString();

        $courses = CoachingLead::forCoaching($coachingId)
            ->whereNotNull('course_interested')
            ->where('course_interested', '!=', '')
            ->distinct()
            ->pluck('course_interested');

        $statuses = CoachingLead::getStatuses();

        return view('coaching_admin.leads.index', compact('coaching', 'leads', 'stats', 'courses', 'statuses'));
    }

    public function store(Request $request)
    {
        $coachingId = $this->getCoachingId($request);

        $validated = $request->validate([
            'student_name'      => 'required|string|max:255',
            'phone'             => 'required|string|min:10|max:20',
            'email'             => 'nullable|email|max:255',
            'course_interested' => 'nullable|string|max:255',
            'slot'              => 'nullable|string|max:100',
            'enquiry_date'      => 'nullable|date',
            'status'            => 'required|in:New,Contacted,Interested,Converted,Closed',
            'notes'             => 'nullable|string|max:1000',
            'source'            => 'nullable|string|max:100',
        ]);

        $validated['coaching_id'] = $coachingId;
        if (empty($validated['enquiry_date'])) {
            $validated['enquiry_date'] = now()->toDateString();
        }
        if (empty($validated['source'])) {
            $validated['source'] = 'Manual / Walk-in';
        }

        CoachingLead::create($validated);

        return redirect()->route('coaching.leads.index')->with('success', 'New student lead created successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $coachingId = $this->getCoachingId($request);

        $lead = CoachingLead::forCoaching($coachingId)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:New,Contacted,Interested,Converted,Closed',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $lead->status = $request->status;
        if ($request->filled('notes')) {
            $newNote = '[' . now()->format('d M H:i') . '] Status changed to ' . $request->status . ': ' . $request->notes;
            $lead->notes = $lead->notes ? ($lead->notes . "\n" . $newNote) : $newNote;
        }
        $lead->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => "Lead #{$lead->id} updated to {$lead->status}",
                'lead'    => $lead,
            ]);
        }

        return redirect()->back()->with('success', "Lead #{$lead->id} status updated to {$lead->status}.");
    }

    public function update(Request $request, $id)
    {
        $coachingId = $this->getCoachingId($request);
        $lead = CoachingLead::forCoaching($coachingId)->findOrFail($id);

        $validated = $request->validate([
            'student_name'      => 'required|string|max:255',
            'phone'             => 'required|string|min:10|max:20',
            'email'             => 'nullable|email|max:255',
            'course_interested' => 'nullable|string|max:255',
            'slot'              => 'nullable|string|max:100',
            'enquiry_date'      => 'nullable|date',
            'status'            => 'required|in:New,Contacted,Interested,Converted,Closed',
            'notes'             => 'nullable|string|max:1000',
        ]);

        $lead->update($validated);

        return redirect()->back()->with('success', 'Lead details updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $coachingId = $this->getCoachingId($request);
        $lead = CoachingLead::forCoaching($coachingId)->findOrFail($id);
        $lead->delete();

        return redirect()->back()->with('success', 'Lead deleted successfully.');
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $coachingId = $this->getCoachingId($request);
        $coaching = Coaching::findOrFail($coachingId);

        $query = CoachingLead::forCoaching($coachingId);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('course')) {
            $query->where('course_interested', $request->course);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('enquiry_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('enquiry_date', '<=', $request->date_to);
        }

        $leads = $query->latest('id')->get();

        $cleanCoachingName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $coaching->coaching_name);
        $filename = "{$cleanCoachingName}_Leads_" . now()->format('Y-m-d_His') . ".csv";

        return response()->streamDownload(function () use ($leads, $coaching) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Lead ID',
                'Coaching Institute',
                'Student Name',
                'Mobile Number',
                'Email',
                'Course Interested',
                'Preferred Slot',
                'Enquiry Date',
                'Lead Status',
                'Source',
                'Notes',
                'Created At',
            ]);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->id,
                    $coaching->coaching_name,
                    $lead->student_name,
                    $lead->phone,
                    $lead->email ?: 'N/A',
                    $lead->course_interested ?: 'General',
                    $lead->slot ?: 'Standard',
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
