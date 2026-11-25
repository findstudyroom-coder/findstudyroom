<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommandoPlan;
use App\Models\Coaching;
use Illuminate\Http\Request;

class CommandoPlanController extends Controller
{
    /**
     * Display a listing of Commando Membership Plans
     */
    public function index(Request $request)
    {
        $query = CommandoPlan::query()->orderBy('order_no', 'asc')->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('tag', 'LIKE', "%{$search}%")
                  ->orWhere('subtitle', 'LIKE', "%{$search}%")
                  ->orWhere('price', 'LIKE', "%{$search}%")
                  ->orWhere('coaching_name', 'LIKE', "%{$search}%");
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

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        $plans = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => CommandoPlan::count(),
            'active'   => CommandoPlan::where('status', 1)->count(),
            'featured' => CommandoPlan::where('is_featured', 1)->count(),
            'inactive' => CommandoPlan::where('status', 0)->count(),
        ];

        $coachings = Coaching::orderBy('coaching_name')->get();

        return view('admin.commando_plans.index', compact('plans', 'stats', 'coachings'));
    }

    /**
     * Show the form for creating a new plan
     */
    public function create()
    {
        $coachings = Coaching::orderBy('coaching_name')->get();
        return view('admin.commando_plans.create', compact('coachings'));
    }

    /**
     * Store a newly created plan in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'coaching_id'    => 'nullable|exists:coachings,id',
            'coaching_name'  => 'nullable|string|max:255',
            'title'          => 'required|string|max:255',
            'tag'            => 'nullable|string|max:255',
            'subtitle'       => 'nullable|string',
            'price'          => 'required|string|max:100',
            'billing_period' => 'nullable|string|max:50',
            'features'       => 'nullable|string',
            'is_featured'    => 'nullable|boolean',
            'featured_badge' => 'nullable|string|max:100',
            'button_text'    => 'nullable|string|max:100',
            'color_theme'    => 'nullable|string|max:50',
            'order_no'       => 'nullable|integer',
            'status'         => 'nullable|boolean',
        ]);

        $coaching = $request->filled('coaching_id') ? Coaching::find($request->coaching_id) : null;
        $validated['coaching_id'] = $coaching ? $coaching->id : 1;
        $validated['coaching_name'] = $coaching ? $coaching->coaching_name : ($request->input('coaching_name') ?: 'The Movement');
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['billing_period'] = $request->input('billing_period') ?: '/month';
        $validated['button_text'] = $request->input('button_text') ?: 'Select Plan';
        $validated['color_theme'] = $request->input('color_theme') ?: 'slate';
        $validated['order_no'] = $request->filled('order_no') ? (int)$request->order_no : 0;

        CommandoPlan::create($validated);

        return redirect()->route('commando-plans.index')->with('success', 'Commando Membership Plan created successfully.');
    }

    /**
     * Show the form for editing the specified plan
     */
    public function edit($id)
    {
        $plan = CommandoPlan::findOrFail($id);
        $coachings = Coaching::orderBy('coaching_name')->get();

        return view('admin.commando_plans.edit', compact('plan', 'coachings'));
    }

    /**
     * Update the specified plan in database
     */
    public function update(Request $request, $id)
    {
        $plan = CommandoPlan::findOrFail($id);

        $validated = $request->validate([
            'coaching_id'    => 'nullable|exists:coachings,id',
            'coaching_name'  => 'nullable|string|max:255',
            'title'          => 'required|string|max:255',
            'tag'            => 'nullable|string|max:255',
            'subtitle'       => 'nullable|string',
            'price'          => 'required|string|max:100',
            'billing_period' => 'nullable|string|max:50',
            'features'       => 'nullable|string',
            'is_featured'    => 'nullable|boolean',
            'featured_badge' => 'nullable|string|max:100',
            'button_text'    => 'nullable|string|max:100',
            'color_theme'    => 'nullable|string|max:50',
            'order_no'       => 'nullable|integer',
            'status'         => 'nullable|boolean',
        ]);

        $coaching = $request->filled('coaching_id') ? Coaching::find($request->coaching_id) : null;
        $validated['coaching_id'] = $coaching ? $coaching->id : ($plan->coaching_id ?: 1);
        $validated['coaching_name'] = $coaching ? $coaching->coaching_name : ($request->input('coaching_name') ?: $plan->coaching_name);
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['billing_period'] = $request->input('billing_period') ?: '/month';
        $validated['button_text'] = $request->input('button_text') ?: 'Select Plan';
        $validated['color_theme'] = $request->input('color_theme') ?: 'slate';
        $validated['order_no'] = $request->filled('order_no') ? (int)$request->order_no : 0;

        $plan->update($validated);

        return redirect()->route('commando-plans.index')->with('success', 'Commando Membership Plan updated successfully.');
    }

    /**
     * Remove the specified plan from database
     */
    public function destroy($id)
    {
        $plan = CommandoPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('commando-plans.index')->with('success', 'Plan deleted successfully.');
    }

    /**
     * Toggle active/inactive status
     */
    public function toggleStatus($id)
    {
        $plan = CommandoPlan::findOrFail($id);
        $plan->status = !$plan->status;
        $plan->save();

        return redirect()->back()->with('success', 'Plan status changed to ' . ($plan->status ? 'Active' : 'Inactive') . '.');
    }
}
