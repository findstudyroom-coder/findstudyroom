<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommandoSchedule;
use Illuminate\Http\Request;

class CommandoScheduleController extends Controller
{
    /**
     * Display a listing of weekly schedules.
     */
    public function index(Request $request)
    {
        $query = CommandoSchedule::query()->orderBy('order_no', 'asc')->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('day', 'LIKE', "%{$search}%")
                  ->orWhere('coaching_name', 'LIKE', "%{$search}%")
                  ->orWhere('dawn_title', 'LIKE', "%{$search}%")
                  ->orWhere('dawn_subtitle', 'LIKE', "%{$search}%")
                  ->orWhere('cadet_title', 'LIKE', "%{$search}%")
                  ->orWhere('cadet_subtitle', 'LIKE', "%{$search}%")
                  ->orWhere('combat_title', 'LIKE', "%{$search}%")
                  ->orWhere('combat_subtitle', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('coaching_id')) {
            $query->where('coaching_id', $request->coaching_id);
        } elseif ($request->filled('coaching_name')) {
            $query->where('coaching_name', $request->coaching_name);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $schedules = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => CommandoSchedule::count(),
            'active'   => CommandoSchedule::where('status', 1)->count(),
            'inactive' => CommandoSchedule::where('status', 0)->count(),
        ];

        $coachings = \App\Models\Coaching::orderBy('coaching_name')->get();

        return view('admin.commando_schedules.index', compact('schedules', 'stats', 'coachings'));
    }

    /**
     * Show form to create a new schedule day.
     */
    public function create()
    {
        $nextOrder = (CommandoSchedule::max('order_no') ?? 0) + 1;
        $coachings = \App\Models\Coaching::orderBy('coaching_name')->get();
        return view('admin.commando_schedules.create', compact('nextOrder', 'coachings'));
    }

    /**
     * Store newly created schedule day.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'coaching_id'     => 'nullable|exists:coachings,id',
            'coaching_name'   => 'nullable|string|max:255',
            'day'             => 'required|string|max:50',
            'order_no'        => 'nullable|integer',
            'status'          => 'nullable|boolean',
            'dawn_title'      => 'nullable|string|max:255',
            'dawn_subtitle'   => 'nullable|string|max:255',
            'dawn_time'       => 'nullable|string|max:50',
            'cadet_title'     => 'nullable|string|max:255',
            'cadet_subtitle'  => 'nullable|string|max:255',
            'cadet_time'      => 'nullable|string|max:50',
            'combat_title'    => 'nullable|string|max:255',
            'combat_subtitle' => 'nullable|string|max:255',
            'combat_time'     => 'nullable|string|max:50',
        ]);

        $coaching = $request->filled('coaching_id') ? \App\Models\Coaching::find($request->coaching_id) : null;
        $validated['coaching_id'] = $coaching ? $coaching->id : 1;
        $validated['coaching_name'] = $coaching ? $coaching->coaching_name : ($request->input('coaching_name') ?: 'The Movement');
        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['order_no'] = $request->filled('order_no') ? (int)$request->order_no : 0;
        $validated['dawn_time'] = $request->input('dawn_time') ?: '06:30 AM';
        $validated['cadet_time'] = $request->input('cadet_time') ?: '04:30 PM';
        $validated['combat_time'] = $request->input('combat_time') ?: '07:00 PM';

        CommandoSchedule::create($validated);

        return redirect()->route('commando-schedules.index')->with('success', 'Schedule day created successfully.');
    }

    /**
     * Show form for editing the specified schedule day.
     */
    public function edit($id)
    {
        $schedule = CommandoSchedule::findOrFail($id);
        $coachings = \App\Models\Coaching::orderBy('coaching_name')->get();
        return view('admin.commando_schedules.edit', compact('schedule', 'coachings'));
    }

    /**
     * Update specified schedule day in database.
     */
    public function update(Request $request, $id)
    {
        $schedule = CommandoSchedule::findOrFail($id);

        $validated = $request->validate([
            'coaching_id'     => 'nullable|exists:coachings,id',
            'coaching_name'   => 'nullable|string|max:255',
            'day'             => 'required|string|max:50',
            'order_no'        => 'nullable|integer',
            'status'          => 'nullable|boolean',
            'dawn_title'      => 'nullable|string|max:255',
            'dawn_subtitle'   => 'nullable|string|max:255',
            'dawn_time'       => 'nullable|string|max:50',
            'cadet_title'     => 'nullable|string|max:255',
            'cadet_subtitle'  => 'nullable|string|max:255',
            'cadet_time'      => 'nullable|string|max:50',
            'combat_title'    => 'nullable|string|max:255',
            'combat_subtitle' => 'nullable|string|max:255',
            'combat_time'     => 'nullable|string|max:50',
        ]);

        $coaching = $request->filled('coaching_id') ? \App\Models\Coaching::find($request->coaching_id) : null;
        $validated['coaching_id'] = $coaching ? $coaching->id : ($schedule->coaching_id ?: 1);
        $validated['coaching_name'] = $coaching ? $coaching->coaching_name : ($request->input('coaching_name') ?: $schedule->coaching_name);

        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['order_no'] = $request->filled('order_no') ? (int)$request->order_no : 0;
        $validated['dawn_time'] = $request->input('dawn_time') ?: '06:30 AM';
        $validated['cadet_time'] = $request->input('cadet_time') ?: '04:30 PM';
        $validated['combat_time'] = $request->input('combat_time') ?: '07:00 PM';

        $schedule->update($validated);

        return redirect()->route('commando-schedules.index')->with('success', 'Schedule day updated successfully.');
    }

    /**
     * Delete specified schedule day.
     */
    public function destroy($id)
    {
        $schedule = CommandoSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('commando-schedules.index')->with('success', 'Schedule day deleted successfully.');
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus($id)
    {
        $schedule = CommandoSchedule::findOrFail($id);
        $schedule->status = !$schedule->status;
        $schedule->save();

        return redirect()->back()->with('success', 'Schedule for ' . $schedule->day . ' set to ' . ($schedule->status ? 'Active' : 'Inactive') . '.');
    }
}
