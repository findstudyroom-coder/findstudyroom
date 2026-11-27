<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommandoGallery;
use App\Models\Coaching;
use Illuminate\Http\Request;

class CommandoGalleryController extends Controller
{
    /**
     * Display a listing of Studio Gallery items
     */
    public function index(Request $request)
    {
        $query = CommandoGallery::query()->orderBy('order_no', 'asc')->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%")
                  ->orWhere('coaching_name', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('coaching_id')) {
            $query->where('coaching_id', $request->coaching_id);
        } elseif ($request->filled('coaching_name')) {
            $query->where('coaching_name', $request->coaching_name);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $galleries = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => CommandoGallery::count(),
            'active'   => CommandoGallery::where('status', 1)->count(),
            'inactive' => CommandoGallery::where('status', 0)->count(),
        ];

        $coachings = Coaching::orderBy('coaching_name')->get();

        // Unique categories for filtering
        $categories = CommandoGallery::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category');

        return view('admin.commando_galleries.index', compact('galleries', 'stats', 'coachings', 'categories'));
    }

    /**
     * Show the form for creating a new gallery item
     */
    public function create()
    {
        $coachings = Coaching::orderBy('coaching_name')->get();
        return view('admin.commando_galleries.create', compact('coachings'));
    }

    /**
     * Store a newly created gallery photo in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'coaching_id'   => 'nullable|exists:coachings,id',
            'coaching_name' => 'nullable|string|max:255',
            'title'         => 'required|string|max:255',
            'category'      => 'nullable|string|max:100',
            'image'         => 'nullable|string',
            'image_file'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'order_no'      => 'nullable|integer',
            'status'        => 'nullable|boolean',
        ]);

        $coaching = $request->filled('coaching_id') ? Coaching::find($request->coaching_id) : null;
        $validated['coaching_id'] = $coaching ? $coaching->id : 1;
        $validated['coaching_name'] = $coaching ? $coaching->coaching_name : ($request->input('coaching_name') ?: 'The Movement');
        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['order_no'] = $request->filled('order_no') ? (int)$request->order_no : 0;

        // Image file upload
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $destination = public_path('uploads/commando/gallery');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $validated['image'] = 'uploads/commando/gallery/' . $filename;
        } elseif (!empty($validated['image'])) {
            $validated['image'] = ltrim(preg_replace('/^file:\/\/\/?/i', '', trim($validated['image'])), '/');
        }

        if (empty($validated['image'])) {
            return back()->withInput()->withErrors(['image_file' => 'Please provide an image file or a valid image URL.']);
        }

        unset($validated['image_file']);

        CommandoGallery::create($validated);

        return redirect()->route('commando-galleries.index')->with('success', 'Studio Gallery image added successfully.');
    }

    /**
     * Show the form for editing the specified gallery photo
     */
    public function edit($id)
    {
        $gallery = CommandoGallery::findOrFail($id);
        $coachings = Coaching::orderBy('coaching_name')->get();

        return view('admin.commando_galleries.edit', compact('gallery', 'coachings'));
    }

    /**
     * Update the specified gallery photo in database
     */
    public function update(Request $request, $id)
    {
        $gallery = CommandoGallery::findOrFail($id);

        $validated = $request->validate([
            'coaching_id'   => 'nullable|exists:coachings,id',
            'coaching_name' => 'nullable|string|max:255',
            'title'         => 'required|string|max:255',
            'category'      => 'nullable|string|max:100',
            'image'         => 'nullable|string',
            'image_file'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'order_no'      => 'nullable|integer',
            'status'        => 'nullable|boolean',
        ]);

        $coaching = $request->filled('coaching_id') ? Coaching::find($request->coaching_id) : null;
        $validated['coaching_id'] = $coaching ? $coaching->id : ($gallery->coaching_id ?: 1);
        $validated['coaching_name'] = $coaching ? $coaching->coaching_name : ($request->input('coaching_name') ?: $gallery->coaching_name);
        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['order_no'] = $request->filled('order_no') ? (int)$request->order_no : 0;

        // Image file upload if uploaded
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $destination = public_path('uploads/commando/gallery');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);

            // Delete old file if existed locally
            if ($gallery->image && !str_starts_with($gallery->image, 'http') && file_exists(public_path($gallery->image))) {
                @unlink(public_path($gallery->image));
            }

            $validated['image'] = 'uploads/commando/gallery/' . $filename;
        } elseif (!empty($validated['image'])) {
            $validated['image'] = ltrim(preg_replace('/^file:\/\/\/?/i', '', trim($validated['image'])), '/');
        } else {
            $validated['image'] = $gallery->image;
        }

        unset($validated['image_file']);

        $gallery->update($validated);

        return redirect()->route('commando-galleries.index')->with('success', 'Studio Gallery image updated successfully.');
    }

    /**
     * Remove the specified gallery item from database
     */
    public function destroy($id)
    {
        $gallery = CommandoGallery::findOrFail($id);

        if ($gallery->image && !str_starts_with($gallery->image, 'http') && file_exists(public_path($gallery->image))) {
            @unlink(public_path($gallery->image));
        }

        $gallery->delete();

        return redirect()->route('commando-galleries.index')->with('success', 'Studio Gallery photo deleted successfully.');
    }

    /**
     * Toggle active/inactive status
     */
    public function toggleStatus($id)
    {
        $gallery = CommandoGallery::findOrFail($id);
        $gallery->status = !$gallery->status;
        $gallery->save();

        return redirect()->back()->with('success', 'Gallery item visibility updated to ' . ($gallery->status ? 'Active' : 'Inactive') . '.');
    }
}
