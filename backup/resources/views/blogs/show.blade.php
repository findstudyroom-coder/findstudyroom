@extends('master')

@section('title', $blog->meta_title ?: $blog->title)
@section('meta_description', $blog->meta_description ?: \Illuminate\Support\Str::limit($blog->short_description, 155))

@section('content')
<section class="bg-[radial-gradient(circle_at_top,_var(--tw-gradient-stops))] from-teal-100 via-white to-white py-16 px-6">
  <div class="max-w-6xl mx-auto">
    <div class="mb-10 rounded-[2rem] border border-slate-200 bg-white p-8 shadow-lg">
      <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
          <p class="text-sm uppercase tracking-[0.3em] text-teal-700">Blog Post</p>
          <h1 class="mt-4 text-4xl font-bold text-slate-900">{{ $blog->title }}</h1>
          <p class="mt-4 max-w-3xl text-lg leading-8 text-slate-600">{{ $blog->short_description }}</p>
        </div>
        <div class="rounded-3xl bg-slate-50 px-6 py-4 text-sm text-slate-600 shadow-sm">
          <p><strong>Author:</strong> {{ $blog->author_name ?? 'Findstudyroom' }}</p>
          <p class="mt-2"><strong>Published:</strong> {{ optional($blog->published_at)->format('d M Y') }}</p>
          <p class="mt-2"><strong>Status:</strong> {{ ucfirst($blog->status) }}</p>
        </div>
      </div>
    </div>

    <article class="rounded-[2rem] border border-slate-200 bg-white shadow-sm overflow-hidden">
      @if($blog->featured_image)
      <div class="h-96 overflow-hidden bg-slate-100">
        <img src="{{ asset('public/uploads/blogs/'.$blog->featured_image) }}" alt="{{ $blog->title }}" class="h-full w-full object-cover">
      </div>
      @endif

      <div class="p-10">
        <div class="prose prose-lg max-w-none text-slate-700">
          {!! $blog->content !!}
        </div>

        @if($blog->meta_keywords)
        <div class="mt-12 rounded-3xl bg-slate-50 p-6 text-sm text-slate-600">
          <span class="text-slate-900 font-semibold">Keywords:</span> {{ $blog->meta_keywords }}
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
