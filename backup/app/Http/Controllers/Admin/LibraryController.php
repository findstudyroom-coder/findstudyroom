<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Library;
use App\Models\State;
use App\Models\District;
use Illuminate\Support\Str;
class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $query = Library::with('state','district')->latest();
        
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('is_verified') && $request->is_verified != '') {
            $query->where('is_verified', $request->is_verified);
        }

        $libraries = $query->get();
        return view('admin.libraries.index', compact('libraries'));
    }

    // 📌 2. Show Add Form
    public function create()
    {
        $state = State::get();
        $district = District::get();
        return view('admin.libraries.create',compact('state','district'));
    }

    // 📌 3. Store Data
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'images' => 'required',
            'location' => 'required',
        
            'price' => 'required|numeric',
             'phone' => 'required',
             'pincode' => 'required',
                     'location' => 'required',
        ]);

        // // Upload Image
        // $imageName = time().'.'.$request->image->extension();
        // $request->image->move(public_path('uploads'), $imageName);
        
        
    $library = new Library;

    $library->name = $request->name;

    // Generate slug with state and district
    $slugString = $request->name;
    if ($request->state_id) {
        $state = State::find($request->state_id);
        if ($state) $slugString .= '-' . $state->name;
    }
    if ($request->city_id) {
        $district = District::find($request->city_id);
        if ($district) $slugString .= '-' . $district->name;
    }
    
    $slug = Str::slug($slugString);

    // Ensure unique slug
    $count = Library::where('slug', 'LIKE', "{$slug}%")->count();
    $library->slug = $count ? $slug.'-'.$count : $slug;

    $library->location = $request->location;
   
     $library->price = $request->price;
     $library->rating = $request->rating;
     $library->status = $request->status;
     $library->is_verified = $request->has('is_verified') ? 1 : 0;
     $library->open_time = $request->open_time;
    $library->status = $request->status;
    $library->facilities = $request->facilities;
    $library->descriptions = $request->descriptions;
    $library->pincode = $request->pincode;
    $library->latitude = $request->latitude;   
    $library->longitude = $request->longitude;
     $library->phone = $request->phone;
     $library->whatsapp_number = $request->whatsapp_number;
     $library->state_id = $request->state_id;
      $library->city_id = $request->city_id;
  

    $images = [];

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $path = $file->store('library_images', 'public');
            $images[] = $path;
        }
    }

    $library->images = $images;

    $gallery = [];
    if ($request->hasFile('image_gallery')) {
        foreach ($request->file('image_gallery') as $file) {
            $path = $file->store('library_gallery', 'public');
            $gallery[] = $path;
        }
    }
    $library->image_gallery = $gallery;
    $library->save();

          return redirect()->route('libraries.index')->with('success', 'Add Successfully');
    }

    // 📌 4. Edit Form
    public function edit($id)
    {
        $library = Library::findOrFail($id);
         $state = State::get();
        $district = District::get();
        return view('admin.libraries.edit', compact('library','state','district'));
    }

    // 📌 5. Update Data
   public function update(Request $request, $id)
{
    $library = Library::findOrFail($id);

    // Get existing images
    $existingImages = $library->images ?? [];

    // Remove selected images
    if ($request->has('remove_images')) {
        foreach ($request->remove_images as $removeImg) {

            // delete from storage
            if (\Storage::disk('public')->exists($removeImg)) {
                \Storage::disk('public')->delete($removeImg);
            }

            // remove from array
            $existingImages = array_filter($existingImages, function ($img) use ($removeImg) {
                return $img != $removeImg;
            });
        }
    }

    // Add new images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $path = $file->store('library_images', 'public');
            $existingImages[] = $path;
        }
    }

    // Re-index array (important)
    $existingImages = array_values($existingImages);

    // Gallery images
    $existingGallery = is_array($library->image_gallery) ? $library->image_gallery : json_decode($library->image_gallery, true) ?? [];

    if ($request->has('remove_gallery_images')) {
        foreach ($request->remove_gallery_images as $removeImg) {
            if (\Storage::disk('public')->exists($removeImg)) {
                \Storage::disk('public')->delete($removeImg);
            }
            $existingGallery = array_filter($existingGallery, function ($img) use ($removeImg) {
                return $img != $removeImg;
            });
        }
    }

    if ($request->hasFile('image_gallery')) {
        foreach ($request->file('image_gallery') as $file) {
            $path = $file->store('library_gallery', 'public');
            $existingGallery[] = $path;
        }
    }

    $existingGallery = array_values($existingGallery);

    $slugString = $request->name;
    if ($request->state_id) {
        $state = State::find($request->state_id);
        if ($state) $slugString .= '-' . $state->name;
    }
    if ($request->city_id) {
        $district = District::find($request->city_id);
        if ($district) $slugString .= '-' . $district->name;
    }
    
    $slug = Str::slug($slugString);
    // Check uniqueness excluding current library
    $count = Library::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $id)->count();
    $newSlug = $count ? $slug.'-'.$count : $slug;

    // Update data
    $library->update([
        'name' => $request->name,
        'slug' => $newSlug,
        'location' => $request->location,
        
        'price' => $request->price,
        'rating' => $request->rating,
        'status' => $request->status,
        'is_verified' => $request->has('is_verified') ? 1 : 0,
        'open_time' => $request->open_time,
        'close_time' => $request->close_time,
        'status' => $request->status,
        'facilities' => $request->facilities,
        'descriptions' => $request->descriptions,
        'pincode' => $request->pincode,
        'phone' => $request->phone,
        'whatsapp_number' => $request->whatsapp_number,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
           
        'images' => $existingImages,
        'image_gallery' => $existingGallery,

         'state_id' => $request->state_id,
        'city_id' => $request->city_id,
        
  
    ]);

    return redirect()->route('libraries.index')->with('success', 'Updated Successfully');
}
    // 📌 6. Delete
    public function destroy($id)
    {
        $library = Library::findOrFail($id);
        $library->delete();

        return redirect()->back()->with('success', 'Deleted Successfully');
    }
}