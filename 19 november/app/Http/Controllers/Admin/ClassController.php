<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;

class ClassController extends Controller
{
    // 🔹 INDEX (list)
    public function index()
    {
        $classes = ClassModel::latest()->get();
        return view('admin.classes.index', compact('classes'));
    }

    // 🔹 CREATE PAGE
    public function create()
    {
        return view('admin.classes.create');
    }

    // 🔹 STORE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:classes,name'
        ]);

        ClassModel::create([
            'name' => $request->name
        ]);

        return redirect()->route('classes.index')
            ->with('success', 'Class added successfully');
    }

    // 🔹 EDIT PAGE
    public function edit($id)
    {
        $class = ClassModel::findOrFail($id);
        return view('admin.classes.edit', compact('class'));
    }

    // 🔹 UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:classes,name,' . $id
        ]);

        $class = ClassModel::findOrFail($id);
        $class->update([
            'name' => $request->name
        ]);

        return redirect()->route('classes.index')
            ->with('success', 'Class updated');
    }

    // 🔹 DELETE
    public function destroy($id)
    {
        $class = ClassModel::findOrFail($id);
        $class->delete();

        return back()->with('success', 'Class deleted');
    }
}