<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coaching;
use Illuminate\Support\Str;

class CoachingController extends Controller
{
    public function index(Request $request)
    {
        $query = Coaching::query();

        if ($request->filled('search')) {
            $query->where('coaching_name', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%')
                  ->orWhere('mobile_number', 'like', '%' . $request->search . '%');
        }

        $coachings = $query->latest()->paginate(20);
        return view('admin.coachings.index', compact('coachings'));
    }

    public function create()
    {
        return view('admin.coachings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'coaching_name' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            // add more validations as needed
        ]);

        $data = $request->except(['logo', 'cover_image', 'gallery_images']);
        
        $data['slug'] = Str::slug($request->coaching_name . '-' . time());

        // Handle Checkboxes manually if needed (Laravel handles boolean if sent as 1/0, but checkboxes only send if checked)
        $data['scholarship_available'] = $request->has('scholarship_available');
        $data['morning_batch'] = $request->has('morning_batch');
        $data['afternoon_batch'] = $request->has('afternoon_batch');
        $data['evening_batch'] = $request->has('evening_batch');
        $data['weekend_batch'] = $request->has('weekend_batch');
        $data['demo_class_available'] = $request->has('demo_class_available');

        // Handling files
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('coachings/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('coachings/covers', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $gallery = [];
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $file->store('coachings/gallery', 'public');
            }
            $data['gallery_images'] = $gallery;
        }

        Coaching::create($data);

        return redirect()->route('coachings.index')->with('success', 'Coaching added successfully!');
    }

    public function edit($id)
    {
        $coaching = Coaching::findOrFail($id);
        return view('admin.coachings.edit', compact('coaching'));
    }

    public function update(Request $request, $id)
    {
        $coaching = Coaching::findOrFail($id);

        $request->validate([
            'coaching_name' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
        ]);

        $data = $request->except(['logo', 'cover_image', 'gallery_images']);
        
        $data['scholarship_available'] = $request->has('scholarship_available');
        $data['morning_batch'] = $request->has('morning_batch');
        $data['afternoon_batch'] = $request->has('afternoon_batch');
        $data['evening_batch'] = $request->has('evening_batch');
        $data['weekend_batch'] = $request->has('weekend_batch');
        $data['demo_class_available'] = $request->has('demo_class_available');

        // Handling files
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('coachings/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('coachings/covers', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $gallery = $coaching->gallery_images ?? [];
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $file->store('coachings/gallery', 'public');
            }
            $data['gallery_images'] = $gallery;
        }

        $coaching->update($data);

        return redirect()->route('coachings.index')->with('success', 'Coaching updated successfully!');
    }

    public function destroy($id)
    {
        $coaching = Coaching::findOrFail($id);
        $coaching->delete();
        
        return redirect()->route('coachings.index')->with('success', 'Coaching deleted successfully!');
    }
}
