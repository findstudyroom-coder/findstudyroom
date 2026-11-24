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
            'date' => 'required|date'
        ]);

        // save
        Booking::create([
            'library_id' => $request->library_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'date' => $request->date,
        ]);
return redirect()->back()->with('success', true);
        //return back()->with('success', 'Booking submitted successfully!');
    }
}