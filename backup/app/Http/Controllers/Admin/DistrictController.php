<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\State;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::with('state')->get();

        return view('admin.districts.index', compact('districts'));
    }

    public function create()
    {
        $states = State::all();

        return view('admin.districts.create', compact('states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required',
        ]);

        District::create([
            'name' => $request->name,
            'state_id' => $request->state_id,
        ]);

        return redirect()
            ->route('districts.index')
            ->with('success', 'District added successfully.');
    }

    public function edit(District $district)
    {
        $states = State::all();

        return view('admin.districts.edit', compact('district', 'states'));
    }

    public function update(Request $request, District $district)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required',
        ]);

        $district->update([
            'name' => $request->name,
            'state_id' => $request->state_id,
        ]);

        return redirect()
            ->route('districts.index')
            ->with('success', 'District updated successfully.');
    }

    public function destroy(District $district)
    {
        $district->delete();

        return redirect()
            ->route('districts.index')
            ->with('success', 'District deleted successfully.');
    }
}