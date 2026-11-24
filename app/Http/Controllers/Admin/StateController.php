<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;

class StateController extends Controller
{
    public function index()
    {
        $states = State::all();

        return view('admin.states.index', compact('states'));
    }

    public function create(){
        
        return view('admin.states.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        State::create([
            'name' => $request->name,
        ]);

        return redirect()->route('states.index')->with('success', 'State added successfully.');
    }
    
    
    public function edit(State $state)
    {
        return view('admin.states.edit', compact('state'));
    }

    public function update(Request $request, State $state)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $state->update([
            'name' => $request->name,
        ]);

       return redirect()->route('states.index')->with('success', 'State update successfully.');
    }

    public function destroy(State $state)
    {
        $state->delete();

        return redirect()->back()->with('success', 'State deleted successfully.');
    }
}