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
@php
    $heroImages = [
        'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2000&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1513258496099-48168024aec0?q=80&w=2000&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2000&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1577415124269-fc1140a69e91?q=80&w=2000&auto=format&fit=crop'
    ];
@endphp

<section class="relative min-h-[500px] flex items-center overflow-hidden">
  <!-- Background Slider -->
  <div class="absolute inset-0 z-0">
    @foreach($heroImages as $index => $img)
      <div class="hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out {{ $index == 0 ? 'opacity-100' : 'opacity-0' }}" style="background-image: url('{{ $img }}');"></div>
    @endforeach
  </div>
  
  <!-- Overlay -->
  <div class="absolute inset-0 bg-gradient-to-r from-gray-900/95 via-gray-900/70 to-gray-900/40 z-0"></div>
  
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10 w-full flex flex-col lg:flex-row items-center justify-between gap-12">
    
    <!-- Left Content -->
    <div class="w-full lg:w-1/2 text-left animate-slide-up">
      <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-5 leading-tight tracking-tight">
        Find the Best Study <br> Library Near You
      </h1>
      
      <p class="text-lg text-gray-200 mb-8 max-w-xl font-light">
       
    Connect with expert tutors, discover top coaching institutes, and access online courses. Your educational journey starts here.
    </p>

      <!-- Badges -->
      <div class="flex flex-wrap gap-3">
        <span class="rounded-full border border-white/30 bg-white/10 backdrop-blur-md px-4 py-2 text-sm text-white flex items-center gap-2"><svg class="w-4 h-4 text-teal-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Verified Libraries</span>
        <span class="rounded-full border border-white/30 bg-white/10 backdrop-blur-md px-4 py-2 text-sm text-white flex items-center gap-2"><svg class="w-4 h-4 text-teal-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Top Tutors</span>
        <!-- <span class="rounded-full border border-white/30 bg-white/10 backdrop-blur-md px-4 py-2 text-sm text-white flex items-center gap-2"><svg class="w-4 h-4 text-teal-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Instant Booking</span>
      -->
      </div>
    </div>

    <!-- Right Content (Search Box) -->
    <div class="w-full lg:w-1/2 relative lg:-mt-6">
      
      <div class="w-full max-w-md mx-auto lg:mx-0 lg:ml-auto animate-slide-up stagger-3 bg-white rounded-[1.5rem] p-6 lg:p-7 shadow-2xl">
        
        <h2 class="text-xl lg:text-2xl font-bold text-gray-900 mb-5">Search The Right Study Space</h2>

        <!-- Tabs -->
        <div class="flex items-center gap-6 border-b border-gray-100 mb-5" id="searchTabs">
            <button type="button" data-tab="tab-type" class="search-tab-btn pb-3 text-teal-600 font-bold border-b-2 border-teal-600 text-[13px] uppercase tracking-wide">TYPE</button>
            <button type="button" data-tab="tab-location" class="search-tab-btn pb-3 text-gray-400 font-semibold hover:text-gray-600 transition text-[13px] uppercase tracking-wide">LOCATION</button>
            <button type="button" data-tab="tab-budget" class="search-tab-btn pb-3 text-gray-400 font-semibold hover:text-gray-600 transition text-[13px] uppercase tracking-wide">BUDGET</button>
        </div>

        <form action="{{ route('search') }}" method="GET" class="flex flex-col gap-4" id="searchHeroForm">
          
          <input type="hidden" name="type" id="hiddenType">
          
          <!-- Type Tab Content -->
          <div id="tab-type" class="search-tab-content relative w-full block">
            <div class="relative bg-white border border-gray-300 rounded-lg flex items-center shadow-sm">
               <input type="text" id="typeSearchInput" placeholder="Search Library Type..." class="w-full bg-transparent px-4 py-3 text-sm lg:text-base text-gray-900 focus:outline-none placeholder-gray-500 rounded-lg" autocomplete="off">
               <div class="px-4 text-gray-400">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
               </div>
            </div>
            <!-- Autocomplete Dropdown -->
            <ul id="typeSuggestions" class="absolute top-full left-0 right-0 mt-1 hidden overflow-hidden rounded-lg border border-gray-200 bg-white shadow-xl z-[100] max-h-60 overflow-y-auto"></ul>
          </div>

          <!-- Location Tab Content -->
          <div id="tab-location" class="search-tab-content hidden flex-col gap-3 w-full">
             <div class="relative bg-white border border-gray-300 rounded-lg flex items-center shadow-sm">
                <select name="state_id" id="stateSelect" class="w-full bg-transparent px-4 py-3 text-sm lg:text-base text-gray-900 focus:outline-none rounded-lg appearance-none cursor-pointer">
                    <option value="">Select State</option>
                </select>
                <div class="absolute right-4 text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
             </div>
             
             <div class="relative bg-white border border-gray-300 rounded-lg flex items-center shadow-sm">
                <select name="district_id" id="districtSelect" class="w-full bg-transparent px-4 py-3 text-sm lg:text-base text-gray-900 focus:outline-none rounded-lg appearance-none cursor-pointer" disabled>
                    <option value="">Select District/City</option>
                </select>
                <div class="absolute right-4 text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
             </div>
          </div>

          <!-- Budget Tab Content -->
          <div id="tab-budget" class="search-tab-content hidden w-full">
             <div class="relative bg-white border border-gray-300 rounded-lg flex items-center shadow-sm">
                <select name="budget" id="budgetSelect" class="w-full bg-transparent px-4 py-3 text-sm lg:text-base text-gray-900 focus:outline-none rounded-lg appearance-none cursor-pointer">
                    <option value="">Select Budget</option>
                    <option value="Under ₹500">Under ₹500</option>
                    <option value="₹500–1000">₹500–1000</option>
                    <option value="₹1000–1500">₹1000–1500</option>
                    <option value="₹1500–2000">₹1500–2000</option>
                    <option value="Above ₹2000">Above ₹2000</option>
                </select>
                <div class="absolute right-4 text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
             </div>
          </div>

          <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white rounded-lg py-3.5 font-bold text-sm transition-colors shadow mt-2">
            Search
          </button>
        </form>

        <div class="mt-5 text-center">
            <a href="{{ route('libraries.all') }}" class="text-teal-600 font-bold text-xs hover:underline inline-flex items-center gap-1">
                Find All Libraries <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
        
      </div>
    </div>
    
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.hero-slide');
    let currentSlide = 0;
    
    if(slides.length > 1) {
        setInterval(() => {
            slides[currentSlide].classList.remove('opacity-100');
            slides[currentSlide].classList.add('opacity-0');
            
            currentSlide = (currentSlide + 1) % slides.length;
            
            slides[currentSlide].classList.remove('opacity-0');
            slides[currentSlide].classList.add('opacity-100');
        }, 5000);
    }
});
</script>

      <!-- Stats & Filters -->
      @php
          $verifiedLibrariesCount = \App\Models\Library::where('is_verified', 1)->count();
          $totalCitiesCount = \App\Models\Library::distinct('city_id')->count('city_id');
      @endphp
      <section class="bg-white border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
          
          <!-- Stats Row -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10">
            <div class="text-center">
              <div class="font-display text-4xl sm:text-5xl font-bold text-teal-600 mb-2">{{ $verifiedLibrariesCount > 0 ? $verifiedLibrariesCount . '+' : '0' }}</div>
              <div class="text-gray-600 font-medium text-sm sm:text-base">Verified Libraries</div>
            </div>
            <div class="text-center">
              <div class="font-display text-4xl sm:text-5xl font-bold text-teal-600 mb-2">{{ $totalCitiesCount > 0 ? $totalCitiesCount . '+' : '0' }}</div>
              <div class="text-gray-600 font-medium text-sm sm:text-base">Cities Covered</div>
            </div>
            <div class="text-center">
              <div class="font-display text-4xl sm:text-5xl font-bold text-teal-600 mb-2">50k+</div>
              <div class="text-gray-600 font-medium text-sm sm:text-base">Active Students</div>
            </div>
            <div class="text-center">
              <div class="font-display text-4xl sm:text-5xl font-bold text-teal-600 mb-2">4.8<span class="text-3xl text-teal-600">★</span></div>
              <div class="text-gray-600 font-medium text-sm sm:text-base">Avg. User Rating</div>
            </div>
          </div>

          <!-- Filter Chips -->
          <div class="flex flex-wrap justify-center gap-3">
            <button class="flex items-center gap-2 px-4 py-2 rounded-full border border-teal-200 bg-teal-50 text-teal-600 hover:bg-teal-100 transition text-sm font-medium">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18m-9-9h18m-9-6l6 6-6 6m-6-6l6-6-6 6"/></svg> AC
            </button>
            <button class="flex items-center gap-2 px-4 py-2 rounded-full border border-gray-200 bg-gray-50 text-gray-700 hover:bg-gray-100 transition text-sm font-medium">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M4.394 10.404c5.858-5.858 15.353-5.858 21.212 0"/></svg> Free WiFi
            </button>
            <button class="flex items-center gap-2 px-4 py-2 rounded-full border border-gray-200 bg-gray-50 text-gray-700 hover:bg-gray-100 transition text-sm font-medium">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 24 Hours
            </button>
            <button class="flex items-center gap-2 px-4 py-2 rounded-full border border-gray-200 bg-gray-50 text-gray-700 hover:bg-gray-100 transition text-sm font-medium">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg> UPSC Oriented
            </button>
            <button class="flex items-center gap-2 px-4 py-2 rounded-full border border-gray-200 bg-gray-50 text-gray-700 hover:bg-gray-100 transition text-sm font-medium">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Girls Only
            </button>
            <button class="flex items-center gap-2 px-4 py-2 rounded-full border border-gray-200 bg-gray-50 text-gray-700 hover:bg-gray-100 transition text-sm font-medium">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Cafe Access
            </button>
            <!-- <button class="flex items-center gap-2 px-4 py-2 rounded-full border border-gray-200 bg-gray-50 text-gray-700 hover:bg-gray-100 transition text-sm font-medium">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg> All Filters
            </button> -->
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
<!-- POPULAR STUDY HUBS -->
<section class="py-12 px-6 bg-gray-50">
  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
      <div>
        <h2 class="text-3xl font-bold text-gray-900">Popular Study Hubs</h2>
        <p class="text-gray-500 mt-1">Discover the most active student communities across India.</p>
      </div>
      <a href="{{ route('libraries.all') }}" class="text-teal-600 font-medium hover:underline flex items-center gap-1">View all states <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
    </div>

    <!-- Filters -->
    <div class="flex items-center gap-3 mb-8 overflow-x-auto no-scrollbar pb-2">
        <button class="px-6 py-2.5 rounded-full bg-teal-600 text-white font-medium text-sm whitespace-nowrap shadow-md shadow-teal-500/20">Libraries</button>
        <!-- <button class="px-6 py-2.5 rounded-full bg-teal-50 text-gray-600 hover:bg-teal-100 transition font-medium text-sm whitespace-nowrap">Tuition Classes</button>
        <button class="px-6 py-2.5 rounded-full bg-teal-50 text-gray-600 hover:bg-teal-100 transition font-medium text-sm whitespace-nowrap">Coachings</button>
    -->
      </div>

    <!-- Slider Container -->
    <div class="flex gap-6 overflow-x-auto snap-x no-scrollbar pb-6 w-full">
        
        @php
            $stateImages = [
                'https://images.unsplash.com/photo-1477587458883-47145ed94245?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1587474260584-136574528ed5?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1514222134-b57cbb8ce073?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1595815771614-c28c894e75bb?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1548013146-5624d7d96071?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1571536802807-30451e3955d8?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1564507592227-d02336efa33f?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1506461883276-594540eb3bb7?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1600100397608-f010f419c74c?q=80&w=600&auto=format&fit=crop'
            ];
        @endphp

        @forelse($topStates as $index => $state)
        <!-- State Card -->
        <a href="{{ route('libraries.state', \Illuminate\Support\Str::slug($state->name)) }}" class="group relative h-64 min-w-[280px] sm:min-w-[300px] snap-center rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300">
            <img src="{{ $stateImages[$index % count($stateImages)] }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $state->name }}">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
            <div class="absolute bottom-5 left-5 text-left w-full pr-5">
                <h3 class="text-2xl font-bold text-white mb-1 truncate">{{ $state->name }}</h3>
                <p class="text-gray-200 text-sm font-medium">{{ $state->libraries_count }}  Libraries</p>
            </div>
        </a>
        @empty
            <div class="w-full text-center text-gray-500">No popular states found.</div>
        @endforelse

    </div>
  </div>
</section>

<!-- TOP RATED LIBRARIES SLIDER -->
<section class="py-10 px-6 bg-white">
  <div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
      <div>
        <h2 class="text-3xl font-bold text-gray-900">Top Rated Libraries</h2>
        <p class="text-gray-500 mt-1">Handpicked spaces with the best reviews from students.</p>
      </div>
      <a href="{{ route('libraries.all') }}" class="text-teal-600 font-medium hover:underline flex items-center gap-1">View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
    </div>

    @if($library->count())
    <div class="relative group">
        <!-- Left Arrow -->
        <button onclick="slideLeft()" class="absolute -left-4 top-1/2 -translate-y-1/2 bg-white shadow-lg border border-gray-100 p-3 rounded-full z-10 opacity-0 group-hover:opacity-100 transition duration-300 hover:bg-gray-50">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <!-- Slider -->
        <div id="slider" class="flex gap-6 overflow-x-auto scroll-smooth no-scrollbar pb-6 pt-2 px-2 -mx-2">
            @foreach($library as $lib)
            <div data-slider-card class="min-w-[300px] max-w-[300px] bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 overflow-hidden group/card flex flex-col">
                
                <div class="relative h-48 overflow-hidden">
                    <a href="{{ route('library.details', $lib->slug) }}" class="block w-full h-full">
                        @php
                            $libImages = [
                                'https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=600&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1541963463532-d68292c34b19?q=80&w=600&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?q=80&w=600&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=600&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1568667256549-094345857637?q=80&w=600&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=600&auto=format&fit=crop'
                            ];
                            $fallbackImage = $libImages[$loop->index % count($libImages)];
                            
                            $imgSrc = $fallbackImage;
                            if (!empty($lib->images)) {
                                if (is_array($lib->images) && count($lib->images) > 0) {
                                    $imgSrc = asset('storage/app/public/'.$lib->images[0]);
                                } elseif (is_string($lib->images) && strlen($lib->images) > 5) {
                                    $imgSrc = asset('storage/app/public/'.$lib->images);
                                }
                            }
                        @endphp
                        <img src="{{ $imgSrc }}"
                             onerror="this.onerror=null;this.src='{{ $fallbackImage }}'"
                             class="w-full h-full object-cover group-hover/card:scale-105 transition duration-500">
                    </a>

                    @if($lib->is_verified)
                        <span class="absolute top-3 left-3 bg-teal-600 text-white text-[10px] font-bold px-2 py-1 rounded-md tracking-wider shadow-md">
                            VERIFIED
                        </span>
                    @endif

                    <button class="absolute top-3 right-3 p-1.5 bg-black/20 hover:bg-black/40 backdrop-blur-sm rounded-full text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                </div>

                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex justify-between items-start gap-2 mb-2">
                        <h3 class="font-bold text-lg text-gray-900 leading-tight">
                            <a href="{{ route('library.details', $lib->slug) }}" class="hover:text-teal-600 transition">{{ $lib->name }}</a>
                        </h3>
                        <div class="flex items-center text-amber-500 shrink-0">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="ml-1 text-sm font-semibold text-gray-700">{{ $lib->rating ?? '4.8' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center text-gray-500 text-sm mb-4">
                        <svg class="w-4 h-4 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="truncate">{{ $lib->location ?? $lib->city }}</span>
                    </div>

                    <div class="mt-auto pt-4 border-t border-gray-100 flex flex-wrap gap-2">
                        @php
                            $tags = ['UPSC', 'WIFI', 'AC'];
                        @endphp
                        @foreach(array_slice($tags, 0, 3) as $tag)
                            <span class="px-2 py-1 bg-teal-50 text-teal-600 text-[10px] font-bold rounded tracking-wide">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Right Arrow -->
        <button onclick="slideRight()" class="absolute -right-4 top-1/2 -translate-y-1/2 bg-white shadow-lg border border-gray-100 p-3 rounded-full z-10 opacity-0 group-hover:opacity-100 transition duration-300 hover:bg-gray-50">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>
    @else
        <div class="text-gray-500 text-center py-8">No libraries found.</div>
    @endif

  </div>
</section>

<!-- TUTORS SECTION -->
{{--<section class="py-10 px-6 bg-white">
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
</section>--}}

@if($blogs->count())
<section class="py-16 px-6 bg-white">
  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
      <div>
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Latest from our Blog</h2>
        <p class="text-gray-500">Expert advice on productivity, exam prep, and finding your perfect study spot.</p>
      </div>
      <a href="{{ route('blogs.index') }}" class="text-teal-600 font-semibold hover:underline flex items-center gap-1">View all <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
    </div>

    <!-- Grid -->
    <div class="grid gap-6 md:grid-cols-3">
      @foreach($blogs as $index => $blog)
      @php
          $categories = ['STUDY TIPS', 'LIBRARY GUIDES', 'PRODUCTIVITY'];
          $category = $categories[$index % count($categories)];
          $avatars = ['bg-teal-100 text-teal-600', 'bg-green-100 text-green-600', 'bg-purple-100 text-purple-600'];
          $avatarClass = $avatars[$index % count($avatars)];
      @endphp
      <div class="group flex flex-col bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
        <!-- Image Container -->
        <a href="{{ route('blogs.show', $blog->slug) }}" class="relative block h-56 bg-gray-100 overflow-hidden">
          @if($blog->featured_image)
            <img src="{{ asset('public/uploads/blogs/'.$blog->featured_image) }}" alt="{{ $blog->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
          @else
            <div class="flex h-full items-center justify-center bg-gray-200 text-gray-500">No image</div>
          @endif
          <!-- Badge -->
          <span class="absolute top-4 left-4 bg-teal-700 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide shadow-md">
            {{ $category }}
          </span>
        </a>
        
        <!-- Content -->
        <div class="p-6 flex flex-col flex-grow">
          <a href="{{ route('blogs.show', $blog->slug) }}" class="block flex-grow">
            <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight group-hover:text-teal-600 transition-colors">{{ $blog->title }}</h3>
            <p class="text-sm text-gray-600 line-clamp-3 mb-6">{{ \Illuminate\Support\Str::limit($blog->short_description, 120) }}</p>
          </a>
          
          <!-- Footer -->
          <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $avatarClass }}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
              </div>
              <div>
                <p class="text-xs font-bold text-gray-900">{{ $blog->author_name ?? 'Findstudyroom' }}</p>
                <p class="text-[11px] text-gray-500">{{ optional($blog->published_at)->format('M d, Y') }}</p>
              </div>
            </div>
            <a href="{{ route('blogs.show', $blog->slug) }}" class="text-sm font-bold text-teal-600 hover:text-teal-700 flex items-center gap-1">
              Read More <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
          </div>
        </div>
      </div>
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
                            <span class="inline-flex items-center justify-center bg-teal-100 text-teal-700 text-[10px] font-bold px-1.5 py-0.5 rounded ml-1 align-middle" title="Verified Premium Library">
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Tab Switching Logic
    const tabBtns = document.querySelectorAll('.search-tab-btn');
    const tabContents = document.querySelectorAll('.search-tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active classes
            tabBtns.forEach(b => {
                b.classList.remove('text-teal-600', 'border-b-2', 'border-teal-600');
                b.classList.add('text-gray-400', 'font-semibold');
            });
            tabContents.forEach(c => {
                c.classList.add('hidden');
                c.classList.remove('flex', 'block');
            });

            // Add active class to clicked
            this.classList.remove('text-gray-400', 'font-semibold');
            this.classList.add('text-teal-600', 'border-b-2', 'border-teal-600', 'font-bold');

            // Show content
            const targetId = this.getAttribute('data-tab');
            const targetContent = document.getElementById(targetId);
            if(targetId === 'tab-location') {
                targetContent.classList.remove('hidden');
                targetContent.classList.add('flex');
            } else {
                targetContent.classList.remove('hidden');
                targetContent.classList.add('block');
            }
        });
    });

    // Type Autocomplete Logic
    const typeInput = document.getElementById('typeSearchInput');
    const typeSuggestions = document.getElementById('typeSuggestions');
    const hiddenType = document.getElementById('hiddenType');
    let allTypes = [];

    fetch('/api/library-types')
        .then(res => res.json())
        .then(data => { allTypes = data; });

    typeInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();
        hiddenType.value = this.value; // Sync hidden input
        typeSuggestions.innerHTML = '';

        if (!query) {
            typeSuggestions.classList.add('hidden');
            return;
        }

        let hasResults = false;

        // 1. Show matching types
        const filtered = allTypes.filter(t => t.toLowerCase().includes(query));
        if (filtered.length > 0) {
            hasResults = true;
            const header = document.createElement('li');
            header.className = 'px-4 py-2 bg-gray-50 text-xs font-semibold text-gray-500 uppercase';
            header.textContent = 'Library Types';
            typeSuggestions.appendChild(header);

            filtered.forEach(item => {
                const li = document.createElement('li');
                li.className = 'px-4 py-3 hover:bg-gray-100 cursor-pointer text-sm text-gray-800 border-b border-gray-50';
                li.textContent = item;
                li.addEventListener('click', function() {
                    typeInput.value = item;
                    hiddenType.value = item;
                    typeSuggestions.classList.add('hidden');
                });
                typeSuggestions.appendChild(li);
            });
            typeSuggestions.classList.remove('hidden');
        }

        // 2. Fetch actual libraries via ajax
        if (query.length >= 2) {
            fetch(`/search-ajax?search=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.libraries && data.libraries.length > 0) {
                        hasResults = true;
                        const header = document.createElement('li');
                        header.className = 'px-4 py-2 bg-gray-50 text-xs font-semibold text-gray-500 uppercase';
                        header.textContent = 'Libraries';
                        typeSuggestions.appendChild(header);

                        data.libraries.forEach(item => {
                            const li = document.createElement('li');
                            li.className = 'hover:bg-gray-100 cursor-pointer border-b border-gray-50';
                            li.innerHTML = `<a href="/library/${item.slug}" class="block px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-sm text-gray-900">${item.name}</p>
                                        <p class="text-xs text-gray-500">${item.location || 'Library location'}</p>
                                    </div>
                                    <span class="text-teal-600 text-xs font-semibold">Library</span>
                                </div>
                            </a>`;
                            typeSuggestions.appendChild(li);
                        });
                        typeSuggestions.classList.remove('hidden');
                    } else if (!hasResults) {
                        typeSuggestions.classList.add('hidden');
                    }
                });
        }

        if (!hasResults && query.length < 2) {
            typeSuggestions.classList.add('hidden');
        }
    });

    // Hide autocomplete when clicking outside
    document.addEventListener("click", function(e){
        if (!typeInput.contains(e.target) && !typeSuggestions.contains(e.target)) {
            typeSuggestions.classList.add("hidden");
        }
    });

    // State & District Dropdown Logic
    const stateSelect = document.getElementById('stateSelect');
    const districtSelect = document.getElementById('districtSelect');

    fetch('/api/states')
        .then(res => res.json())
        .then(data => {
            data.forEach(state => {
                const opt = document.createElement('option');
                opt.value = state.id;
                opt.textContent = state.name;
                opt.setAttribute('data-slug', state.slug);
                stateSelect.appendChild(opt);
            });
        });

    stateSelect.addEventListener('change', function() {
        const stateId = this.value;
        districtSelect.innerHTML = '<option value="">Select District/City</option>';
        
        if (stateId) {
            districtSelect.disabled = true;
            fetch('/api/districts?state_id=' + stateId)
                .then(res => res.json())
                .then(data => {
                    data.forEach(district => {
                        const opt = document.createElement('option');
                        opt.value = district.id;
                        opt.textContent = district.name;
                        opt.setAttribute('data-slug', district.slug);
                        districtSelect.appendChild(opt);
                    });
                    districtSelect.disabled = false;
                });
        } else {
            districtSelect.disabled = true;
        }
    });

    // Handle Search Form Submission for Location Tab
    const searchForm = document.getElementById('searchHeroForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            // Check which tab is currently active
            const activeTab = document.querySelector('.search-tab-btn.text-teal-600');
            if (activeTab && activeTab.getAttribute('data-tab') === 'tab-location') {
                const stateOpt = stateSelect.options[stateSelect.selectedIndex];
                const districtOpt = districtSelect.options[districtSelect.selectedIndex];
                
                const stateSlug = stateOpt ? stateOpt.getAttribute('data-slug') : null;
                const districtSlug = districtOpt ? districtOpt.getAttribute('data-slug') : null;

                if (districtSlug && districtSelect.value) {
                    e.preventDefault(); // Stop normal form submit
                    window.location.href = '/libraries/district/' + districtSlug;
                } else if (stateSlug && stateSelect.value) {
                    e.preventDefault(); // Stop normal form submit
                    window.location.href = '/libraries/state/' + stateSlug;
                }
                // If neither is selected, it will just submit normally to /search
            }
        });
    }
});
</script>
