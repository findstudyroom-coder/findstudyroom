@extends('master')

@section('title', $blog->meta_title ?: $blog->title)
@section('meta_description', $blog->meta_description ?: \Illuminate\Support\Str::limit($blog->short_description, 155))

@section('content')
<style>
  /* ========================================================
     BULLETPROOF BLOG CONTENT TYPOGRAPHY & FORMATTING
     Overrides Tailwind CSS Preflight resets completely
     Works for CKEditor, Summernote, and custom HTML
     ======================================================== */
  
  .blog-content,
  .prose {
    font-size: 1.1rem !important;
    line-height: 1.85 !important;
    color: #334155 !important;
    word-break: break-word !important;
  }

  /* HEADINGS - Force visible sizes and bold styling */
  .blog-content h1, .prose h1,
  .blog-content h2, .prose h2,
  .blog-content h3, .prose h3,
  .blog-content h4, .prose h4,
  .blog-content h5, .prose h5,
  .blog-content h6, .prose h6 {
    color: #0f172a !important;
    font-family: 'Space Grotesk', system-ui, -apple-system, sans-serif !important;
    font-weight: 700 !important;
    line-height: 1.35 !important;
    display: block !important;
    clear: both !important;
  }

  .blog-content h1, .prose h1 {
    font-size: 2.2rem !important;
    font-weight: 800 !important;
    margin-top: 2.5rem !important;
    margin-bottom: 1.25rem !important;
    border-bottom: 2px solid #e2e8f0 !important;
    padding-bottom: 0.5rem !important;
  }

  .blog-content h2, .prose h2 {
    font-size: 1.65rem !important;
    font-weight: 700 !important;
    margin-top: 2.25rem !important;
    margin-bottom: 1rem !important;
    border-bottom: 1px solid #f1f5f9 !important;
    padding-bottom: 0.4rem !important;
  }

  .blog-content h3, .prose h3 {
    font-size: 1.35rem !important;
    font-weight: 700 !important;
    margin-top: 1.75rem !important;
    margin-bottom: 0.75rem !important;
    color: #1e293b !important;
  }

  .blog-content h4, .prose h4 {
    font-size: 1.15rem !important;
    font-weight: 600 !important;
    margin-top: 1.35rem !important;
    margin-bottom: 0.5rem !important;
    color: #1e293b !important;
  }

  .blog-content h5, .prose h5,
  .blog-content h6, .prose h6 {
    font-size: 1.05rem !important;
    font-weight: 600 !important;
    margin-top: 1.15rem !important;
    margin-bottom: 0.5rem !important;
    color: #334155 !important;
  }

  /* Hide empty headings produced by editor enters */
  .blog-content h1:empty, .prose h1:empty,
  .blog-content h2:empty, .prose h2:empty,
  .blog-content h3:empty, .prose h3:empty {
    display: none !important;
  }

  /* PARAGRAPHS */
  .blog-content p, .prose p {
    margin-top: 0 !important;
    margin-bottom: 1.25rem !important;
    line-height: 1.85 !important;
    color: #334155 !important;
    font-size: 1.05rem !important;
  }

  /* UNORDERED (BULLET) LISTS */
  .blog-content ul, .prose ul {
    list-style: disc !important;
    list-style-type: disc !important;
    list-style-position: outside !important;
    padding-left: 2rem !important;
    margin-top: 0.75rem !important;
    margin-bottom: 1.5rem !important;
    display: block !important;
  }

  /* ORDERED (NUMBERED) LISTS */
  .blog-content ol, .prose ol {
    list-style: decimal !important;
    list-style-type: decimal !important;
    list-style-position: outside !important;
    padding-left: 2rem !important;
    margin-top: 0.75rem !important;
    margin-bottom: 1.5rem !important;
    display: block !important;
  }

  /* LIST ITEMS */
  .blog-content ul li, .prose ul li {
    list-style: disc !important;
    list-style-type: disc !important;
    display: list-item !important;
    margin-bottom: 0.5rem !important;
    line-height: 1.75 !important;
    color: #334155 !important;
    padding-left: 0.35rem !important;
  }

  .blog-content ol li, .prose ol li {
    list-style: decimal !important;
    list-style-type: decimal !important;
    display: list-item !important;
    margin-bottom: 0.5rem !important;
    line-height: 1.75 !important;
    color: #334155 !important;
    padding-left: 0.35rem !important;
  }

  /* CRITICAL: CKEditor wraps list items in <p> tags.
     Make <p> inside <li> inline so text sits right next to the bullet! */
  .blog-content li p, .prose li p,
  .blog-content li > p, .prose li > p {
    display: inline !important;
    margin: 0 !important;
    padding: 0 !important;
    line-height: inherit !important;
    font-size: inherit !important;
    color: inherit !important;
  }

  /* NESTED LISTS */
  .blog-content ul ul, .prose ul ul {
    list-style: circle !important;
    list-style-type: circle !important;
    margin-top: 0.4rem !important;
    margin-bottom: 0.4rem !important;
    padding-left: 1.75rem !important;
  }

  .blog-content ul ul li, .prose ul ul li {
    list-style: circle !important;
    list-style-type: circle !important;
  }

  .blog-content ol ol, .prose ol ol {
    list-style: lower-alpha !important;
    list-style-type: lower-alpha !important;
    margin-top: 0.4rem !important;
    margin-bottom: 0.4rem !important;
    padding-left: 1.75rem !important;
  }

  .blog-content ol ol li, .prose ol ol li {
    list-style: lower-alpha !important;
    list-style-type: lower-alpha !important;
  }

  /* INLINE TEXT STYLES */
  .blog-content strong, .prose strong,
  .blog-content b, .prose b {
    font-weight: 700 !important;
    color: #0f172a !important;
  }

  .blog-content em, .prose em,
  .blog-content i, .prose i {
    font-style: italic !important;
  }

  .blog-content u, .prose u {
    text-decoration: underline !important;
    text-underline-offset: 3px !important;
  }

  .blog-content s, .prose s,
  .blog-content strike, .prose strike,
  .blog-content del, .prose del {
    text-decoration: line-through !important;
  }

  .blog-content mark, .prose mark {
    background-color: #fef08a !important;
    color: #854d0e !important;
    padding: 0.1rem 0.35rem !important;
    border-radius: 0.25rem !important;
  }

  /* HYPERLINKS */
  .blog-content a, .prose a {
    color: #0d9488 !important;
    text-decoration: underline !important;
    text-underline-offset: 3px !important;
    font-weight: 600 !important;
    transition: color 0.15s ease !important;
  }

  .blog-content a:hover, .prose a:hover {
    color: #0f766e !important;
  }

  /* BLOCKQUOTES */
  .blog-content blockquote, .prose blockquote {
    border-left: 4px solid #0d9488 !important;
    background: #f0fdfa !important;
    padding: 1.15rem 1.5rem !important;
    margin: 1.75rem 0 !important;
    border-radius: 0 0.85rem 0.85rem 0 !important;
    font-style: italic !important;
    color: #134e4a !important;
    font-size: 1.05rem !important;
    line-height: 1.75 !important;
  }

  .blog-content blockquote p, .prose blockquote p {
    margin-bottom: 0 !important;
    color: #134e4a !important;
  }

  /* TABLES */
  .blog-content table, .prose table {
    width: 100% !important;
    border-collapse: collapse !important;
    margin: 2rem 0 !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 0.75rem !important;
    overflow: hidden !important;
    font-size: 0.95rem !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04) !important;
  }

  .blog-content table th, .prose table th {
    background: #f1f5f9 !important;
    color: #0f172a !important;
    font-weight: 700 !important;
    padding: 0.85rem 1.1rem !important;
    border: 1px solid #cbd5e1 !important;
    text-align: left !important;
  }

  .blog-content table td, .prose table td {
    padding: 0.85rem 1.1rem !important;
    border: 1px solid #e2e8f0 !important;
    color: #334155 !important;
    vertical-align: top !important;
  }

  .blog-content table tr:nth-child(even) td, .prose table tr:nth-child(even) td {
    background: #f8fafc !important;
  }

  /* IMAGES */
  .blog-content img, .prose img {
    max-width: 100% !important;
    height: auto !important;
    border-radius: 0.85rem !important;
    margin: 2rem auto !important;
    display: block !important;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08) !important;
  }

  /* HORIZONTAL RULES */
  .blog-content hr, .prose hr {
    border: none !important;
    border-top: 1px solid #e2e8f0 !important;
    margin: 2.5rem 0 !important;
  }

  /* CODE & PRE BLOCKS */
  .blog-content code, .prose code {
    background: #f1f5f9 !important;
    color: #0f172a !important;
    padding: 0.2rem 0.45rem !important;
    border-radius: 0.35rem !important;
    font-size: 0.9em !important;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
    border: 1px solid #e2e8f0 !important;
  }

  .blog-content pre, .prose pre {
    background: #0f172a !important;
    color: #f8fafc !important;
    padding: 1.25rem 1.5rem !important;
    border-radius: 0.85rem !important;
    overflow-x: auto !important;
    margin: 1.75rem 0 !important;
    font-size: 0.95rem !important;
    line-height: 1.6 !important;
  }

  .blog-content pre code, .prose pre code {
    background: transparent !important;
    color: inherit !important;
    padding: 0 !important;
    border: none !important;
  }
</style>

<section class="bg-[radial-gradient(circle_at_top,_var(--tw-gradient-stops))] from-teal-100 via-white to-white py-16 px-6">
  <div class="max-w-4xl mx-auto">
    <div class="mb-10 rounded-[2rem] border border-slate-200 bg-white p-8 sm:p-10 shadow-lg">
      <div class="flex flex-col gap-6">
        <div>
          <span class="inline-flex items-center gap-1.5 rounded-full bg-teal-50 px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-teal-700">
            <i class="fas fa-feather-alt text-teal-600"></i> Blog Post
          </span>
          <h1 class="mt-4 text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">{{ $blog->title }}</h1>
          <p class="mt-4 text-lg leading-relaxed text-slate-600 font-medium">{{ $blog->short_description }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-4 pt-4 border-t border-slate-100 text-sm text-slate-600">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-xs">
              {{ strtoupper(substr($blog->author_name ?? 'A', 0, 1)) }}
            </div>
            <span><strong>Author:</strong> {{ $blog->author_name ?? 'Findstudyroom Team' }}</span>
          </div>
          <span class="text-slate-300">•</span>
          <div>
            <strong>Published:</strong> {{ optional($blog->published_at)->format('d M, Y') ?? 'Recently' }}
          </div>
        </div>
      </div>
    </div>

    <article class="rounded-[2rem] border border-slate-200 bg-white shadow-sm overflow-hidden">
      @if($blog->featured_image)
      <div class="max-h-[480px] overflow-hidden bg-slate-100">
        <img src="{{ asset('public/uploads/blogs/'.$blog->featured_image) }}" alt="{{ $blog->title }}" class="h-full w-full object-cover">
      </div>
      @endif

      <div class="p-8 sm:p-12">
        <div class="blog-content prose prose-lg max-w-none text-slate-700">
          {!! $blog->content !!}
        </div>

        @if($blog->meta_keywords)
        <div class="mt-12 rounded-2xl bg-slate-50 p-6 text-sm text-slate-600 border border-slate-100">
          <span class="text-slate-900 font-bold uppercase tracking-wider text-xs me-2">Tags:</span>
          @foreach(explode(',', $blog->meta_keywords) as $keyword)
            <span class="inline-block bg-white border border-slate-200 rounded-full px-3 py-1 text-xs font-medium text-slate-700 me-1 mb-1">
              #{{ trim($keyword) }}
            </span>
          @endforeach
        </div>
        @endif
      </div>
    </article>

    <div class="mt-10 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <a href="{{ route('blogs.index') }}" class="inline-flex items-center gap-2 rounded-full border border-teal-200 bg-white px-5 py-3 text-sm font-semibold text-teal-700 transition hover:bg-teal-50">&larr; Back to blogs</a>
      <div class="text-sm text-slate-500">Share this post with friends and classmates.</div>
    </div>
  </div>
</section>
@endsection
