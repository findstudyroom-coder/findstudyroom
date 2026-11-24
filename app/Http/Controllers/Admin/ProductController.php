<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{

    // Show product list
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.product.index', compact('products'));
    }

    // Show create form
    public function create()
    {
        return view('admin.product.create');
    }

    // Store product
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'image' => 'required|image'
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {

            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);

        }

        Product::create([
            'title' => $request->title,
            'price' => $request->price,
            'image' => $imageName,
            'description' => $request->description,
            'status' => $request->status
        ]);

        return redirect()->back()->with('success','Product Created Successfully');
    }


    // Edit product
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.product.edit', compact('product'));
    }


    // Update product
    public function update(Request $request, $id)
    {

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {

            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);

            $product->image = $imageName;
        }

        $product->update([
            'title' => $request->title,
            'price' => $request->price,
            'description' => $request->description,
            'status' => $request->status
        ]);

        return redirect()->route('product.index')->with('success','Product Updated');
    }


    // Delete product
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return back()->with('success','Product Deleted');
    }

}