<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coaching;
use Illuminate\Support\Str;

class CoachingController extends Controller
{
    /**
     * Display a listing of all coachings with statistics and filters.
     */
    public function index(Request $request)
    {
        $stats = [
            'total'    => Coaching::count(),
            'active'   => Coaching::where('status', 1)->count(),
            'inactive' => Coaching::where('status', 0)->count(),
            'cities'   => Coaching::whereNotNull('city')->where('city', '!=', '')->distinct('city')->count('city'),
        ];

        $query = Coaching::withCount(['classes', 'instructors', 'schedules', 'trials', 'plans', 'galleries']);

        // Search filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('coaching_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('area_locality', 'like', "%{$search}%")
                  ->orWhere('mobile_number', 'like', "%{$search}%")
                  ->orWhere('coaching_type', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', (int)$request->status);
        }

        // City filter
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Coaching type filter
        if ($request->filled('coaching_type')) {
            $query->where('coaching_type', $request->coaching_type);
        }

        $coachings = $query->latest('id')->paginate(15)->withQueryString();

        $cities = Coaching::whereNotNull('city')->where('city', '!=', '')->distinct()->orderBy('city')->pluck('city');
        $types = Coaching::whereNotNull('coaching_type')->where('coaching_type', '!=', '')->distinct()->orderBy('coaching_type')->pluck('coaching_type');

        return view('admin.coachings.index', compact('coachings', 'stats', 'cities', 'types'));
    }

    /**
     * Show the form for creating a new coaching.
     */
    public function create()
    {
        return view('admin.coachings.create');
    }

    /**
     * Store a newly created coaching in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'coaching_name'       => 'required|string|max:255',
            'slug'                => 'nullable|string|max:255|unique:coachings,slug',
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
            'logo'                => 'nullable|string|max:255',
            'logo_file'           => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'cover_image'         => 'nullable|string|max:255',
            'cover_file'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_files.*'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        // Clean slug generation
        $baseSlug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->coaching_name);
        $slug = $baseSlug;
        $counter = 1;
        while (Coaching::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;

        // Checkboxes
        $validated['scholarship_available'] = $request->has('scholarship_available');
        $validated['morning_batch']         = $request->has('morning_batch');
        $validated['afternoon_batch']       = $request->has('afternoon_batch');
        $validated['evening_batch']         = $request->has('evening_batch');
        $validated['weekend_batch']         = $request->has('weekend_batch');
        $validated['demo_class_available']  = $request->has('demo_class_available');
        $validated['status']                = $request->has('status') ? 1 : ($request->filled('status') ? (int)$request->status : 1);

        // Upload Logo
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

        // Upload Cover Image
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

        // Upload Gallery Images
        if ($request->hasFile('gallery_files')) {
            $gallery = [];
            $destination = public_path('uploads/coachings/gallery');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            foreach ($request->file('gallery_files') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($destination, $filename);
                $gallery[] = 'uploads/coachings/gallery/' . $filename;
            }
            $validated['gallery_images'] = $gallery;
        }

        // Multi-selects & comma tags
        $validated['courses_offered'] = $this->parseArrayInput($request->input('courses_offered'));
        $validated['class_level']     = $this->parseArrayInput($request->input('class_level'));
        $validated['subjects']        = $this->parseArrayInput($request->input('subjects'));
        $validated['faculty_names']   = $this->parseArrayInput($request->input('faculty_names'));

        Coaching::create($validated);

        return redirect()->route('coachings.index')->with('success', 'Coaching institute "' . $validated['coaching_name'] . '" added successfully!');
    }

    /**
     * Show the form for editing the specified coaching.
     */
    public function edit($id)
    {
        $coaching = Coaching::findOrFail($id);
        return view('admin.coachings.edit', compact('coaching'));
    }

    /**
     * Update the specified coaching in database.
     */
    public function update(Request $request, $id)
    {
        $coaching = Coaching::findOrFail($id);

        $validated = $request->validate([
            'coaching_name'       => 'required|string|max:255',
            'slug'                => 'nullable|string|max:255|unique:coachings,slug,' . $id,
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
            'logo'                => 'nullable|string|max:255',
            'logo_file'           => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'cover_image'         => 'nullable|string|max:255',
            'cover_file'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_files.*'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        // Slug
        if ($request->filled('slug')) {
            $baseSlug = Str::slug($request->slug);
            $slug = $baseSlug;
            $counter = 1;
            while (Coaching::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        // Checkboxes
        $validated['scholarship_available'] = $request->has('scholarship_available');
        $validated['morning_batch']         = $request->has('morning_batch');
        $validated['afternoon_batch']       = $request->has('afternoon_batch');
        $validated['evening_batch']         = $request->has('evening_batch');
        $validated['weekend_batch']         = $request->has('weekend_batch');
        $validated['demo_class_available']  = $request->has('demo_class_available');
        $validated['status']                = $request->has('status') ? 1 : 0;

        // Upload Logo
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

        // Upload Cover Image
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

        // Upload Gallery Images (Append to existing)
        if ($request->hasFile('gallery_files')) {
            $gallery = is_array($coaching->gallery_images) ? $coaching->gallery_images : [];
            $destination = public_path('uploads/coachings/gallery');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            foreach ($request->file('gallery_files') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($destination, $filename);
                $gallery[] = 'uploads/coachings/gallery/' . $filename;
            }
            $validated['gallery_images'] = $gallery;
        }

        // Multi-selects & comma tags
        $validated['courses_offered'] = $this->parseArrayInput($request->input('courses_offered'));
        $validated['class_level']     = $this->parseArrayInput($request->input('class_level'));
        $validated['subjects']        = $this->parseArrayInput($request->input('subjects'));
        $validated['faculty_names']   = $this->parseArrayInput($request->input('faculty_names'));

        $coaching->update($validated);

        return redirect()->route('coachings.index')->with('success', 'Coaching institute "' . $coaching->coaching_name . '" updated successfully!');
    }

    /**
     * Remove the specified coaching from database.
     */
    public function destroy($id)
    {
        $coaching = Coaching::findOrFail($id);
        $name = $coaching->coaching_name;
        $coaching->delete();

        return redirect()->route('coachings.index')->with('success', 'Coaching institute "' . $name . '" deleted successfully.');
    }

    /**
     * Toggle active/inactive status of coaching.
     */
    public function toggleStatus(Request $request, $id)
    {
        $coaching = Coaching::findOrFail($id);
        $coaching->status = $coaching->status ? 0 : 1;
        $coaching->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status'  => $coaching->status,
                'message' => 'Coaching status updated to ' . ($coaching->status ? 'Active' : 'Inactive'),
            ]);
        }

        return back()->with('success', 'Status updated successfully.');
    }

    /**
     * Helper to parse array or comma-separated string inputs.
     */
    private function parseArrayInput($input): ?array
    {
        if (empty($input)) {
            return null;
        }

        if (is_array($input)) {
            $flattened = [];
            foreach ($input as $item) {
                if (is_string($item) && str_contains($item, ',')) {
                    foreach (explode(',', $item) as $sub) {
                        $trimmed = trim($sub);
                        if ($trimmed !== '') {
                            $flattened[] = $trimmed;
                        }
                    }
                } else {
                    $trimmed = trim((string)$item);
                    if ($trimmed !== '') {
                        $flattened[] = $trimmed;
                    }
                }
            }
            return array_values(array_unique($flattened));
        }

        if (is_string($input)) {
            $parts = array_map('trim', explode(',', $input));
            return array_values(array_filter($parts, fn($val) => $val !== ''));
        }

        return null;
    }
}
