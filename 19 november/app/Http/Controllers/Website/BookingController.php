<?php
namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // validation
        $request->validate([
            'library_id' => 'required|exists:libraries,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|digits_between:10,15',
            'date' => 'nullable|date'
        ]);

        // save
        $booking = Booking::create([
            'library_id' => $request->library_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'date' => $request->date ?? now()->toDateString(),
        ]);

        try {
            \App\Models\LibraryView::recordVisit($request->library_id, $request, 'booking_submitted');
        } catch (\Exception $e) {}

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Details submitted successfully',
                'booking' => $booking
            ]);
        }

        return redirect()->back()->with('success', true);
    }
}