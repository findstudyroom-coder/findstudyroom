<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Library;
use App\Models\Member;
use App\Models\Plan;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    // ──────────────────────────────────────────────
    // 1. LIST all members
    // ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Member::with('library', 'seat', 'plan')->latest();

        if ($request->filled('library_id')) {
            $query->where('library_id', $request->library_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('mobile', 'like', "%{$s}%")
                  ->orWhere('location', 'like', "%{$s}%");
            });
        }

        $members   = $query->paginate(20)->withQueryString();
        $libraries = Library::orderBy('name')->get();

        return view('admin.members.index', compact('members', 'libraries'));
    }

    // ──────────────────────────────────────────────
    // 2. SHOW create form
    // ──────────────────────────────────────────────
    public function create()
    {
        $libraries = Library::orderBy('name')->get();
        $plans     = Plan::all();
        $shifts    = Member::SHIFT_LABELS;

        return view('admin.members.create', compact('libraries', 'plans', 'shifts'));
    }

    // ──────────────────────────────────────────────
    // 3. STORE new member
    // ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'library_id'  => 'required|exists:libraries,id',
            'name'        => 'required|string|max:255',
            'mobile'      => 'required|string|max:15',
            'location'    => 'nullable|string|max:255',
            'photo'       => 'nullable|image|max:2048',
            'document'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'seat_id'     => 'nullable|exists:seats,id',
            'plan_id'     => 'nullable|exists:plans,id',
            'shift_type'  => 'required|in:half_day_morning,half_day_evening,full_day',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'status'      => 'nullable|in:active,inactive,expired',
            'notes'       => 'nullable|string|max:1000',
        ]);

        // Handle uploads
        $photoPath    = $request->hasFile('photo')
            ? $request->file('photo')->store('members/photos', 'public')
            : null;

        $documentPath = $request->hasFile('document')
            ? $request->file('document')->store('members/documents', 'public')
            : null;

        // Create member
        $member = Member::create([
            'library_id'  => $request->library_id,
            'name'        => $request->name,
            'mobile'      => $request->mobile,
            'location'    => $request->location,
            'photo'       => $photoPath,
            'document'    => $documentPath,
            'seat_id'     => $request->seat_id ?: null,
            'plan_id'     => $request->plan_id ?: null,
            'shift_type'  => $request->shift_type,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'status'      => $request->status ?? 'active',
            'notes'       => $request->notes,
        ]);

        // Mark seat as occupied
        if ($member->seat_id) {
            Seat::find($member->seat_id)?->markOccupied();
        }

        return redirect()->route('members.index')
            ->with('success', 'Member added successfully!');
    }

    // ──────────────────────────────────────────────
    // 4. SHOW single member card
    // ──────────────────────────────────────────────
    public function show($id)
    {
        $member = Member::with('library', 'seat', 'plan')->findOrFail($id);

        return view('admin.members.show', compact('member'));
    }

    // ──────────────────────────────────────────────
    // 5. EDIT form
    // ──────────────────────────────────────────────
    public function edit($id)
    {
        $member    = Member::findOrFail($id);
        $libraries = Library::orderBy('name')->get();
        $plans     = Plan::all();
        $shifts    = Member::SHIFT_LABELS;

        // Load seats for current library
        $seats = Seat::forLibrary($member->library_id)
            ->where(function ($q) use ($member) {
                $q->where('status', 'available')
                  ->orWhere('id', $member->seat_id);
            })
            ->orderBy('seat_number')
            ->get();

        return view('admin.members.edit', compact('member', 'libraries', 'plans', 'shifts', 'seats'));
    }

    // ──────────────────────────────────────────────
    // 6. UPDATE member
    // ──────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'library_id'  => 'required|exists:libraries,id',
            'name'        => 'required|string|max:255',
            'mobile'      => 'required|string|max:15',
            'location'    => 'nullable|string|max:255',
            'photo'       => 'nullable|image|max:2048',
            'document'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'seat_id'     => 'nullable|exists:seats,id',
            'plan_id'     => 'nullable|exists:plans,id',
            'shift_type'  => 'required|in:half_day_morning,half_day_evening,full_day',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'status'      => 'nullable|in:active,inactive,expired',
            'notes'       => 'nullable|string|max:1000',
        ]);

        // Handle seat change — free old seat
        $oldSeatId = $member->seat_id;
        $newSeatId = $request->seat_id ?: null;

        if ($oldSeatId && $oldSeatId !== $newSeatId) {
            Seat::find($oldSeatId)?->markAvailable();
        }

        // Handle photo upload
        $photoPath = $member->photo;
        if ($request->hasFile('photo')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('members/photos', 'public');
        }

        // Handle document upload
        $documentPath = $member->document;
        if ($request->hasFile('document')) {
            if ($documentPath) {
                Storage::disk('public')->delete($documentPath);
            }
            $documentPath = $request->file('document')->store('members/documents', 'public');
        }

        $member->update([
            'library_id'  => $request->library_id,
            'name'        => $request->name,
            'mobile'      => $request->mobile,
            'location'    => $request->location,
            'photo'       => $photoPath,
            'document'    => $documentPath,
            'seat_id'     => $newSeatId,
            'plan_id'     => $request->plan_id ?: null,
            'shift_type'  => $request->shift_type,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'status'      => $request->status ?? 'active',
            'notes'       => $request->notes,
        ]);

        // Mark new seat as occupied
        if ($newSeatId) {
            Seat::find($newSeatId)?->markOccupied();
        }

        return redirect()->route('members.index')
            ->with('success', 'Member updated successfully!');
    }

    // ──────────────────────────────────────────────
    // 7. DELETE member
    // ──────────────────────────────────────────────
    public function destroy($id)
    {
        $member = Member::findOrFail($id);

        // Release seat
        if ($member->seat_id) {
            Seat::find($member->seat_id)?->markAvailable();
        }

        // Delete uploaded files
        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }
        if ($member->document) {
            Storage::disk('public')->delete($member->document);
        }

        $member->delete();

        return redirect()->back()->with('success', 'Member deleted.');
    }

    // ──────────────────────────────────────────────
    // 8. AJAX — get available seats for a library
    // ──────────────────────────────────────────────
    public function getSeats(Request $request)
    {
        $libraryId = $request->library_id;
        $shiftType = $request->shift_type;

        $query = Seat::forLibrary($libraryId)
            ->where('status', 'available')
            ->orderBy('seat_number');

        if ($shiftType === 'full_day') {
            $query->whereIn('seat_type', ['full_day', 'both']);
        } elseif (in_array($shiftType, ['half_day_morning', 'half_day_evening'])) {
            $query->whereIn('seat_type', ['half_day', 'both']);
        }

        $seats = $query->get(['id', 'seat_number', 'seat_type', 'status']);

        return response()->json(['status' => 'success', 'data' => $seats]);
    }
}
