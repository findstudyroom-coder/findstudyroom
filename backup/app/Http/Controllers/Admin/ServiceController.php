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

use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Auth;
use DB;
class ServiceController extends BaseController
{

    public function create()
    {
     
         return view('admin.services.add');
    }
   
  
public function store(Request $request)
{
    // ✅ Validation
    $request->validate([
        'title'        => 'required|string|max:255',
        'price'        => 'nullable|string|max:255',
        'price_label'  => 'nullable|string|max:255',
        'features'     => 'nullable|array',
        'features.*'   => 'nullable|string|max:255',
        'button_text'  => 'nullable|string|max:255',
        'status'       => 'required|in:0,1',
        'image' => 'required|image'
    ]);

    // ✅ Remove empty feature inputs
    $features = array_filter($request->features ?? []);
      $imageName = null;

        if ($request->hasFile('image')) {

            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/services'), $imageName);

        }

    // ✅ Insert into database
    Service::create([
        'title'       => $request->title,
        'price'       => $request->price,
        'price_label' => $request->price_label,
        'features'    => $features, // auto JSON convert
        'button_text' => $request->button_text ?? 'Learn More',
        'status'      => $request->status,
        'image' => $imageName,
    ]);

    return redirect()->back()->with('success', 'Service Added Successfully!');
}


  public function index()
{
    $services = Service::latest()->get();
    return view('admin.services.index', compact('services'));
}

   public function edit($id)
{
    $service = Service::findOrFail($id);
    return view('admin.services.edit', compact('service'));
}

public function update(Request $request, $id)
{
    try {

        $request->validate([
            'title'        => 'required|string|max:255',
            'price'        => 'nullable|string|max:255',
            'features'     => 'nullable|array',
            'features.*'   => 'nullable|string|max:255',
            'button_text'  => 'nullable|string|max:255',
            'status'       => 'required|in:0,1',
            'image'        => 'nullable|image'
        ]);

        $service = Service::findOrFail($id);

        $imageName = $service->image; // keep old image

        if ($request->hasFile('image')) {

            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/services'), $imageName);
        }

        $features = array_filter($request->features ?? []);

        $service->update([
            'title'       => $request->title,
            'price'       => $request->price,
            'price_label' => $request->price_label,
            'features'    => $features,
            'button_text' => $request->button_text ?? 'Learn More',
            'status'      => $request->status,
            'image'       => $imageName,
        ]);

        return redirect()->route('service.index')
                         ->with('success', 'Service Updated Successfully!');

    } catch (\Exception $e) {

        return redirect()->back()
                         ->with('error', 'Something went wrong: '.$e->getMessage())
                         ->withInput();
    }
}












}
