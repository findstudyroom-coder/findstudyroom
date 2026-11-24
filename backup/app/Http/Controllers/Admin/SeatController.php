<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Library;
use App\Models\Seat;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    // ──────────────────────────────────────────────
    // 1. Show seat map for a library
    // ──────────────────────────────────────────────
    public function index(Request $request, $libraryId)
    {
        $library = Library::findOrFail($libraryId);
        $seats   = Seat::forLibrary($libraryId)
            ->with('activeMembers.plan')
            ->orderBy('seat_number')
            ->get();

        $totalSeats    = $seats->count();
        $occupiedSeats = $seats->where('status', 'occupied')->count();
        $availableSeats = $seats->where('status', 'available')->count();

        return view('admin.seats.index', compact(
            'library', 'seats', 'totalSeats', 'occupiedSeats', 'availableSeats'
        ));
    }

    // ──────────────────────────────────────────────
    // 2. Auto-generate seats for a library
    // ──────────────────────────────────────────────
    public function generate(Request $request, $libraryId)
    {
        $library = Library::findOrFail($libraryId);

        $request->validate([
            'total_seats' => 'required|integer|min:1|max:200',
            'seat_type'   => 'required|in:half_day,full_day,both',
        ]);

        $totalSeats = (int) $request->total_seats;
        $seatType   = $request->seat_type;

        $existing = Seat::forLibrary($libraryId)->pluck('seat_number')->toArray();

        $created = 0;
        for ($i = 1; $i <= $totalSeats; $i++) {
            if (!in_array($i, $existing)) {
                Seat::create([
                    'library_id'  => $libraryId,
                    'seat_number' => $i,
                    'seat_type'   => $seatType,
                    'status'      => 'available',
                ]);
                $created++;
            }
        }

        return redirect()->route('seats.index', $libraryId)
            ->with('success', "{$created} seats generated successfully!");
    }

    // ──────────────────────────────────────────────
    // 3. Delete all seats for a library
    // ──────────────────────────────────────────────
    public function destroyAll($libraryId)
    {
        Library::findOrFail($libraryId); // 404 guard
        Seat::forLibrary($libraryId)->where('status', 'available')->delete();

        return redirect()->route('seats.index', $libraryId)
            ->with('success', 'All available seats removed.');
    }

    // ──────────────────────────────────────────────
    // 4. Update seat type (AJAX-friendly)
    // ──────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $seat = Seat::findOrFail($id);

        $request->validate([
            'seat_type' => 'required|in:half_day,full_day,both',
        ]);

        $seat->update(['seat_type' => $request->seat_type]);

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'seat' => $seat]);
        }

        return redirect()->back()->with('success', 'Seat updated.');
    }

    // ──────────────────────────────────────────────
    // 5. AJAX — available seats for a library
    // ──────────────────────────────────────────────
    public function available(Request $request)
    {
        $libraryId = $request->library_id;
        $shiftType = $request->shift_type ?? 'full_day';

        $query = Seat::forLibrary($libraryId)
            ->where('status', 'available')
            ->orderBy('seat_number');

        if ($shiftType === 'full_day') {
            $query->whereIn('seat_type', ['full_day', 'both']);
        } else {
            // half_day_morning or half_day_evening
            $query->whereIn('seat_type', ['half_day', 'both']);
        }

        $seats = $query->get(['id', 'seat_number', 'seat_type', 'status']);

        return response()->json([
            'status' => 'success',
            'data'   => $seats,
        ]);
    }
}
