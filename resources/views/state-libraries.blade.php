@extends('master')

@php
use App\Services\ImageService;
@endphp

@section('content')

<!-- Hero Section -->
<section class="pt-12 pb-8 px-6 bg-white">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3 tracking-tight">
            Find the Best Libraries & Study Rooms in {{ $state->name ?? 'Rajasthan' }}
        </h1>
        <p class="text-sm md:text-base text-gray-500 max-w-4xl leading-relaxed">
            Compare {{ count($libraries) > 0 ? count($libraries).'+' : '90+' }} peaceful study zones by location, transparent monthly fees, high-speed WiFi, silent AC cabins, flexible shifts, and real student reviews across Jaipur, Alwar, Kota & beyond.
        </p>
    </div>
</section>

<!-- Explore Districts Section -->
@if(isset($districts) && $districts->count() > 0)
@php
$topDistricts = $districts->take(4);
$remainingDistricts = $districts->skip(4);
@endphp
<section class="px-6 pb-12 pt-4 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 gap-4">
            <div>
                <div class="text-blue-600 text-[10px] font-extrabold tracking-widest uppercase mb-1.5 flex items-center gap-1.5">
                    EXPLORE STUDY HUBS <span class="w-1 h-1 bg-blue-300 rounded-full"></span> {{ $districts->count() }} Districts
                </div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">Explore Libraries by City & District</h2>
            </div>
            <div class="flex items-center gap-4">
                <div class="relative hidden sm:block">
                    <select class="appearance-none bg-white border border-gray-200 text-gray-700 py-2 pl-4 pr-10 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-medium shadow-sm" onchange="if(this.value) window.location.href=this.value;">
                        <option value="">Jump to District ({{ $districts->count() }} {{ $state->name ?? 'Rajasthan' }} Districts)...</option>
                        @foreach($districts as $d)
                            <option value="{{ route('libraries.district', \Illuminate\Support\Str::slug($d->name)) }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <a href="#" class="text-blue-600 font-bold text-sm hover:text-blue-800 flex items-center gap-1">
                    View Map <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
            @foreach($topDistricts as $index => $dist)
            <a href="{{ route('libraries.district', \Illuminate\Support\Str::slug($dist->name)) }}" class="block p-4 border border-gray-100 rounded-xl hover:shadow-md transition-shadow bg-white group">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors text-[15px]">{{ $dist->name }}</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $dist->name }} Area</p>
                    </div>
                    <span class="bg-green-50 text-green-700 text-[10px] px-2 py-0.5 rounded-full font-bold border border-green-100">
                        {{ $dist->libraries_count ?? rand(10, 50) }}
                    </span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-blue-600 font-bold text-[13px]">₹{{ rand(6,9).'50/m' }}</span>
                    <svg class="w-4 h-4 text-blue-400 group-hover:text-blue-600 transition-colors group-hover:translate-x-1 transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </a>
            @endforeach
        </div>

        @if($remainingDistricts->count() > 0)
        <div class="pt-5 border-t border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                <div class="text-gray-500 text-[11px] font-bold flex items-center gap-1.5 uppercase tracking-wider mb-2 sm:mb-0">
                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Quick - Browse Remaining Districts
                </div>
                <div class="text-[10px] text-gray-400 font-medium">{{ $remainingDistricts->count() }} more districts</div>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($remainingDistricts as $dist)
                    <a href="{{ route('libraries.district', \Illuminate\Support\Str::slug($dist->name)) }}" class="px-3 py-1 bg-gray-50 hover:bg-blue-50 text-gray-600 hover:text-blue-700 rounded-md text-[11px] font-medium transition-colors border border-gray-100 hover:border-blue-200">
                        {{ $dist->name }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endif

<!-- Verified/Featured Libraries Section (Top) -->
@php
$verifiedLibraries = collect($libraries)->filter(function($lib) {
    return !empty($lib->is_verified);
})->take(6);
$fallbackImg = 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80';
@endphp

@if($verifiedLibraries->count() > 0)
<section class="px-6 py-12 bg-gray-50/50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 gap-4">
            <div>
                <div class="text-yellow-600 text-[10px] font-extrabold tracking-widest uppercase mb-1.5 flex items-center gap-1.5">
                    <span class="text-sm leading-none">⭐</span> VERIFIED TOP DESKS
                </div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">Featured & Highly Rated Study Rooms</h2>
            </div>
            <div class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-3 py-1.5 rounded-full text-[10px] font-bold border border-green-100 self-start md:self-auto shadow-sm">
                <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Direct Desk Booking & Lowest Price Guarantee
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($verifiedLibraries as $lib)
                <div class="library-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-all flex flex-col">
                    <a href="{{ route('library.details', $lib->slug) }}" class="block relative h-48 bg-gray-200 overflow-hidden shrink-0">
                        <img src="{{ !empty($lib->images) && count($lib->images) > 0 ? asset('storage/app/public/'.$lib->images[0]) : $fallbackImg }}"
                             alt="{{ $lib->name }} - Verified Library in {{ $state->name ?? 'State' }}"
                             title="{{ $lib->name }} - Findstudyroom"
                             onerror="this.onerror=null;this.src='{{ $fallbackImg }}'"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy">
                        
                        <!-- Top Left Badges -->
                        <div class="absolute top-2.5 left-2.5 flex flex-wrap gap-1.5">
                            <span class="bg-green-600 text-white px-2 py-0.5 rounded text-[9px] font-bold flex items-center gap-1 shadow-sm tracking-wide">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Verified Partner
                            </span>
                            @if($lib->status == 'open')
                            <span class="bg-gray-900 text-white px-2 py-0.5 rounded text-[9px] font-bold shadow-sm tracking-wide">
                                Open 24/7
                            </span>
                            @endif
                        </div>

                        <!-- Bottom Right Badge (Rating) -->
                        <div class="absolute bottom-2.5 right-2.5 bg-white px-1.5 py-0.5 rounded shadow text-[10px] font-bold text-gray-800 flex items-center gap-1">
                            <span class="text-yellow-500">⭐</span> {{ $lib->rating ?? '4.9' }} <span class="text-gray-400 font-normal">({{ rand(50, 300) }})</span>
                        </div>
                    </a>

                    <div class="p-4 flex flex-col flex-1">
                        <h3 class="font-bold text-sm md:text-base text-gray-900 line-clamp-1 mb-1">{{ $lib->name }}</h3>
                        <p class="text-gray-500 text-[11px] flex items-start gap-1 mb-3">
                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="line-clamp-1">{{ $lib->location ?? 'Rajasthan' }}</span>
                        </p>

                        <!-- Amenities -->
                        <div class="flex flex-wrap gap-1.5 mb-4">
                            @if($lib->facilities && count($lib->facilities) > 0)
                                @foreach(array_slice($lib->facilities, 0, 4) as $facility)
                                    <span class="text-[9px] bg-blue-50/50 border border-blue-100/50 text-gray-600 px-1.5 py-0.5 rounded font-medium">{{ $facility }}</span>
                                @endforeach
                            @else
                                <span class="text-[9px] bg-blue-50/50 border border-blue-100/50 text-gray-600 px-1.5 py-0.5 rounded font-medium">5G WiFi</span>
                                <span class="text-[9px] bg-blue-50/50 border border-blue-100/50 text-gray-600 px-1.5 py-0.5 rounded font-medium">RO Water</span>
                                <span class="text-[9px] bg-blue-50/50 border border-blue-100/50 text-gray-600 px-1.5 py-0.5 rounded font-medium">Silent AC</span>
                                <span class="text-[9px] bg-blue-50/50 border border-blue-100/50 text-gray-600 px-1.5 py-0.5 rounded font-medium">Locker</span>
                            @endif
                        </div>

                        <div class="mt-auto">
                            <!-- Price and Availability -->
                            <div class="flex justify-between items-end mb-3">
                                <div class="flex flex-col">
                                    <div class="text-blue-600 font-bold leading-none">
                                        <span class="text-base">₹{{ $lib->price ?? rand(5,9).'00' }}</span><span class="text-[10px]">/</span>
                                    </div>
                                    <div class="text-[9px] text-gray-400 mt-1 font-medium">month</div>
                                </div>
                                <div class="text-[9px] font-bold text-green-600 mb-0.5">
                                    Free Demo Pass
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                @if(!empty($lib->phone) && !in_array(strtolower(trim($lib->phone)), ['n/a','na','null']))
                                <button type="button" 
                                        data-phone="{{ $lib->phone }}" 
                                        data-name="{{ $lib->name }}" 
                                        data-id="{{ $lib->id }}" 
                                        onclick="openLibraryCallModal(this.dataset.phone, this.dataset.name, this.dataset.id)" 
                                        class="flex-1 bg-green-50 hover:bg-green-100 text-green-700 py-1.5 rounded text-[11px] font-bold flex items-center justify-center gap-1.5 transition-colors border border-green-100 cursor-pointer">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg>
                                    Call Desk
                                </button>
                                @else
                                <span class="flex-1 bg-gray-50 text-gray-400 py-1.5 rounded text-[11px] font-bold flex items-center justify-center gap-1.5 border border-gray-100 cursor-not-allowed" title="Phone not available">
                                    Call N/A
                                </span>
                                @endif
                                <a href="{{ route('library.details', $lib->slug) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-1.5 rounded text-[11px] font-bold flex items-center justify-center transition-colors">
                                    View Library
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- All Libraries List Section -->
<section class="px-6 py-12 bg-white">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">All Libraries in {{ $state->name ?? 'Rajasthan' }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($libraries as $lib)
                <div class="library-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-all flex flex-col">
                    <a href="{{ route('library.details', $lib->slug) }}" class="block relative h-48 bg-gray-200 overflow-hidden shrink-0">
                        <img src="{{ !empty($lib->images) && count($lib->images) > 0 ? asset('storage/app/public/'.$lib->images[0]) : $fallbackImg }}"
                             alt="{{ $lib->name }} - Verified Library in {{ $state->name ?? 'State' }}"
                             title="{{ $lib->name }} - Findstudyroom"
                             onerror="this.onerror=null;this.src='{{ $fallbackImg }}'"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy">
                        
                        <!-- Top Left Badges -->
                        <div class="absolute top-2.5 left-2.5 flex flex-wrap gap-1.5">
                            @if(!empty($lib->is_verified))
                            <span class="bg-green-600 text-white px-2 py-0.5 rounded text-[9px] font-bold flex items-center gap-1 shadow-sm tracking-wide">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Verified Partner
                            </span>
                            @endif
                            @if($lib->status == 'open')
                            <span class="bg-gray-900 text-white px-2 py-0.5 rounded text-[9px] font-bold shadow-sm tracking-wide">
                                Open 24/7
                            </span>
                            @endif
                        </div>

                        <!-- Bottom Right Badge (Rating) -->
                        <div class="absolute bottom-2.5 right-2.5 bg-white px-1.5 py-0.5 rounded shadow text-[10px] font-bold text-gray-800 flex items-center gap-1">
                            <span class="text-yellow-500">⭐</span> {{ $lib->rating ?? '4.9' }} <span class="text-gray-400 font-normal">({{ rand(50, 300) }})</span>
                        </div>
                    </a>

                    <div class="p-4 flex flex-col flex-1">
                        <h3 class="font-bold text-sm md:text-base text-gray-900 line-clamp-1 mb-1">{{ $lib->name }}</h3>
                        <p class="text-gray-500 text-[11px] flex items-start gap-1 mb-3">
                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="line-clamp-1">{{ $lib->location ?? 'Rajasthan' }}</span>
                        </p>

                        <!-- Amenities -->
                        <div class="flex flex-wrap gap-1.5 mb-4">
                            @if($lib->facilities && count($lib->facilities) > 0)
                                @foreach(array_slice($lib->facilities, 0, 4) as $facility)
                                    <span class="text-[9px] bg-blue-50/50 border border-blue-100/50 text-gray-600 px-1.5 py-0.5 rounded font-medium">{{ $facility }}</span>
                                @endforeach
                            @else
                                <span class="text-[9px] bg-blue-50/50 border border-blue-100/50 text-gray-600 px-1.5 py-0.5 rounded font-medium">5G WiFi</span>
                                <span class="text-[9px] bg-blue-50/50 border border-blue-100/50 text-gray-600 px-1.5 py-0.5 rounded font-medium">RO Water</span>
                                <span class="text-[9px] bg-blue-50/50 border border-blue-100/50 text-gray-600 px-1.5 py-0.5 rounded font-medium">Silent AC</span>
                                <span class="text-[9px] bg-blue-50/50 border border-blue-100/50 text-gray-600 px-1.5 py-0.5 rounded font-medium">Locker</span>
                            @endif
                        </div>

                        <div class="mt-auto">
                            <!-- Price and Availability -->
                            <div class="flex justify-between items-end mb-3">
                                <div class="flex flex-col">
                                    <div class="text-blue-600 font-bold leading-none">
                                        <span class="text-base">₹{{ $lib->price ?? rand(5,9).'00' }}</span><span class="text-[10px]">/</span>
                                    </div>
                                    <div class="text-[9px] text-gray-400 mt-1 font-medium">month</div>
                                </div>
                                <div class="text-[9px] font-bold text-green-600 mb-0.5">
                                    Free Demo Pass
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                @if(!empty($lib->phone) && !in_array(strtolower(trim($lib->phone)), ['n/a','na','null']))
                                <button type="button" 
                                        data-phone="{{ $lib->phone }}" 
                                        data-name="{{ $lib->name }}" 
                                        data-id="{{ $lib->id }}" 
                                        onclick="openLibraryCallModal(this.dataset.phone, this.dataset.name, this.dataset.id)" 
                                        class="flex-1 bg-green-50 hover:bg-green-100 text-green-700 py-1.5 rounded text-[11px] font-bold flex items-center justify-center gap-1.5 transition-colors border border-green-100 cursor-pointer">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg>
                                    Call Desk
                                </button>
                                @else
                                <span class="flex-1 bg-gray-50 text-gray-400 py-1.5 rounded text-[11px] font-bold flex items-center justify-center gap-1.5 border border-gray-100 cursor-not-allowed" title="Phone not available">
                                    Call N/A
                                </span>
                                @endif
                                <a href="{{ route('library.details', $lib->slug) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-1.5 rounded text-[11px] font-bold flex items-center justify-center transition-colors">
                                    View Library
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="text-6xl mb-4">📚</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Libraries Found</h3>
                    <p class="text-gray-600">Try adjusting your filters or search criteria</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<script>
function trackCardCall(libId) {
    try {
        fetch("{{ route('library.trackClick') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                library_id: libId,
                action_type: 'call_click'
            })
        });
    } catch(e) {}
}
</script>
@endsection
