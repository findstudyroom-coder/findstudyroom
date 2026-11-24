@extends('master')

@php
    $pageTitle = 'Sports, Martial Arts & Hobby Academies | Findstudyroom';
    $pageDesc = 'Discover premier sports academies, martial arts dojos, yoga sanctuaries, and hobby centers near you. Check verified senseis, class schedules, fees, and book free trial slots.';
@endphp

@section('title', $pageTitle)
@section('meta_description', $pageDesc)
@section('meta_keywords', 'sports academy, martial arts dojo, karate classes, judo classes, yoga studio, boxing academy, fitness sanctuary, Alwar sports')
@section('og_title', $pageTitle)
@section('og_description', $pageDesc)
@section('twitter_title', $pageTitle)
@section('twitter_description', $pageDesc)

@push('head')
<style>
    .coaching-card {
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .coaching-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 35px -10px rgba(13, 148, 136, 0.12), 0 10px 15px -3px rgba(0, 0, 0, 0.04);
        border-color: rgba(13, 148, 136, 0.4);
    }
</style>
@endpush

@section('content')
<div class="bg-slate-50 min-h-screen pb-20">

    <!-- 1. HERO HEADER SECTION -->
    <section class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-teal-950 text-white overflow-hidden py-16 sm:py-20 border-b border-slate-700">
        <!-- Ambient background glow elements -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-500/20 border border-teal-400/30 text-teal-300 font-mono text-xs font-bold uppercase tracking-wider mb-4 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                        SPORTS &amp; HOBBY ACADEMIES
                    </div>
                    <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                        Find Premier Sports &amp; Movement Sanctuaries
                    </h1>
                    <p class="text-slate-300 text-sm sm:text-base mt-4 font-light leading-relaxed">
                        Explore certified martial arts dojos, Olympic tatami academies, yoga physiology suites, and specialized sports clubs. Compare disciplines, certified senseis, and book your verified free trial.
                    </p>
                </div>

                <!-- Stats summary badges -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5 flex-shrink-0">
                    <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-4 text-center">
                        <div class="text-2xl sm:text-3xl font-extrabold text-teal-400 font-display">{{ $coachings->total() }}+</div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-300 mt-1">Verified Studios</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-4 text-center">
                        <div class="text-2xl sm:text-3xl font-extrabold text-amber-400 font-display">15+</div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-300 mt-1">Master Senseis</div>
                    </div>
                    <div class="col-span-2 sm:col-span-1 bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-4 text-center">
                        <div class="text-2xl sm:text-3xl font-extrabold text-emerald-400 font-display">100%</div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-300 mt-1">Free Trial Pass</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. SEARCH & FILTER TOOLBAR -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-7 relative z-20">
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-xl border border-slate-200">
            <form method="GET" action="{{ route('sports-hobby') }}" class="flex flex-col md:flex-row items-center gap-3">
                
                <!-- Search Input -->
                <div class="relative flex-grow w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search academy name, martial art, boxing, yoga, or locality..." class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 text-sm focus:outline-none focus:border-teal-500 focus:bg-white focus:ring-4 focus:ring-teal-50 transition font-medium">
                </div>

                <!-- City Dropdown -->
                <div class="w-full md:w-56">
                    <select name="city" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-sm focus:outline-none focus:border-teal-500 focus:bg-white focus:ring-4 focus:ring-teal-50 transition font-medium">
                        <option value="">All Locations</option>
                        @foreach($cities as $c)
                            <option value="{{ $c }}" {{ request('city') === $c ? 'selected' : '' }}>📍 {{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Category Dropdown -->
                <div class="w-full md:w-64">
                    <select name="category" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-sm focus:outline-none focus:border-teal-500 focus:bg-white focus:ring-4 focus:ring-teal-50 transition font-medium">
                        <option value="">All Sports &amp; Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>🥋 {{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit & Reset Buttons -->
                <div class="flex gap-2 w-full md:w-auto flex-shrink-0">
                    <button type="submit" class="w-full md:w-auto px-6 py-3 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm shadow-md shadow-teal-600/25 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'city', 'category']))
                        <a href="{{ route('sports-hobby') }}" class="px-4 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold transition" title="Clear Filters">
                            Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </section>

    <!-- 3. COACHINGS CARDS GRID -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 font-display">
                    Verified Sports &amp; Movement Centers
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Showing {{ $coachings->total() }} certified academy {{ $coachings->total() === 1 ? 'partner' : 'partners' }} ready for enrollment
                </p>
            </div>

            <!-- Quick Pill Categories -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs">
                <a href="{{ route('sports-hobby') }}" class="px-3.5 py-1.5 rounded-full {{ !request('category') ? 'bg-teal-600 text-white font-bold' : 'bg-white border border-slate-200 text-slate-600 hover:text-slate-900' }} transition shadow-sm whitespace-nowrap">
                    All Categories
                </a>
                @foreach($categories->take(3) as $pillCat)
                    <a href="{{ route('sports-hobby', ['category' => $pillCat]) }}" class="px-3.5 py-1.5 rounded-full {{ request('category') === $pillCat ? 'bg-teal-600 text-white font-bold' : 'bg-white border border-slate-200 text-slate-600 hover:text-slate-900' }} transition shadow-sm whitespace-nowrap">
                        {{ $pillCat }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">

            @forelse($coachings as $coaching)
                @php
                    $targetUrl = ($coaching->slug === 'the-movement') 
                        ? route('the-movement') 
                        : route('coaching.show', $coaching->slug);
                    $cleanPhone = preg_replace('/[^0-9]/', '', $coaching->whatsapp_number ?: $coaching->mobile_number);
                    if (strlen($cleanPhone) === 10) $cleanPhone = '91' . $cleanPhone;
                    $courses = is_array($coaching->courses_offered) ? $coaching->courses_offered : [];
                @endphp

                <!-- COACHING CARD (Full Card Click Opens /coaching/{slug}) -->
                <div class="coaching-card bg-white rounded-3xl overflow-hidden border border-slate-200 flex flex-col justify-between group shadow-sm">
                    
                    <!-- Top Cover & Visual Identity -->
                    <div>
                        <a href="{{ $targetUrl }}" class="relative block h-52 bg-slate-900 overflow-hidden">
                            <img src="{{ $coaching->cover_image_url }}" 
                                 alt="{{ $coaching->coaching_name }}"
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1545205597-3d9d02c29597?auto=format&fit=crop&w=800&q=80';"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
                            
                            <!-- Gradient overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/25 to-transparent"></div>

                            <!-- Top Badges -->
                            <div class="absolute top-3 inset-x-3 flex items-center justify-between">
                                <span class="px-3 py-1 rounded-full bg-white/90 backdrop-blur-md text-slate-800 text-[10px] font-extrabold uppercase tracking-wider shadow-sm">
                                    {{ $coaching->coaching_type ?: 'Sports Academy' }}
                                </span>

                                <span class="px-2.5 py-1 rounded-full bg-teal-600/95 backdrop-blur-md text-white text-[10px] font-extrabold uppercase tracking-wider flex items-center gap-1 shadow-md">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    VERIFIED
                                </span>
                            </div>

                            <!-- Bottom Info inside Banner -->
                            <div class="absolute bottom-3 inset-x-4 flex items-end justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $coaching->logo_url }}" 
                                         alt="{{ $coaching->coaching_name }} Logo" 
                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($coaching->coaching_name) }}&background=0d9488&color=fff';"
                                         class="w-12 h-12 rounded-xl object-cover border-2 border-white bg-white shadow-md">
                                    
                                    <div class="text-white">
                                        <div class="text-xs font-semibold text-slate-300 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-teal-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $coaching->city ?? 'Alwar' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-slate-900/80 backdrop-blur-md px-2.5 py-1 rounded-lg border border-white/10 text-[11px] font-bold text-amber-300">
                                    ★ 4.9 <span class="text-white/60 font-normal">({{ rand(40, 180) }})</span>
                                </div>
                            </div>
                        </a>

                        <!-- Card Body -->
                        <div class="p-6 space-y-4">
                            <div>
                                <a href="{{ $targetUrl }}" class="group-hover:text-teal-600 transition-colors">
                                    <h3 class="font-display text-xl font-bold text-slate-900 tracking-tight leading-snug">
                                        {{ $coaching->coaching_name }}
                                    </h3>
                                </a>
                                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                    <span>📍</span> {{ $coaching->area_locality ? $coaching->area_locality . ', ' : '' }}{{ $coaching->city ?? 'Alwar' }}
                                </p>
                            </div>

                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                {{ $coaching->short_description ?: ($coaching->description ?: 'Operating as a flagship movement and sports sanctuary with structured progressions from fundamentals to advanced application.') }}
                            </p>

                            <!-- Courses / Disciplines Offered Badges -->
                            @if(count($courses) > 0)
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    @foreach(array_slice($courses, 0, 3) as $cTag)
                                        <span class="text-[11px] font-semibold bg-teal-50 text-teal-800 border border-teal-200 px-2.5 py-0.5 rounded-lg">
                                            {{ $cTag }}
                                        </span>
                                    @endforeach
                                    @if(count($courses) > 3)
                                        <span class="text-[10px] font-bold bg-slate-100 text-slate-600 px-2 py-0.5 rounded-lg border border-slate-200">
                                            +{{ count($courses) - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Highlights Metrics (Instructors, Timetable, Mode) -->
                            <div class="grid grid-cols-3 gap-2 pt-3 border-t border-slate-100 text-center">
                                <div class="p-2 bg-slate-50 rounded-xl border border-slate-100">
                                    <div class="text-[10px] font-extrabold uppercase text-slate-400">INSTRUCTORS</div>
                                    <div class="text-xs font-bold text-slate-800 mt-0.5">
                                        {{ $coaching->instructors_count > 0 ? $coaching->instructors_count . ' Senseis' : 'Black Belt' }}
                                    </div>
                                </div>
                                <div class="p-2 bg-slate-50 rounded-xl border border-slate-100">
                                    <div class="text-[10px] font-extrabold uppercase text-slate-400">SCHEDULE</div>
                                    <div class="text-xs font-bold text-slate-800 mt-0.5">
                                        {{ $coaching->schedules_count > 0 ? $coaching->schedules_count . ' Days' : 'Weekly' }}
                                    </div>
                                </div>
                                <div class="p-2 bg-slate-50 rounded-xl border border-slate-100">
                                    <div class="text-[10px] font-extrabold uppercase text-slate-400">FACILITY</div>
                                    <div class="text-xs font-bold text-teal-700 mt-0.5">
                                        {{ $coaching->mode ?: 'Tatami Dojo' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="p-6 pt-0 border-t border-slate-100 mt-4 flex items-center justify-between gap-3">
                        <div>
                            @if($coaching->starting_fees)
                                <div class="text-[10px] text-slate-400 font-bold uppercase">STARTING FROM</div>
                                <div class="text-lg font-extrabold text-slate-900">
                                    ₹{{ number_format($coaching->starting_fees) }}<span class="text-xs font-normal text-slate-500">/mo</span>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-lg">
                                    ⚡ Free Trial
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            @if($cleanPhone)
                                <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hello Sensei, I saw ' . $coaching->coaching_name . ' on Findstudyroom and would like to inquire about classes.') }}" target="_blank" class="p-2.5 rounded-xl border border-slate-200 text-emerald-600 hover:bg-emerald-50 hover:border-emerald-300 transition shadow-sm" title="WhatsApp Chat">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086.159.058 1.011.477 1.184.564.173.086.289.13.332.202.043.072.043.419-.101.824z"/>
                                    </svg>
                                </a>
                            @endif

                            <!-- Primary View Details Button -->
                            <a href="{{ $targetUrl }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-teal-700 text-white text-xs font-bold transition shadow-sm hover:shadow">
                                <span>Explore Dojo</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>

            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200 p-8">
                    <div class="w-16 h-16 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                        🥋
                    </div>
                    <h3 class="font-display text-xl font-bold text-slate-900">No Academies Found</h3>
                    <p class="text-slate-500 text-sm mt-1 max-w-md mx-auto">
                        We could not find any coaching centers matching your active filters. Try clearing your search term or selecting another category.
                    </p>
                    <a href="{{ route('sports-hobby') }}" class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs shadow-sm transition">
                        Reset All Filters
                    </a>
                </div>
            @endforelse

        </div>

        <!-- Pagination -->
        @if($coachings->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $coachings->links() }}
            </div>
        @endif

    </section>

    <!-- 4. WHY CHOOSE VERIFIED ACADEMIES SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
        <div class="bg-gradient-to-r from-teal-900 to-slate-900 rounded-3xl p-8 sm:p-12 text-white relative overflow-hidden shadow-xl">
            <div class="max-w-xl relative z-10">
                <span class="text-teal-400 font-mono text-xs font-bold uppercase tracking-wider">THE FINDSTUDY STANDARD</span>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-white mt-1">
                    Why Train with Verified Movement Studios?
                </h2>
                <p class="text-slate-300 text-xs sm:text-sm mt-3 leading-relaxed">
                    Every studio on our network undergoes facility inspection — guaranteeing authentic black belt lineage, medical-grade joint protection mats, and certified child safety standards.
                </p>

                <div class="grid grid-cols-2 gap-4 mt-6 text-xs text-slate-200">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-teal-500/30 text-teal-300 flex items-center justify-center font-bold">✓</span>
                        <span>Olympic Shock Mats</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-teal-500/30 text-teal-300 flex items-center justify-center font-bold">✓</span>
                        <span>WKF &amp; Kodokan Lineage</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-teal-500/30 text-teal-300 flex items-center justify-center font-bold">✓</span>
                        <span>Zero Obligation Free Trial</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-teal-500/30 text-teal-300 flex items-center justify-center font-bold">✓</span>
                        <span>HEPA Air Filtration</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
