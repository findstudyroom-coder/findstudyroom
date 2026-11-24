<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommandoTrial;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CommandoTrialController extends Controller
{
    /**
     * Public store: Handles submission from the frontend "Book a Free Trial" form
     * Completely independent of the libraries table!
     */
    public function publicStore(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'phone'      => 'required|string|min:10|max:15',
            'discipline' => 'nullable|string|max:255',
            'slot'       => 'nullable|string|max:100',
            'date'       => 'nullable|date',
        ]);

        // Humanize slot if needed
        $rawSlot = strtolower(trim($request->input('slot', 'dawn')));
        if ($rawSlot === 'dawn') {
            $formattedSlot = 'Dawn (06:30 AM)';
        } elseif ($rawSlot === 'dusk') {
            $formattedSlot = 'Dusk (07:00 PM)';
        } else {
            $formattedSlot = $request->input('slot') ?: 'Dawn (06:30 AM)';
        }

        $discipline = $request->input('discipline') ?: 'Shotokan Karate';
        $trialDate = $request->input('date') ?: now()->toDateString();

        $trial = CommandoTrial::create([
            'name'       => trim($request->name),
            'phone'      => trim($request->phone),
            'discipline' => $discipline,
            'slot'       => $formattedSlot,
            'date'       => $trialDate,
            'status'     => 'Pending',
            'notes'      => 'Online trial booking submitted via The Movement page',
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Trial slot booked! Sensei desk will confirm via WhatsApp within 24 hours.',
                'data'    => $trial,
            ]);
        }

        return redirect()->back()->with('success', 'Trial slot booked! Sensei desk will confirm via WhatsApp within 24 hours.');
    }

    /**
     * Admin Index: List all Commando Classes trial bookings with stats & filters
     */
    public function index(Request $request)
    {
        $stats = [
            'total'     => CommandoTrial::count(),
            'pending'   => CommandoTrial::where('status', 'Pending')->count(),
            'confirmed' => CommandoTrial::where('status', 'Confirmed')->count(),
            'attended'  => CommandoTrial::where('status', 'Attended')->count(),
            'cancelled' => CommandoTrial::where('status', 'Cancelled')->count(),
        ];

        $query = CommandoTrial::latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('discipline', 'LIKE', "%{$search}%")
                  ->orWhere('slot', 'LIKE', "%{$search}%")
                  ->orWhere('notes', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('discipline')) {
            $query->where('discipline', $request->discipline);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $commandoTrials = $query->paginate(15)->withQueryString();

        $disciplines = CommandoTrial::whereNotNull('discipline')
            ->distinct()
            ->pluck('discipline');

        return view('admin.commando_trials.index', compact('commandoTrials', 'stats', 'disciplines'));
    }

    /**
     * Admin: Update trial status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Confirmed,Attended,Cancelled',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $trial = CommandoTrial::findOrFail($id);
        $trial->status = $request->status;
        if ($request->has('notes')) {
            $trial->notes = $request->notes;
        }
        $trial->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Status updated to ' . $request->status,
                'data'    => $trial,
            ]);
        }

        return redirect()->back()->with('success', 'Trial status updated to ' . $request->status);
    }

    /**
     * Admin: Delete booking
     */
    public function destroy($id)
    {
        $trial = CommandoTrial::findOrFail($id);
        $trial->delete();

        return redirect()->back()->with('success', 'Commando Class trial record deleted successfully.');
    }

    /**
     * Admin: Export to CSV
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = CommandoTrial::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('discipline')) {
            $query->where('discipline', $request->discipline);
        }

        $records = $query->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="commando_classes_trials_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () use ($records) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['ID', 'Student Name', 'Phone', 'Primary Discipline', 'Preferred Slot', 'Trial Date', 'Status', 'Notes', 'Created At']);

            foreach ($records as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->name,
                    $row->phone,
                    $row->discipline,
                    $row->slot,
                    $row->date ? $row->date->format('Y-m-d') : '',
                    $row->status,
                    $row->notes ?? '',
                    $row->created_at ? $row->created_at->format('Y-m-d H:i') : '',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
