<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\About;
use Illuminate\Support\Facades\Log;

class AboutController extends Controller
{

    // Show all about records
    public function index()
    {
        $abouts = About::latest()->get();
        return view('admin.about.index', compact('abouts'));
    }


    // Show add form
    public function create()
    {
        return view('admin.about.create');
    }


    // Store data
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|image'
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('about','public');
        }

        About::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'description_two' => $request->description_two,
            'image' => $image
        ]);

        return redirect()->route('admin.about.index')->with('success','About Added Successfully');
    }


    // Show edit form
    public function edit($id)
    {
        $about = About::findOrFail($id);
        return view('admin.about.edit', compact('about'));
    }


    // Update data


public function update(Request $request, $id)
{
    try {

        $about = About::findOrFail($id);

        $about->title = $request->title;
        $about->subtitle = $request->subtitle;
        $about->description = $request->description;
        $about->description_two = $request->description_two;

        // 🔥 Existing images
        $images = $about->images ? json_decode($about->images) : [];

        // ❌ Remove selected images
        if ($request->remove_images) {
            foreach ($request->remove_images as $removeImg) {

                // delete from array
                if (($key = array_search($removeImg, $images)) !== false) {
                    unset($images[$key]);
                }

                // delete file from storage
                if (\Storage::disk('public')->exists($removeImg)) {
                    \Storage::disk('public')->delete($removeImg);
                }
            }
        }

        // ✅ Add new images (append)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('about', 'public');
                $images[] = $path;
            }
        }

        // reindex array
        $about->images = json_encode(array_values($images));

        $about->save();

       return redirect()->route('admin.about.index')->with('success','About update Successfully');

    } catch (\Exception $e) {

        return back()->with('error', $e->getMessage());
    }
}


    // Delete record
    public function destroy($id)
    {
        $about = About::findOrFail($id);
        $about->delete();

        return redirect()->route('admin.about.index')->with('success','About Deleted Successfully');
    }

}