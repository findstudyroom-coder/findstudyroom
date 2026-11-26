@extends('master')

@section('title', 'Blogs | Findstudyroom')
@section('meta_description', 'Read our latest blog posts and updates from Findstudyroom.')

@section('content')
<section class="bg-[radial-gradient(circle_at_top,_var(--tw-gradient-stops))] from-teal-100 via-white to-white py-12 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr] items-center">
      <div>
        <span class="inline-flex rounded-full bg-teal-50 px-4 py-2 text-sm font-semibold text-teal-700">Blog</span>
        <h1 class="mt-4 text-4xl sm:text-5xl font-bold tracking-tight text-slate-900">Latest stories, study tips, and student success guides.</h1>
        <p class="mt-3 max-w-2xl text-lg leading-8 text-slate-600">Discover fresh classroom strategies, library updates, and tutor advice from the Findstudyroom community.</p>
      </div>
      <div class="rounded-4xl border border-slate-200 bg-white p-6 shadow-lg">
        <!-- <p class="text-sm uppercase tracking-[0.2em] text-teal-700">Featured post</p> -->
        @if($blogs->first())
          <a href="{{ route('blogs.show', $blogs->first()->slug) }}" class="block rounded-3xl overflow-hidden border border-slate-200 transition hover:-translate-y-1 hover:shadow-xl">
            @if($blogs->first()->featured_image)
              <img src="{{ asset('public/uploads/blogs/'.$blogs->first()->featured_image) }}"
                   alt="{{ $blogs->first()->title }} - Findstudyroom Blog"
                   title="{{ $blogs->first()->title }} - Findstudyroom"
                   class="h-52 w-full object-cover"
                   loading="lazy">
            @else
              <div class="h-52 bg-slate-100"></div>
            @endif
            <div class="p-6">
              <h2 class="text-2xl font-semibold text-slate-900">{{ $blogs->first()->title }}</h2>
              <p class="mt-3 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($blogs->first()->short_description, 120) }}</p>
            </div>
          </a>
        @endif
      </div>
    </div>
  </div>
</section>

<section class="py-16 px-6 bg-slate-50">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between mb-8">
      <div>
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-teal-700">All articles</p>
        <h2 class="mt-3 text-3xl font-bold text-slate-900">Browse the full blog archive</h2>
      </div>
      <a href="{{ url('/') }}" class="text-teal-600 font-semibold hover:text-teal-700">Back to homepage</a>
    </div>

    @if($blogs->count())
      <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
        @foreach($blogs as $blog)
        <article class="group overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
          <a href="{{ route('blogs.show', $blog->slug) }}">
            <div class="h-64 overflow-hidden bg-slate-100">
              @if($blog->featured_image)
                <img src="{{ asset('public/uploads/blogs/'.$blog->featured_image) }}"
                     alt="{{ $blog->title }} - Findstudyroom Blog"
                     title="{{ $blog->title }} - Findstudyroom"
                     class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                     loading="lazy">
              @endif
            </div>
          </a>
          <div class="p-6">
            <div class="flex items-center justify-between gap-3 text-xs uppercase tracking-[0.24em] text-teal-700">
              <span>{{ ucfirst($blog->status) }}</span>
              <span>{{ optional($blog->published_at)->format('d M Y') }}</span>
            </div>
            <a href="{{ route('blogs.show', $blog->slug) }}" class="mt-4 block text-2xl font-semibold text-slate-900 hover:text-teal-700">{{ $blog->title }}</a>
            <p class="mt-4 text-sm leading-7 text-slate-600">{{ \Illuminate\Support\Str::limit($blog->short_description, 130) }}</p>
            <div class="mt-6 flex items-center gap-2 text-sm text-slate-500">
              <span>{{ $blog->author_name ?? 'Findstudyroom' }}</span>
            </div>
          </div>
        </article>
        @endforeach
      </div>
    @else
      <div class="rounded-4xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-600">
        No blog posts found. Please check back later.
      </div>
    @endif
  </div>
</section>
@endsection
