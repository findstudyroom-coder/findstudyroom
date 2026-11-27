<?php

namespace App\Http\Controllers\CoachingAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coaching;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    protected function getCoaching(Request $request)
    {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $id = $request->get('coaching_id') ?: ($user->coaching_id ?: Coaching::value('id'));
        } else {
            $id = $user->coaching_id;
        }

        return Coaching::findOrFail($id);
    }

    public function edit(Request $request)
    {
        $coaching = $this->getCoaching($request);

        return view('coaching_admin.profile.edit', compact('coaching'));
    }

    public function update(Request $request)
    {
        $coaching = $this->getCoaching($request);

        $validated = $request->validate([
            'coaching_name'       => 'required|string|max:255',
            'coaching_type'       => 'nullable|string|max:100',
            'established_year'    => 'nullable|integer|min:1800|max:2100',
            'short_description'   => 'nullable|string|max:500',
            'description'         => 'nullable|string',
            'contact_person'      => 'nullable|string|max:255',
            'mobile_number'       => 'nullable|string|max:25',
            'whatsapp_number'     => 'nullable|string|max:25',
            'email'               => 'nullable|email|max:255',
            'website'             => 'nullable|string|max:255',
            'facebook'            => 'nullable|string|max:255',
            'instagram'           => 'nullable|string|max:255',
            'youtube'             => 'nullable|string|max:255',
            'state'               => 'nullable|string|max:100',
            'city'                => 'nullable|string|max:100',
            'area_locality'       => 'nullable|string|max:255',
            'full_address'        => 'nullable|string',
            'landmark'            => 'nullable|string|max:255',
            'pincode'             => 'nullable|string|max:20',
            'google_map_url'      => 'nullable|string',
            'mode'                => 'nullable|string|max:100',
            'starting_fees'       => 'nullable|numeric|min:0',
            'monthly_fees'        => 'nullable|numeric|min:0',
            'course_fees'         => 'nullable|numeric|min:0',
            'registration_fees'   => 'nullable|numeric|min:0',
            'batch_size'          => 'nullable|string|max:100',
            'faculty_experience'  => 'nullable|string',
            'qualification'       => 'nullable|string',
            'logo_file'           => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'cover_file'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_files.*'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        // Checkboxes
        $validated['scholarship_available'] = $request->has('scholarship_available');
        $validated['morning_batch']         = $request->has('morning_batch');
        $validated['afternoon_batch']       = $request->has('afternoon_batch');
        $validated['evening_batch']         = $request->has('evening_batch');
        $validated['weekend_batch']         = $request->has('weekend_batch');
        $validated['demo_class_available']  = $request->has('demo_class_available');

        // Logo Upload
        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $filename = time() . '_logo_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $destination = public_path('uploads/coachings/logos');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $validated['logo'] = 'uploads/coachings/logos/' . $filename;
        }

        // Cover Upload
        if ($request->hasFile('cover_file')) {
            $file = $request->file('cover_file');
            $filename = time() . '_cover_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $destination = public_path('uploads/coachings/covers');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $validated['cover_image'] = 'uploads/coachings/covers/' . $filename;
        }

        // Gallery Upload
        if ($request->hasFile('gallery_files')) {
            $currentGallery = is_array($coaching->gallery_images) ? $coaching->gallery_images : [];
            $destination = public_path('uploads/coachings/gallery');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            foreach ($request->file('gallery_files') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($destination, $filename);
                $currentGallery[] = 'uploads/coachings/gallery/' . $filename;
            }
            $validated['gallery_images'] = $currentGallery;
        }

        // Array tag parsing
        $validated['courses_offered'] = $this->parseArrayInput($request->input('courses_offered'));
        $validated['class_level']     = $this->parseArrayInput($request->input('class_level'));
        $validated['subjects']        = $this->parseArrayInput($request->input('subjects'));
        $validated['faculty_names']   = $this->parseArrayInput($request->input('faculty_names'));

        $coaching->update($validated);

        return redirect()->route('coaching.profile')->with('success', 'Your coaching institute profile has been successfully updated!');
    }

    public function removeGalleryImage(Request $request)
    {
        $coaching = $this->getCoaching($request);
        $imagePath = $request->input('image_path');

        $gallery = is_array($coaching->gallery_images) ? $coaching->gallery_images : [];
        $newGallery = array_values(array_filter($gallery, fn($img) => $img !== $imagePath));

        $coaching->update(['gallery_images' => $newGallery]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Image removed from gallery',
        ]);
    }

    protected function parseArrayInput($input): array
    {
        if (is_array($input)) {
            return array_values(array_filter(array_map('trim', $input)));
        }
        if (is_string($input)) {
            $parts = explode(',', $input);
            return array_values(array_filter(array_map('trim', $parts)));
        }
        return [];
    }
}
