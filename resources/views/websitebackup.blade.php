@extends('master')

@php
use App\Services\ImageService;
$homeDescription = 'Looking for a peaceful study room or expert tutor? Search and compare the best study libraries and tutors near you. Check fees, amenities, and book a free visit today!';
@endphp

@section('title', 'Findstudyroom - Find Best Study Libraries & Tutors Near You')
@section('meta_description', $homeDescription)
@section('meta_keywords', 'best study library near me, tutors near me, library booking, top study room, coaching center, affordable library membership')
@section('og_title', 'Findstudyroom - Find Best Study Libraries & Tutors Near You')
@section('og_description', $homeDescription)
@section('twitter_title', 'Findstudyroom - Find Best Study Libraries & Tutors Near You')
@section('twitter_description', $homeDescription)

@push('head')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "WebSite",
      "name": "Findstudyroom",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/search') }}?search={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@type": "Organization",
      "name": "Findstudyroom",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('public/uploads/fav.svg') }}"
    }
  ]
}
</script>
@endpush

@section('content')

<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
#searchResults a {
    transition: all 0.2s ease;
}

#searchResults a:hover {
    background: #f9fafb;
}
#searchResults {
  z-index: 99999;
  position: absolute;
  top: calc(100% + 8px); /* ✅ fixes dropdown clipping */
  left: 0;
  right: 0;
  background: white;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* Enhanced search input visuals */
.search-input-wrapper {
  transition: box-shadow 0.18s ease, transform 0.18s ease;
}
.search-input-wrapper:focus-within {
  box-shadow: 0 8px 30px rgba(16, 185, 129, 0.12);
  transform: translateY(-2px);
}
.search-icon {
  width: 20px;
  height: 20px;
  color: #0f766e;
}
.result-item:hover {
  background: #f8faf9;
}
.result-header {
  background: linear-gradient(90deg, rgba(6,182,212,0.04), rgba(16,185,129,0.03));
}
</style>
 <section class="relative">
        <div class="gradient-blob w-96 h-96 bg-accent top-20 -left-48"></div>
        <div class="gradient-blob w-80 h-80 bg-coral top-40 right-0"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-20 relative">
          <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-soft-teal rounded-full text-accent text-sm font-medium mb-6 animate-slide-up">
              <span class="w-2 h-2 bg-accent rounded-full"></span>
              10,000+ verified tutors across India
            </div>
            
            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-fg mb-6 animate-slide-up stagger-1">
              Find the Best Tutors<br>
              <span class="text-accent">Near You</span>
            </h1>
            
            <p class="text-lg text-muted mb-10 max-w-2xl mx-auto animate-slide-up stagger-2">
              Connect with expert tutors, discover top coaching institutes, and access online courses. Your educational journey starts here.
            </p>

            <!-- Search Box -->
            <div class="bg-white rounded-3xl shadow-2xl p-6 max-w-4xl mx-auto animate-slide-up stagger-3 border border-gray-100">

  <form action="{{ route('search') }}" method="GET" class="flex flex-col gap-4">

    <div class="flex flex-col gap-3">
      <div class="flex items-center justify-between gap-3">
        <div>
          <p class="text-sm text-gray-500">Type a tutor name, library area, board or subject to find matching results.</p>
        </div>
        <span class="inline-flex items-center rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-700">All in one</span>
      </div>

      <div class="relative">
        <div class="relative flex items-center gap-3 search-input-wrapper bg-white rounded-2xl border border-gray-200 px-3 py-2">
          <div class="flex items-center px-2">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
            </svg>
          </div>

          <input 
            type="text" 
            id="searchInput"
            name="search"
            placeholder="Search tutors, libraries, subjects or locations"
            class="flex-1 bg-transparent px-2 py-3 text-lg placeholder-gray-400 text-gray-900 focus:outline-none"
            autocomplete="off"
          >

          <button type="button" id="clearSearch" class="text-gray-400 hover:text-gray-600 px-2 hidden">
            ✕
          </button>
        </div>

        <!-- Dropdown -->
        <div id="searchResults" 
             class="absolute top-full left-0 right-0 mt-3 hidden overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">
        </div>
      </div>
    </div>

    <div class="flex flex-wrap gap-2">
      <span class="rounded-full border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-600">JEE tutors</span>
      <span class="rounded-full border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-600">Library near me</span>
      <span class="rounded-full border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-600">Study room</span>
      <span class="rounded-full border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-600">Online coaching</span>
    </div>

    <button type="submit"
      class="w-full mt-2 inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-teal-600 to-teal-500 px-8 py-4 text-base font-semibold text-white shadow-lg transition-transform hover:-translate-y-0.5 hover:from-teal-700 hover:to-teal-600">
      Search now
    </button>

  </form>
</div>
            </div>

            <!-- Popular Searches -->
            
          </div>
        </div>
      </section>

      <!-- Stats -->
      <section class="bg-white border-y border-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
              <div class="font-display text-3xl sm:text-4xl font-bold text-accent">10,000+</div>
              <div class="text-muted text-sm mt-1">Verified Tutors</div>
            </div>
            <div class="text-center">
              <div class="font-display text-3xl sm:text-4xl font-bold text-accent">50,000+</div>
              <div class="text-muted text-sm mt-1">Students Enrolled</div>
            </div>
            <div class="text-center">
              <div class="font-display text-3xl sm:text-4xl font-bold text-accent">2,500+</div>
              <div class="text-muted text-sm mt-1">Coaching Centers</div>
            </div>
            <div class="text-center">
              <div class="font-display text-3xl sm:text-4xl font-bold text-accent">4.8/5</div>
              <div class="text-muted text-sm mt-1">Average Rating</div>
            </div>
          </div>
        </div>
      </section>

<!-- FEATURES SECTION -->
<section class="py-10 px-6">
  <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-8 text-center">

    <div class="bg-white p-6 rounded-xl shadow">
      <h3 class="text-xl font-semibold mb-2">Expert Tutors</h3>
      <p class="text-gray-600">Learn from verified professionals</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
      <h3 class="text-xl font-semibold mb-2">Flexible Learning</h3>
      <p class="text-gray-600">Online & Offline options</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
      <h3 class="text-xl font-semibold mb-2">Affordable Pricing</h3>
      <p class="text-gray-600">Best value courses</p>
    </div>

  </div>
</section>



<section class="py-10 px-6 bg-gray-50">
  <div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
      <div>
        <h2 class="text-2xl font-bold text-gray-800">Libraries Near You</h2>
        <p class="text-gray-500">Top rated study spaces in your area</p>
      </div>
      <a href="{{route('libraries.all')}}" class="text-teal-600 font-medium">View All</a>
    </div>

    @php
        $firstFour = $library->take(4);
        $rest = $library->slice(4);
    @endphp

    <!-- Grid (First 4) -->
    <div class="grid md:grid-cols-4 gap-6">

   
   
   
    </div>

    <!-- Slider (Remaining) -->
    @if($rest->count())
  <div class="mt-8 relative">

    <!-- Left Arrow -->
<button onclick="slideLeft()" 
    class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow p-2 rounded-full z-10">
    ◀
</button>


    <!-- Slider -->
    <div id="slider" class="flex gap-6 overflow-x-auto scroll-smooth no-scrollbar px-8">

        @foreach($library as $lib)
        <div class="min-w-[280px] max-w-[280px] bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">

            <div class="relative">
                <a href="{{ route('library.details', $lib->slug) }}">
                    <img src="{{ !empty($lib->images) && is_array($lib->images) && count($lib->images) > 0 ? asset('storage/app/public/'.$lib->images[0]) : (is_string($lib->images) ? asset('storage/app/public/'.$lib->images) : \App\Services\ImageService::getLibraryImage()) }}"
                         onerror="this.onerror=null;this.src='{{ \App\Services\ImageService::getLibraryImage() }}'"
                      class="w-full h-48 object-cover">
                </a>

                <span class="absolute top-3 right-3 
                    {{ $lib->status == 'open' ? 'bg-green-500' : 'bg-gray-500' }} 
                    text-white text-xs px-3 py-1 rounded-full">
                    {{ $lib->status == 'open' ? 'Open Now' : 'Closed' }}
                </span>
            </div>

            <div class="p-4">
                <h3 class="font-semibold text-lg">
                    {{ $lib->name }}
                    @if($lib->is_verified)
                        <span class="inline-flex items-center justify-center bg-blue-100 text-blue-700 text-[10px] font-bold px-1.5 py-0.5 rounded ml-1 align-middle" title="Verified Premium Library">
                            <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.403 12.652a3 3 0 000-5.304 3 3 0 00-3.75-3.751 3 3 0 00-5.305 0 3 3 0 00-3.751 3.75 3 3 0 000 5.305 3 3 0 003.75 3.751 3 3 0 005.305 0 3 3 0 003.751-3.75zm-2.546-4.46a.75.75 0 00-1.214-.883l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"></path></svg>
                            VERIFIED
                        </span>
                    @endif
                </h3>

                <div class="flex justify-between items-center mt-3">
                    <p class="text-teal-600 font-semibold">{{ $lib->price ? '₹'.$lib->price.'/month' : 'Price coming soon' }}</p>
                    <span class="bg-green-100 text-green-600 text-sm px-2 py-1 rounded">
                        ★ {{ $lib->rating ?? '4.5' }}
                    </span>
                </div>
            </div>

        </div>
        @endforeach

    </div>

    <!-- Right Arrow -->
   <button onclick="slideRight()" 
    class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow p-2 rounded-full z-10">
    ▶
</button>

</div>
    @endif

  </div>
</section>

<!-- TUTORS SECTION -->
<section class="py-10 px-6 bg-white">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
      <div>
        <h2 class="text-2xl font-bold text-gray-800">Top Tutors</h2>
        <p class="text-gray-500">Expert tutors available for online and offline classes.</p>
      </div>
      <a href="{{ route('tutors.all') }}" class="text-teal-600 font-medium">View All Tutors</a>
    </div>

    <div class="grid gap-6 md:grid-cols-3 lg:grid-cols-4">
      @foreach($tutors as $tutor)
      <a href="{{ url('/tutor/'.$tutor->public_slug) }}" class="group block overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
        <div class="h-48 overflow-hidden bg-gray-100">
          <img src="{{ $tutor->image ? asset('public/uploads/tutors/'.$tutor->image) : ImageService::getTutorImage() }}" alt="{{ $tutor->full_name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        </div>
        <div class="p-5">
          <h3 class="text-lg font-semibold text-gray-900">{{ $tutor->full_name }}</h3>
          <p class="text-sm text-gray-500 mt-2">{{ implode(', ', array_slice($tutor->subject ?? [], 0, 3)) ?: 'General tutor' }}</p>
          <div class="mt-4 flex items-center justify-between gap-3 text-sm">
            <span class="text-teal-600 font-semibold">₹{{ $tutor->hourly_rate ?? 'N/A' }}/hr</span>
            <span class="text-gray-500">{{ $tutor->experience_years ? $tutor->experience_years.' yrs' : 'No exp' }}</span>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>

@if($blogs->count())
<section class="py-10 px-6 bg-gray-50">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
      <div>
        <h2 class="text-2xl font-bold text-gray-800">Latest Blogs</h2>
        <p class="text-gray-500">Read our latest updates, tips and stories from students and educators.</p>
      </div>
      <a href="{{ route('blogs.index') }}" class="text-teal-600 font-medium">View All Blogs</a>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
      @foreach($blogs as $blog)
      <a href="{{ route('blogs.show', $blog->slug) }}" class="group block overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
        <div class="h-56 overflow-hidden bg-gray-100">
          @if($blog->featured_image)
            <img src="{{ asset('public/uploads/blogs/'.$blog->featured_image) }}" alt="{{ $blog->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
          @else
            <div class="flex h-full items-center justify-center bg-gray-200 text-gray-500">No image</div>
          @endif
        </div>
        <div class="p-5">
          <h3 class="text-xl font-semibold text-gray-900">{{ $blog->title }}</h3>
          <p class="text-sm text-gray-500 mt-3 line-clamp-3">{{ \Illuminate\Support\Str::limit($blog->short_description, 120) }}</p>
          <div class="mt-4 flex items-center justify-between gap-3 text-sm text-gray-500">
            <span>{{ $blog->author_name ?? 'Findstudyroom' }}</span>
            <span>{{ optional($blog->published_at)->format('d M Y') }}</span>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>
@endif

@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {

    let input = document.getElementById("searchInput");
    let resultsBox = document.getElementById("searchResults");

    input.addEventListener("input", function () {
        let query = this.value.trim();

        if (query.length < 2) {
            resultsBox.classList.add("hidden");
            return;
        }

        fetch(`/search-ajax?search=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                let html = `
                  <div class="px-4 py-4 border-b border-gray-100 bg-gray-50">
                    <p class="text-sm font-semibold text-gray-900">Search suggestions</p>
                    <p class="text-xs text-gray-500">Results from tutors and libraries</p>
                  </div>
                `;

                if (data.tutors && data.tutors.length) {
                    html += `<div class="p-3 border-b border-gray-100">
                        <div class="text-sm font-semibold text-gray-700 mb-2">Tutors</div>`;
                    data.tutors.forEach(item => {
                        html += `
                            <a href="/tutor/${item.slug}" class="block rounded-2xl px-3 py-3 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-medium text-gray-900">${item.full_name}</p>
                                        <p class="text-xs text-gray-500">${item.subject ? item.subject.slice(0, 3).join(', ') : 'Tutoring'}</p>
                                    </div>
                                    <span class="text-teal-600 text-xs font-semibold">Tutor</span>
                                </div>
                            </a>
                        `;
                    });
                    html += `</div>`;
                }

                if (data.libraries && data.libraries.length) {
                    html += `<div class="p-3">
                        <div class="text-sm font-semibold text-gray-700 mb-2">Libraries</div>`;
                    data.libraries.forEach(item => {
                        let verifiedBadge = item.is_verified ? `
                            <span class="inline-flex items-center justify-center bg-blue-100 text-blue-700 text-[10px] font-bold px-1.5 py-0.5 rounded ml-1 align-middle" title="Verified Premium Library">
                                <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.403 12.652a3 3 0 000-5.304 3 3 0 00-3.75-3.751 3 3 0 00-5.305 0 3 3 0 00-3.751 3.75 3 3 0 000 5.305 3 3 0 003.75 3.751 3 3 0 005.305 0 3 3 0 003.751-3.75zm-2.546-4.46a.75.75 0 00-1.214-.883l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"></path></svg>
                                VERIFIED
                            </span>` : '';

                        html += `
                            <a href="/library/${item.slug}" class="block rounded-2xl px-3 py-3 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <h3 class="font-bold text-lg text-gray-900">${item.name}${verifiedBadge}</h3>
                                        <p class="text-xs text-gray-500">${item.location || 'Library location'}</p>
                                    </div>
                                    <span class="text-teal-600 text-xs font-semibold">Library</span>
                                </div>
                            </a>
                        `;
                    });
                    html += `</div>`;
                }

                if ((!data.tutors || !data.tutors.length) && (!data.libraries || !data.libraries.length)) {
                    html += `<div class="px-4 py-4 text-sm text-gray-500">No matches found. Press Enter to see full results.</div>`;
                }

                html += `<a href="/search?search=${encodeURIComponent(query)}" class="block rounded-b-3xl bg-gray-50 px-4 py-4 text-center text-sm font-semibold text-teal-700 hover:bg-teal-50">View all results for “${query}”</a>`;

                resultsBox.innerHTML = html;
                resultsBox.classList.remove("hidden");
            });
    });

    document.addEventListener("click", function(e){
        if (!input.contains(e.target) && !resultsBox.contains(e.target)) {
            resultsBox.classList.add("hidden");
        }
    });
});
</script>
<script>
function slideLeft() {
    const slider = document.getElementById('slider');
    if (!slider) return;

    const card = slider.querySelector('[data-slider-card]');
    const gap = parseFloat(getComputedStyle(slider).gap) || 0;
    const scrollAmount = (card ? card.offsetWidth : slider.clientWidth) + gap;

    slider.scrollBy({
        left: -scrollAmount,
        behavior: 'smooth'
    });
}

function slideRight() {
    const slider = document.getElementById('slider');
    if (!slider) return;

    const card = slider.querySelector('[data-slider-card]');
    const gap = parseFloat(getComputedStyle(slider).gap) || 0;
    const scrollAmount = (card ? card.offsetWidth : slider.clientWidth) + gap;

    slider.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
    });
}
</script>