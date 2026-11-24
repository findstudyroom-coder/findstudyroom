<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Request\Auth\UserSignInRequest;
use App\Http\Request\Auth\ForgetPasswordRequest;
use App\Http\Request\Auth\EditRequest;
use App\Http\Request\Auth\EditPasswordRequest;
use App\Mail\AdminForgetPassword;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\ContactUs;
use App\Models\Booking;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Auth;
use DB;
class UserController extends BaseController
{



public function store(Request $request)
{
    $request->validate([
        'name'       => 'required|string|max:255',
        'address'       => 'required|string|max:255',
        'email'    => 'required|string|max:255',
        'phone'      => 'required|string|max:20',
        'product_name' => 'nullable|string',
        'type'       => 'nullable|string|max:50', 
    ]);

    User::create([
        'name'       => $request->name,
        'library_id'       => $request->library_id,
        'email'    => $request->email,
        'address'    => $request->address,
        'phone'      => $request->phone,
        'product_name' => $request->product_name, // from Learn More 
        'type'       => $request->type ?? 'apply_now', 
    ]);

    return back()->with('success', 'Application Submitted Successfully!');
}


    // Admin List
    public function index()
    {
        $applications = User::latest()->get();
        return view('admin.users.index', compact('applications'));
    }

    // Delete
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success','Deleted Successfully');
    }
   
  

 public function Contactstore(Request $request)
    {
        // Validation
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'phone'    => 'required|string|max:20',
            'location' => 'nullable|string|max:255',
            'message'  => 'required|string|max:2000',
        ]);

        // Insert into database
        ContactUs::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'location' => $request->location,
            'message'  => $request->message,
            'type'     => 'contact_form', // identify source
        ]);

        return back()->with('success', 'Your query has been submitted successfully!');
    }


public function contactusindex(){
       $contacts = ContactUs::latest()->paginate(10);
    return view('admin.contact.index', compact('contacts'));
}


public function contactedit($id)
{
    $contact = ContactUs::findOrFail($id);
    return view('admin.contact.edit', compact('contact'));
}
public function contactupdate(Request $request, $id)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'nullable|email|max:255',
        'phone'    => 'required|string|max:20',
        'location' => 'nullable|string|max:255',
    ]);

    $contact = ContactUs::findOrFail($id);

    $contact->update([
        'name'     => $request->name,
        'email'    => $request->email,
        'phone'    => $request->phone,
        'location' => $request->location,
    ]);

    return redirect()->route('admin.contact.index')
        ->with('success', 'Contact updated successfully!');
}




 public function bookingindex()
    {
        $bookings = Booking::with('library')
                    ->latest()
                    ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }


}
