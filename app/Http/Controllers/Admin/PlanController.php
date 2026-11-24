<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::latest()->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'amount' => 'required|numeric',
        ]);

        Plan::create($request->only(['name','amount','validity','whats_text_limit','text_limit']));

        return redirect()->route('plans.index')->with('success', 'Plan created successfully');
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'amount' => 'required|numeric',
        ]);

        $plan = Plan::findOrFail($id);
        $plan->update($request->only(['name','amount','validity','whats_text_limit','text_limit']));

        return redirect()->route('plans.index')->with('success', 'Plan updated successfully');
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return redirect()->back()->with('success', 'Plan deleted successfully');
    }
}
