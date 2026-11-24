<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->get();
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'content' => 'required|string',
            'author_name' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'status' => 'required|string|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $slug = Str::slug($request->title);
        $slug = $this->createSlug($slug);

        $featuredImage = null;
        $uploadPath = public_path('uploads/blogs');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $featuredImage = time() . '_' . $image->getClientOriginalName();
            $image->move($uploadPath, $featuredImage);
        }

        Blog::create([
            'title' => $request->title,
            'slug' => $slug,
            'short_description' => $request->short_description,
            'content' => $request->content,
            'featured_image' => $featuredImage,
            'author_name' => $request->author_name,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'status' => $request->status,
            'published_at' => $request->filled('published_at') ? $request->published_at : ($request->status === 'published' ? now() : null),
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog added successfully.');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'content' => 'required|string',
            'author_name' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'status' => 'required|string|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $slug = Str::slug($request->title);
        if ($slug !== $blog->slug) {
            $slug = $this->createSlug($slug, $blog->id);
        }

        $uploadPath = public_path('uploads/blogs');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image && file_exists(public_path('uploads/blogs/' . $blog->featured_image))) {
                unlink(public_path('uploads/blogs/' . $blog->featured_image));
            }
            $image = $request->file('featured_image');
            $featuredImage = time() . '_' . $image->getClientOriginalName();
            $image->move($uploadPath, $featuredImage);
        } else {
            $featuredImage = $blog->featured_image;
        }

        $blog->update([
            'title' => $request->title,
            'slug' => $slug,
            'short_description' => $request->short_description,
            'content' => $request->content,
            'featured_image' => $featuredImage,
            'author_name' => $request->author_name,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'status' => $request->status,
            'published_at' => $request->filled('published_at') ? $request->published_at : ($request->status === 'published' && !$blog->published_at ? now() : $blog->published_at),
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->featured_image && file_exists(public_path('uploads/blogs/' . $blog->featured_image))) {
            unlink(public_path('uploads/blogs/' . $blog->featured_image));
        }

        $blog->delete();
        return redirect()->back()->with('success', 'Blog deleted successfully.');
    }

    private function createSlug($slug, $ignoreId = null)
    {
        $originalSlug = $slug;
        $counter = 1;

        while (Blog::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }
}
