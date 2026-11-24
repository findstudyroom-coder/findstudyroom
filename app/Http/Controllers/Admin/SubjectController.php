<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    // Show Subjects List
    public function index()
    {
        $Subjects = Subject::latest()->get();
        return view('admin.Subject.index', compact('Subjects'));
    }

    // Show Create Form
    public function create()
    {
        return view('admin.Subject.create');
    }

    // Store Subject
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
          
        ]);

        // $imageName = null;

        // if ($request->hasFile('image')) {
        //     $imageName = time().'_'.$request->image->getClientOriginalName();
        //     $request->image->move(public_path('uploads/Subjects'), $imageName);
        // }

        Subject::create([
            'name' => $request->name,
            // 'position' => $request->position,
            // 'image' => $imageName,
            // 'status' => $request->status ?? 1
        ]);

        return redirect()->route('Subject.index')->with('success', 'Subject Added Successfully');
    }

    // Edit Subject
    public function edit($id)
    {
        $Subject = Subject::findOrFail($id);
        return view('admin.Subject.edit', compact('Subject'));
    }

    // Update Subject
    public function update(Request $request, $id)
    {
        $Subject = Subject::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            // 'position' => 'required|string|max:255',
            // 'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // $imageName = $Subject->image;

        // if ($request->hasFile('image')) {

        //     // delete old image
        //     if ($Subject->image && file_exists(public_path('uploads/Subjects/'.$Subject->image))) {
        //         unlink(public_path('uploads/Subjects/'.$Subject->image));
        //     }

        //     $imageName = time().'_'.$request->image->getClientOriginalName();
        //     $request->image->move(public_path('uploads/Subjects'), $imageName);
        // }

        $Subject->update([
            'name' => $request->name,
            // 'position' => $request->position,
            // 'image' => $imageName,
            // 'status' => $request->status ?? 1
        ]);

        return redirect()->route('Subject.index')->with('success', 'Subject Updated Successfully');
    }

    // Delete Subject
    public function destroy($id)
    {
        $Subject = Subject::findOrFail($id);

        // if ($Subject->image && file_exists(public_path('uploads/Subjects/'.$Subject->image))) {
        //     unlink(public_path('uploads/Subjects/'.$Subject->image));
        // }

        $Subject->delete();

        return redirect()->back()->with('success', 'Subject Deleted Successfully');
    }
}