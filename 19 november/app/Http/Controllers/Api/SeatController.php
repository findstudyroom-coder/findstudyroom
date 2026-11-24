<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seat;
use App\Models\StudentMembership;
use App\Models\Student;

class SeatController extends Controller
{
    public function getFloors(Request $request)
    {
        $libraryId = $request->user()->library_id;
        $floors = Seat::where('library_id', $libraryId)
            ->select('floor')
            ->distinct()
            ->get()
            ->pluck('floor');

        if ($floors->isEmpty()) {
            $this->seedDefaultSeats($libraryId);
            $floors = collect(['Floor 1']);
        }

        // Map to format
        $mapped = $floors->map(function ($floor, $index) use ($libraryId) {
            $capacity = Seat::where('library_id', $libraryId)->where('floor', $floor)->count();
            return [
                'id' => 'f' . ($index + 1),
                'label' => $floor,
                'capacity' => $capacity
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $mapped
        ]);
    }

    public function getLayout(Request $request)
    {
        $libraryId = $request->user()->library_id;
        $floorName = $request->query('floor', 'Floor 1'); // Defaults to Floor 1 now

        $seats = Seat::where('library_id', $libraryId)
            ->where('floor', $floorName)
            ->with(['memberships' => function($q) {
                $q->where('status', 'CURRENT')->with(['student', 'shift', 'plan']);
            }])
            ->get();

        if ($seats->isEmpty()) {
            $this->seedDefaultSeats($libraryId);
            $seats = Seat::where('library_id', $libraryId)
                ->where('floor', $floorName)
                ->with(['memberships' => function($q) {
                    $q->where('status', 'CURRENT')->with(['student', 'shift', 'plan']);
                }])
                ->get();
        }

        $allSeats = [];
        $vacantSeats = [];
        $occupiedSeats = [];

        foreach ($seats as $s) {
            $membership = $s->memberships->first();
            $student = $membership ? $membership->student : null;

            $seatData = [
                'id' => $s->seat_number, // Kept for backward compatibility
                'seat_id' => $s->id,
                'seat_number' => $s->seat_number,
                'floor' => $s->floor,
                'row' => $s->row,
                'col' => $s->col,
                'zone' => $s->zone,
                'status' => $s->status,
                'premium' => $s->is_premium ? true : false,
                'student' => $student ? [
                    'id' => 'st_' . $student->id,
                    'name' => $student->name,
                    'phone' => $student->phone,
                ] : null,
                'membership' => $membership ? [
                    'membership_number' => $membership->membership_number,
                    'shift' => $membership->shift ? $membership->shift->name : null,
                    'plan' => $membership->plan ? $membership->plan->name : null,
                    'start_date' => $membership->start_date ? \Carbon\Carbon::parse($membership->start_date)->format('Y-m-d') : null,
                    'end_date' => $membership->end_date ? \Carbon\Carbon::parse($membership->end_date)->format('Y-m-d') : null,
                    'nextDueDate' => $membership->end_date ? \Carbon\Carbon::parse($membership->end_date)->format('Y-m-d') : null,
                ] : null
            ];

            $allSeats[] = $seatData;
            
            if ($s->status === 'available') {
                $vacantSeats[] = $seatData;
            } else {
                $occupiedSeats[] = $seatData;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'all' => $allSeats,
                'vacant' => $vacantSeats,
                'occupied' => $occupiedSeats
            ]
        ]);
    }

    public function allocateSeat(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'seat_number' => 'required|string',
            'shift_key' => 'nullable|string'
        ]);

        $libraryId = $request->user()->library_id;
        $studentId = str_replace('st_', '', $request->student_id);

        $seat = Seat::where('library_id', $libraryId)->where('seat_number', $request->seat_number)->first();
        
        if (!$seat) {
            // Auto-create the seat if it doesn't exist in the database yet
            $seat = Seat::create([
                'library_id' => $libraryId,
                'seat_number' => $request->seat_number,
                'floor' => 'Floor 1',
                'room' => 'Main Hall',
                'row' => 1,
                'col' => 1,
                'zone' => 'Zone A',
                'status' => 'available',
                'is_premium' => false
            ]);
        }
        
        if ($seat->status === 'occupied') {
            return response()->json(['status' => 'error', 'message' => 'Seat already occupied'], 400);
        }

        $student = Student::findOrFail($studentId);
        $membership = $student->activeMembership;

        if (!$membership) {
            return response()->json(['status' => 'error', 'message' => 'Student does not have active membership plan.'], 400);
        }

        // Release old seat if allocated
        if ($membership->seat_id) {
            $oldSeat = Seat::find($membership->seat_id);
            if ($oldSeat) $oldSeat->update(['status' => 'available']);
        }

        // Allocate seat
        $membership->update(['seat_id' => $seat->id]);
        $seat->update(['status' => 'occupied']);

        // Fetch eager loaded data for response
        $membership->load(['shift', 'plan']);

        return response()->json([
            'status' => 'success',
            'message' => "Seat {$seat->seat_number} allocated to student {$student->name}",
            'data' => [
                'seat' => $seat,
                'student' => $student,
                'membership' => $membership,
                'updated_status' => 'occupied'
            ]
        ]);
    }

    public function releaseSeat(Request $request)
    {
        $request->validate([
            'seat_number' => 'required|string'
        ]);

        $libraryId = $request->user()->library_id;
        $seat = Seat::where('library_id', $libraryId)->where('seat_number', $request->seat_number)->firstOrFail();

        $membership = StudentMembership::where('seat_id', $seat->id)->where('status', 'CURRENT')->first();
        $affectedStudentId = null;
        if ($membership) {
            $affectedStudentId = $membership->student_id;
            $membership->update(['seat_id' => null]);
        }

        $seat->update(['status' => 'available']);

        return response()->json([
            'status' => 'success',
            'message' => "Seat {$seat->seat_number} is now vacant.",
            'data' => [
                'seat' => $seat,
                'updated_status' => 'available',
                'affected_student_id' => $affectedStudentId
            ]
        ]);
    }

    private function seedDefaultSeats($libraryId)
    {
        // Only seed if absolutely no seats exist for this library
        if (Seat::where('library_id', $libraryId)->exists()) {
            return;
        }

        $seats = [];
        for ($i = 1; $i <= 30; $i++) {
            $seats[] = [
                'library_id' => $libraryId,
                'seat_number' => (string)$i,
                'floor' => 'Floor 1',
                'room' => 'Main Hall',
                'row' => ceil($i / 5),
                'col' => $i % 5 == 0 ? 5 : $i % 5,
                'zone' => 'Zone A',
                'status' => 'available',
                'is_premium' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Seat::insert($seats);
    }
}
