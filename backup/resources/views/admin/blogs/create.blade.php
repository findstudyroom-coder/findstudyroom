@extends('admin.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white">+ Create Blog</h2>
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">Back to list</a>
</div>

<div class="card border-0 mt-3">
    <div class="card-body bg-dark p-4 rounded-4">
        <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

                <div class="mb-3">
                <label class="form-label text-white">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                @error('title')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Short Description</label>
                <textarea name="short_description" class="form-control" rows="3" required>{{ old('short_description') }}</textarea>
                @error('short_description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Content</label>
                <textarea id="content" name="content" class="form-control" rows="8" required>{{ old('content') }}</textarea>
                @error('content')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="row g-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-white">Author Name</label>
                    <input type="text" name="author_name" value="{{ old('author_name') }}" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-white">Featured Image</label>
                    <input type="file" name="featured_image" class="form-control">
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-white">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-white">Published At</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Meta Description</label>
                <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Meta Keywords</label>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Save Blog</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content');
</script>
@endsection
