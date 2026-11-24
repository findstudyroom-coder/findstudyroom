<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommandoClass;
use Illuminate\Http\Request;

class CommandoClassController extends Controller
{
    /**
     * Display a listing of Commando Classes (Disciplines)
     */
    public function index(Request $request)
    {
        $query = CommandoClass::query()->orderBy('order_no', 'asc')->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('coaching_name', 'LIKE', "%{$search}%")
                  ->orWhere('tag', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('instructor_name', 'LIKE', "%{$search}%");
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

        $classes = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => CommandoClass::count(),
            'active'   => CommandoClass::where('status', 1)->count(),
            'inactive' => CommandoClass::where('status', 0)->count(),
        ];

        $coachings = \App\Models\Coaching::orderBy('coaching_name')->get();

        return view('admin.commando_classes.index', compact('classes', 'stats', 'coachings'));
    }

    /**
     * Show form to create a new Commando Class
     */
    public function create()
    {
        $coachings = \App\Models\Coaching::orderBy('coaching_name')->get();
        return view('admin.commando_classes.create', compact('coachings'));
    }

    /**
     * Store newly created Commando Class in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'coaching_id'      => 'nullable|exists:coachings,id',
            'coaching_name'    => 'nullable|string|max:255',
            'title'            => 'required|string|max:255',
            'tag'              => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'duration'         => 'nullable|string|max:100',
            'level'            => 'nullable|string|max:100',
            'color_theme'      => 'nullable|string|max:50',
            'icon'             => 'nullable|string',
            'instructor_name'  => 'nullable|string|max:255',
            'slot_window'      => 'nullable|string|max:255',
            'price'            => 'nullable|string|max:100',
            'location_address' => 'nullable|string',
            'timings'          => 'nullable|string|max:255',
            'phone'            => 'nullable|string|max:50',
            'map_url'          => 'nullable|string',
            'order_no'         => 'nullable|integer',
            'status'           => 'nullable|boolean',
        ]);

        $coaching = $request->filled('coaching_id') ? \App\Models\Coaching::find($request->coaching_id) : null;
        $validated['coaching_id'] = $coaching ? $coaching->id : 1;
        $validated['coaching_name'] = $coaching ? $coaching->coaching_name : ($request->input('coaching_name') ?: 'The Movement');
        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['order_no'] = $request->filled('order_no') ? (int)$request->order_no : 0;
        $validated['duration'] = $request->input('duration') ?: '60 MIN';
        $validated['level'] = $request->input('level') ?: 'ALL BELT TIERS';
        $validated['color_theme'] = $request->input('color_theme') ?: 'blue';

        CommandoClass::create($validated);

        return redirect()->route('commando-classes.index')->with('success', 'Discipline class added successfully.');
    }

    /**
     * Show form for editing the specified Commando Class
     */
    public function edit($id)
    {
        $class = CommandoClass::findOrFail($id);
        $coachings = \App\Models\Coaching::orderBy('coaching_name')->get();
        return view('admin.commando_classes.edit', compact('class', 'coachings'));
    }

    /**
     * Update specified Commando Class in database
     */
    public function update(Request $request, $id)
    {
        $class = CommandoClass::findOrFail($id);

        $validated = $request->validate([
            'coaching_id'      => 'nullable|exists:coachings,id',
            'coaching_name'    => 'nullable|string|max:255',
            'title'            => 'required|string|max:255',
            'tag'              => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'duration'         => 'nullable|string|max:100',
            'level'            => 'nullable|string|max:100',
            'color_theme'      => 'nullable|string|max:50',
            'icon'             => 'nullable|string',
            'instructor_name'  => 'nullable|string|max:255',
            'slot_window'      => 'nullable|string|max:255',
            'price'            => 'nullable|string|max:100',
            'location_address' => 'nullable|string',
            'timings'          => 'nullable|string|max:255',
            'phone'            => 'nullable|string|max:50',
            'map_url'          => 'nullable|string',
            'order_no'         => 'nullable|integer',
            'status'           => 'nullable|boolean',
        ]);

        $coaching = $request->filled('coaching_id') ? \App\Models\Coaching::find($request->coaching_id) : null;
        $validated['coaching_id'] = $coaching ? $coaching->id : ($class->coaching_id ?: 1);
        $validated['coaching_name'] = $coaching ? $coaching->coaching_name : ($request->input('coaching_name') ?: $class->coaching_name);
        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['order_no'] = $request->filled('order_no') ? (int)$request->order_no : 0;
        $validated['duration'] = $request->input('duration') ?: '60 MIN';
        $validated['level'] = $request->input('level') ?: 'ALL BELT TIERS';
        $validated['color_theme'] = $request->input('color_theme') ?: 'blue';

        $class->update($validated);

        return redirect()->route('commando-classes.index')->with('success', 'Discipline class updated successfully.');
    }

    /**
     * Delete specified Commando Class
     */
    public function destroy($id)
    {
        $class = CommandoClass::findOrFail($id);
        $class->delete();

        return redirect()->route('commando-classes.index')->with('success', 'Commando Class deleted successfully.');
    }

    /**
     * Toggle active/inactive status
     */
    public function toggleStatus($id)
    {
        $class = CommandoClass::findOrFail($id);
        $class->status = !$class->status;
        $class->save();

        return redirect()->back()->with('success', 'Commando Class status updated to ' . ($class->status ? 'Active' : 'Inactive') . '.');
    }
}
