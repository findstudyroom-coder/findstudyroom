<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommandoInstructor;
use Illuminate\Http\Request;

class CommandoInstructorController extends Controller
{
    /**
     * List all Master Instructors
     */
    public function index(Request $request)
    {
        $query = CommandoInstructor::query()->orderBy('order_no', 'asc')->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('coaching_name', 'LIKE', "%{$search}%")
                  ->orWhere('role_title', 'LIKE', "%{$search}%")
                  ->orWhere('rank_badge', 'LIKE', "%{$search}%")
                  ->orWhere('bio', 'LIKE', "%{$search}%")
                  ->orWhere('specialties', 'LIKE', "%{$search}%");
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

        $instructors = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => CommandoInstructor::count(),
            'active'   => CommandoInstructor::where('status', 1)->count(),
            'inactive' => CommandoInstructor::where('status', 0)->count(),
        ];

        $coachings = \App\Models\Coaching::orderBy('coaching_name')->get();

        return view('admin.commando_instructors.index', compact('instructors', 'stats', 'coachings'));
    }

    /**
     * Show form to create new instructor
     */
    public function create()
    {
        $coachings = \App\Models\Coaching::orderBy('coaching_name')->get();
        return view('admin.commando_instructors.create', compact('coachings'));
    }

    /**
     * Store new instructor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'coaching_id'   => 'nullable|exists:coachings,id',
            'coaching_name' => 'nullable|string|max:255',
            'name'        => 'required|string|max:255',
            'role_title'  => 'nullable|string|max:255',
            'rank_badge'  => 'nullable|string|max:100',
            'bio'         => 'nullable|string',
            'experience'  => 'nullable|string|max:100',
            'specialties' => 'nullable|string',
            'image'       => 'nullable|string',
            'image_file'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'color_theme' => 'nullable|string|max:50',
            'order_no'    => 'nullable|integer',
            'status'      => 'nullable|boolean',
        ]);

        $coaching = $request->filled('coaching_id') ? \App\Models\Coaching::find($request->coaching_id) : null;
        $validated['coaching_id'] = $coaching ? $coaching->id : 1;
        $validated['coaching_name'] = $coaching ? $coaching->coaching_name : ($request->input('coaching_name') ?: 'The Movement');

        // Handle file upload if present
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $destination = public_path('uploads/instructors');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $validated['image'] = 'uploads/instructors/' . $filename;
        }

        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['order_no'] = $request->filled('order_no') ? (int)$request->order_no : 0;
        $validated['color_theme'] = $request->input('color_theme') ?: 'blue';

        unset($validated['image_file']);

        CommandoInstructor::create($validated);

        return redirect()->route('commando-instructors.index')->with('success', 'Master Instructor added successfully.');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $instructor = CommandoInstructor::findOrFail($id);
        $coachings = \App\Models\Coaching::orderBy('coaching_name')->get();
        return view('admin.commando_instructors.edit', compact('instructor', 'coachings'));
    }

    /**
     * Update instructor
     */
    public function update(Request $request, $id)
    {
        $instructor = CommandoInstructor::findOrFail($id);

        $validated = $request->validate([
            'coaching_id'   => 'nullable|exists:coachings,id',
            'coaching_name' => 'nullable|string|max:255',
            'name'        => 'required|string|max:255',
            'role_title'  => 'nullable|string|max:255',
            'rank_badge'  => 'nullable|string|max:100',
            'bio'         => 'nullable|string',
            'experience'  => 'nullable|string|max:100',
            'specialties' => 'nullable|string',
            'image'       => 'nullable|string',
            'image_file'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'color_theme' => 'nullable|string|max:50',
            'order_no'    => 'nullable|integer',
            'status'      => 'nullable|boolean',
        ]);

        $coaching = $request->filled('coaching_id') ? \App\Models\Coaching::find($request->coaching_id) : null;
        $validated['coaching_id'] = $coaching ? $coaching->id : ($instructor->coaching_id ?: 1);
        $validated['coaching_name'] = $coaching ? $coaching->coaching_name : ($request->input('coaching_name') ?: $instructor->coaching_name);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $destination = public_path('uploads/instructors');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $validated['image'] = 'uploads/instructors/' . $filename;
        }

        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['order_no'] = $request->filled('order_no') ? (int)$request->order_no : 0;
        $validated['color_theme'] = $request->input('color_theme') ?: 'blue';

        unset($validated['image_file']);

        $instructor->update($validated);

        return redirect()->route('commando-instructors.index')->with('success', 'Master Instructor updated successfully.');
    }

    /**
     * Delete instructor
     */
    public function destroy($id)
    {
        $instructor = CommandoInstructor::findOrFail($id);
        $instructor->delete();

        return redirect()->route('commando-instructors.index')->with('success', 'Master Instructor deleted successfully.');
    }

    /**
     * Toggle status
     */
    public function toggleStatus($id)
    {
        $instructor = CommandoInstructor::findOrFail($id);
        $instructor->status = !$instructor->status;
        $instructor->save();

        return redirect()->back()->with('success', 'Instructor visibility updated to ' . ($instructor->status ? 'Active' : 'Inactive') . '.');
    }
}
