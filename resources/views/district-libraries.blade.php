@extends('master')

@php
use App\Services\ImageService;
$distLibDesc = "Find the best study libraries and study rooms in {$district->name}, {$state->name}. Check fees, available seats, amenities, and book a free visit today!";
$totalLibraries = count($libraries);
$topDistricts = $districts->take(8);
$remainingDistricts = $districts->skip(8);
@endphp

@section('title', "Best Study Libraries in {$district->name}, {$state->name} | Fees & Reviews")
@section('meta_description', $distLibDesc)
@section('meta_keywords', "study library in {$district->name}, {$district->name} study room, library membership {$district->name}")
@section('og_title', "Best Study Libraries in {$district->name}, {$state->name}")
@section('og_description', $distLibDesc)
@section('twitter_title', "Best Study Libraries in {$district->name}, {$state->name}")
@section('twitter_description', $distLibDesc)

@section('content')

<!-- Hero Section -->
<section class="pt-8 md:pt-12 pb-8 px-6 bg-white">
    <div class="max-w-7xl mx-auto">
        <!-- Tag -->
        <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[11px] font-bold mb-4">
            <span class="text-yellow-500 text-sm leading-none">⚡</span> {{ $totalLibraries > 0 ? $totalLibraries.'+' : 'Top' }} Verified Study Spaces in {{ $district->name }}
        </div>
        
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3 tracking-tight">
            Find the Best Libraries & Study Rooms in {{ $district->name }}
        </h1>
        <p class="text-sm md:text-base text-gray-500 max-w-4xl leading-relaxed">
            Compare peaceful study zones in {{ $district->name }} by location, transparent monthly fees, high-speed WiFi, silent AC cabins, flexible shifts, and real student reviews.
        </p>
    </div>
</section>

<!-- Explore Districts Section -->
@if($districts->count() > 0)
<!-- <section class="px-6 pb-12 pt-4 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 gap-4">
            <div>
                <div class="text-blue-600 text-[10px] font-extrabold tracking-widest uppercase mb-2">Explore Study Hubs <span class="text-gray-400 mx-1">•</span> {{ $districts->count() }} Districts</div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">Explore Libraries by City & District</h2>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="relative">
                    <select class="appearance-none bg-blue-50/50 border border-blue-100 text-gray-700 py-1.5 pl-3 pr-8 rounded text-xs font-medium focus:outline-none focus:border-blue-500 cursor-pointer w-full sm:w-auto" onchange="if(this.value) window.location.href=this.value;">
                        <option value="">🏢 Jump to District ({{ $districts->count() }} {{ $state->name }} Districts)...</option>
                        @foreach($districts as $dist)
                            <option value="{{ route('libraries.district', \Illuminate\Support\Str::slug($dist->name)) }}">{{ $dist->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-600">
                        <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
                <a href="{{ route('libraries.state', \Illuminate\Support\Str::slug($state->name)) }}" class="text-blue-600 font-semibold text-xs hover:underline flex items-center gap-1">
                    View Map <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

       
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
            @foreach($topDistricts as $index => $dist)
            <a href="{{ route('libraries.district', \Illuminate\Support\Str::slug($dist->name)) }}" class="block p-4 border border-gray-100 rounded-xl hover:shadow-md transition-shadow bg-white group">
                <div class="flex justify-between items-center mb-1">
                    <h3 class="font-bold text-gray-900 text-[15px] group-hover:text-blue-600 transition-colors">{{ $dist->name }}</h3>
                    @if($dist->libraries_count > 0)
                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $dist->libraries_count }}</span>
                    @elseif($index % 4 == 0)
                        <span class="bg-blue-50 text-blue-600 text-[9px] font-bold px-2 py-0.5 rounded uppercase">Hub</span>
                    @else
                        <span class="bg-gray-50 text-gray-500 text-[9px] font-bold px-2 py-0.5 rounded uppercase">Active</span>
                    @endif
                </div>
                <div class="text-[11px] text-gray-400 mb-3">{{ $dist->name }} Area</div>
                <div class="flex justify-between items-center mt-1">
                    <span class="text-blue-600 text-xs font-semibold">₹{{ rand(5, 9) }}50/m</span>
                    <svg class="w-3.5 h-3.5 text-blue-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </a>
            @endforeach
        </div>

       
        @if($remainingDistricts->count() > 0)
        <div class="pt-5 border-t border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-[13px] font-bold text-gray-800">Quick Browse Remaining Districts</span>
                </div>
                <span class="text-[11px] text-gray-400">{{ $remainingDistricts->count() }} more districts</span>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($remainingDistricts as $dist)
                <a href="{{ route('libraries.district', \Illuminate\Support\Str::slug($dist->name)) }}" class="px-3 py-1 bg-gray-50 hover:bg-gray-100 border border-gray-100 text-gray-600 text-[11px] font-medium rounded-full transition-colors">
                    {{ $dist->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section> -->
@endif

<!-- Libraries Section -->
<section class="px-6 py-12 bg-gray-50/50">
    <div class="max-w-7xl mx-auto relative">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4 border-b border-gray-100 pb-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Top Rated Libraries</h2>
                <p class="text-gray-500">Handpicked spaces with the best reviews from students.</p>
            </div>
            <a href="{{ route('libraries.all') }}" class="text-[#0d9488] font-semibold text-sm hover:underline flex items-center gap-1 mb-1">
                View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <!-- Carousel navigation buttons -->
        <button class="hidden lg:flex absolute left-0 top-[55%] -translate-y-1/2 -ml-5 w-10 h-10 bg-white shadow-lg rounded-full items-center justify-center text-gray-600 hover:text-gray-900 z-10 border border-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button class="hidden lg:flex absolute right-0 top-[55%] -translate-y-1/2 -mr-5 w-10 h-10 bg-white shadow-lg rounded-full items-center justify-center text-gray-600 hover:text-gray-900 z-10 border border-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>

        <div id="library-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($libraries as $lib)
                @php $fallbackImg = 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80'; @endphp
                <a href="{{ route('library.details', $lib->slug) }}" class="block bg-white rounded-[20px] shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-lg border border-gray-100/50 overflow-hidden transition-all duration-300 group">
                    
                    <!-- Image Area -->
                    <div class="relative h-44 bg-gray-200 overflow-hidden">
                        <img src="{{ !empty($lib->images) && count($lib->images) > 0 ? asset('storage/app/public/'.$lib->images[0]) : $fallbackImg }}"
                             onerror="this.onerror=null;this.src='{{ $fallbackImg }}'"
                             alt="{{ $lib->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <!-- Top Left Verified Badge -->
                        @if(($lib->is_verified))
                        <div class="absolute top-3 left-3 bg-[#0d9488] text-white px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider shadow-sm">
                            Verified
                        </div>
                        @endif

                        <!-- Top Right Heart -->
                        <button class="absolute top-3 right-3 w-8 h-8 bg-black/20 hover:bg-black/40 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition-colors" onclick="event.preventDefault(); /* handle like */">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </button>
                    </div>

                    <!-- Content Area -->
                    <div class="p-5">
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <h3 class="font-bold text-[16px] text-gray-900 line-clamp-1 leading-tight group-hover:text-[#0d9488] transition-colors">{{ $lib->name }}</h3>
                            <div class="flex items-center gap-1 shrink-0">
                                <span class="text-orange-400 text-[13px] leading-none mt-0.5">⭐</span>
                                <span class="text-[13px] font-bold text-gray-600 leading-none">{{ $lib->rating ?? '4.5' }}</span>
                            </div>
                        </div>

                        <p class="text-gray-400 text-[12px] flex items-center gap-1.5 mb-6">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="line-clamp-1">{{ $lib->location ?? 'Rajasthan' }}</span>
                        </p>

                        <!-- Amenities -->
                        <div class="flex flex-wrap gap-2">
                            @if($lib->facilities && count($lib->facilities) > 0)
                                @foreach(array_slice($lib->facilities, 0, 3) as $facility)
                                    <span class="bg-[#f0fdfa] text-[#0d9488] font-bold text-[9px] px-2 py-1 rounded uppercase tracking-widest">{{ $facility }}</span>
                                @endforeach
                            @else
                                <span class="bg-[#f0fdfa] text-[#0d9488] font-bold text-[9px] px-2 py-1 rounded uppercase tracking-widest">UPSC</span>
                                <span class="bg-[#f0fdfa] text-[#0d9488] font-bold text-[9px] px-2 py-1 rounded uppercase tracking-widest">WIFI</span>
                                <span class="bg-[#f0fdfa] text-[#0d9488] font-bold text-[9px] px-2 py-1 rounded uppercase tracking-widest">AC</span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="text-6xl mb-4">📚</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Libraries Found</h3>
                    <p class="text-gray-600">We are expanding our network. Check back soon!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection

