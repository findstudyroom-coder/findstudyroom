<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tehsil;
use App\Models\District;

class TehsilController extends Controller
{
    public function index()
    {
        $tehsils = Tehsil::with('district')->get();

        return view('admin.tehsils.index', compact('tehsils'));
    }

    public function create()
    {
        $districts = District::all();

        return view('admin.tehsils.create', compact('districts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'district_id' => 'required',
        ]);

        Tehsil::create([
            'name' => $request->name,
            'district_id' => $request->district_id,
        ]);

        return redirect()
            ->route('tehsils.index')
            ->with('success', 'Tehsil added successfully.');
    }

    public function edit(Tehsil $tehsil)
    {
        $districts = District::all();

        return view('admin.tehsils.edit', compact('tehsil', 'districts'));
    }

    public function update(Request $request, Tehsil $tehsil)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'district_id' => 'required',
        ]);

        $tehsil->update([
            'name' => $request->name,
            'district_id' => $request->district_id,
        ]);

        return redirect()
            ->route('tehsils.index')
            ->with('success', 'Tehsil updated successfully.');
    }

    public function destroy(Tehsil $tehsil)
    {
        $tehsil->delete();

        return redirect()
            ->route('tehsils.index')
            ->with('success', 'Tehsil deleted successfully.');
    }
}