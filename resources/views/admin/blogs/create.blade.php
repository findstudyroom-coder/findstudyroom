@extends('admin.master')

@section('styles')
<!-- Summernote Lite CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame {
        border: 1px solid #334155 !important;
        border-radius: 12px !important;
        overflow: hidden;
        background: #ffffff !important;
    }
    .note-toolbar {
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 8px !important;
    }
    .note-btn {
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        color: #1e293b !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        padding: 5px 9px !important;
    }
    .note-btn:hover {
        background: #f1f5f9 !important;
        color: #0f172a !important;
    }
    .note-editable {
        background: #ffffff !important;
        color: #0f172a !important;
        min-height: 400px !important;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif !important;
        font-size: 16px !important;
        line-height: 1.8 !important;
        padding: 24px !important;
    }
    .note-editable p {
        margin-bottom: 1rem !important;
    }
    .note-editable ul {
        list-style-type: disc !important;
        padding-left: 2rem !important;
        margin-bottom: 1rem !important;
    }
    .note-editable ol {
        list-style-type: decimal !important;
        padding-left: 2rem !important;
        margin-bottom: 1rem !important;
    }
    .note-editable h1, .note-editable h2, .note-editable h3 {
        margin-top: 1.5rem !important;
        margin-bottom: 0.75rem !important;
        font-weight: 700 !important;
    }
    .note-statusbar {
        background: #f8fafc !important;
        border-top: 1px solid #e2e8f0 !important;
    }
    .note-modal-content {
        background: #1e293b !important;
        color: #ffffff !important;
        border-radius: 12px !important;
    }
    .note-modal-header {
        border-bottom: 1px solid #334155 !important;
    }
    .note-modal-footer {
        border-top: 1px solid #334155 !important;
    }
    .note-input {
        background: #0f172a !important;
        border: 1px solid #334155 !important;
        color: #ffffff !important;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white">+ Create Blog</h2>
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">Back to list</a>
</div>

<div class="card border-0 mt-3">
    <div class="card-body bg-dark p-4 rounded-4">
        <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
            @csrf

            <div class="mb-3">
                <label class="form-label text-white fw-bold">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="Enter blog title" required>
                @error('title')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label text-white fw-bold">Short Description <span class="text-danger">*</span></label>
                <textarea name="short_description" class="form-control" rows="3" placeholder="Brief 1-2 sentence overview for cards and meta snippets" required>{{ old('short_description') }}</textarea>
                @error('short_description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label text-white fw-bold d-flex justify-content-between">
                    <span>Full Article Content <span class="text-danger">*</span></span>
                    <span class="text-info small"><i class="fas fa-magic me-1"></i> Rich Visual Editor</span>
                </label>
                <textarea id="content" name="content" class="form-control d-none">{{ old('content') }}</textarea>
                @error('content')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            <div class="row g-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-white">Author Name</label>
                    <input type="text" name="author_name" value="{{ old('author_name', 'Findstudyroom Team') }}" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-white">Featured Image</label>
                    <input type="file" name="featured_image" class="form-control" accept="image/*">
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-white">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-white">Published At</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Meta Title (SEO)</label>
                <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="form-control" placeholder="Optional custom SEO title">
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Meta Description (SEO)</label>
                <textarea name="meta_description" class="form-control" rows="2" placeholder="Optional custom SEO description">{{ old('meta_description') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Meta Keywords (SEO)</label>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" class="form-control" placeholder="e.g. study library, quiet study rooms, jaipur libraries">
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-4 shadow">
                    <i class="fas fa-save me-1"></i> Save Blog
                </button>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary btn-lg ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<!-- Summernote Lite JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#content').summernote({
            placeholder: 'Write your full blog article here... Use headings (H1, H2, H3), bullet points, numbered lists, blockquotes, tables, and images.',
            tabsize: 2,
            height: 420,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            styleTags: [
                'p',
                { title: 'Heading 1', tag: 'h1', className: '', value: 'h1' },
                { title: 'Heading 2', tag: 'h2', className: '', value: 'h2' },
                { title: 'Heading 3', tag: 'h3', className: '', value: 'h3' },
                { title: 'Heading 4', tag: 'h4', className: '', value: 'h4' },
                { title: 'Blockquote', tag: 'blockquote', className: 'blockquote', value: 'blockquote' },
                { title: 'Code block', tag: 'pre', className: '', value: 'pre' }
            ]
        });

        // Ensure Summernote content syncs into textarea before form submit
        $('#blogForm').on('submit', function() {
            var contentHtml = $('#content').summernote('code');
            $('#content').val(contentHtml);
        });
    });
</script>
@endsection
