<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    // ──────────────────────────────────────────────
    // GET /api/members  (auth required)
    // ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Member::with('library', 'seat', 'plan')
            ->when($request->library_id, fn($q) => $q->where('library_id', $request->library_id))
            ->when($request->status,     fn($q) => $q->where('status', $request->status))
            ->latest();

        $members = $query->paginate(20);

        return response()->json([
            'status' => 'success',
            'data'   => $members,
        ]);
    }

    // ──────────────────────────────────────────────
    // GET /api/members/{id}  (auth required)
    // ──────────────────────────────────────────────
    public function show($id)
    {
        $member = Member::with('library', 'seat', 'plan')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $member,
        ]);
    }

    // ──────────────────────────────────────────────
    // POST /api/members  (auth required)
    // ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'library_id' => 'required|exists:libraries,id',
            'name'       => 'required|string|max:255',
            'mobile'     => 'required|string|max:15',
            'location'   => 'nullable|string|max:255',
            'photo'      => 'nullable|image|max:2048',
            'document'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'seat_id'    => 'nullable|exists:seats,id',
            'plan_id'    => 'nullable|exists:plans,id',
            'shift_type' => 'required|in:half_day_morning,half_day_evening,full_day',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'nullable|in:active,inactive,expired',
            'notes'      => 'nullable|string',
        ]);

        $photoPath    = $request->hasFile('photo')
            ? $request->file('photo')->store('members/photos', 'public') : null;
        $documentPath = $request->hasFile('document')
            ? $request->file('document')->store('members/documents', 'public') : null;

        $member = Member::create([
            ...$request->only(['library_id','name','mobile','location','seat_id','plan_id','shift_type','start_date','end_date','notes']),
            'photo'    => $photoPath,
            'document' => $documentPath,
            'status'   => $request->status ?? 'active',
        ]);

        if ($member->seat_id) {
            Seat::find($member->seat_id)?->markOccupied();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Member added.',
            'data'    => $member->load('library', 'seat', 'plan'),
        ], 201);
    }

    // ──────────────────────────────────────────────
    // PUT /api/members/{id}  (auth required)
    // ──────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'name'       => 'sometimes|required|string|max:255',
            'mobile'     => 'sometimes|required|string|max:15',
            'status'     => 'nullable|in:active,inactive,expired',
            'shift_type' => 'nullable|in:half_day_morning,half_day_evening,full_day',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
            'seat_id'    => 'nullable|exists:seats,id',
        ]);

        // Handle seat change
        $oldSeatId = $member->seat_id;
        $newSeatId = $request->has('seat_id') ? ($request->seat_id ?: null) : $oldSeatId;

        if ($oldSeatId && $oldSeatId !== $newSeatId) {
            Seat::find($oldSeatId)?->markAvailable();
        }

        $member->update($request->only([
            'name','mobile','location','seat_id','plan_id','shift_type',
            'start_date','end_date','status','notes',
        ]));

        if ($newSeatId && $newSeatId !== $oldSeatId) {
            Seat::find($newSeatId)?->markOccupied();
        }

        return response()->json([
            'status' => 'success',
            'data'   => $member->fresh()->load('library', 'seat', 'plan'),
        ]);
    }

    // ──────────────────────────────────────────────
    // DELETE /api/members/{id}  (auth required)
    // ──────────────────────────────────────────────
    public function destroy($id)
    {
        $member = Member::findOrFail($id);

        if ($member->seat_id) {
            Seat::find($member->seat_id)?->markAvailable();
        }

        if ($member->photo)    Storage::disk('public')->delete($member->photo);
        if ($member->document) Storage::disk('public')->delete($member->document);

        $member->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Member deleted.',
        ]);
    }

    // ──────────────────────────────────────────────
    // GET /api/seats/{library_id}/available  (public)
    // ──────────────────────────────────────────────
    public function availableSeats(Request $request, $libraryId)
    {
        $shiftType = $request->shift_type ?? 'full_day';

        $query = Seat::forLibrary($libraryId)
            ->where('status', 'available')
            ->orderBy('seat_number');

        if ($shiftType === 'full_day') {
            $query->whereIn('seat_type', ['full_day', 'both']);
        } else {
            $query->whereIn('seat_type', ['half_day', 'both']);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $query->get(['id', 'seat_number', 'seat_type', 'status']),
        ]);
    }

    // ──────────────────────────────────────────────
    // GET /api/seats/{library_id}  (public)
    // ──────────────────────────────────────────────
    public function seatMap($libraryId)
    {
        $seats = Seat::forLibrary($libraryId)
            ->orderBy('seat_number')
            ->get(['id', 'seat_number', 'seat_type', 'status']);

        $summary = [
            'total'     => $seats->count(),
            'available' => $seats->where('status', 'available')->count(),
            'occupied'  => $seats->where('status', 'occupied')->count(),
        ];

        return response()->json([
            'status'  => 'success',
            'summary' => $summary,
            'data'    => $seats,
        ]);
    }
}
