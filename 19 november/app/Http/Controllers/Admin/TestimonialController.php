<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;

class TestimonialController extends Controller
{

    public function index()
    {
        $testimonials = Testimonial::latest()->get();
        return view('admin.testimonial.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonial.create');
    }

    public function store(Request $request)
    {

        $image = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('testimonial','public');
        }

        Testimonial::create([
            'name' => $request->name,
            'designation' => $request->designation,
            'message' => $request->message,
            'image' => $image
        ]);

        return redirect()->route('admin.testimonial.index')
        ->with('success','Testimonial Added Successfully');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.testimonial.edit', compact('testimonial'));
    }

    public function update(Request $request,$id)
    {

        $testimonial = Testimonial::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('testimonial','public');
        } else {
            $image = $testimonial->image;
        }

        $testimonial->update([
            'name' => $request->name,
            'designation' => $request->designation,
            'message' => $request->message,
            'image' => $image
        ]);

        return redirect()->route('admin.testimonial.index')
        ->with('success','Testimonial Updated Successfully');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return redirect()->route('admin.testimonial.index')
        ->with('success','Testimonial Deleted Successfully');
    }

}