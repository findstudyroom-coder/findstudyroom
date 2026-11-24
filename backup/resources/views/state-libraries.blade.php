@extends('master')

@php
use App\Services\ImageService;
$stateLibDesc = "Explore the best study libraries and reading rooms across {$state->name}. Find top-rated study spaces, check monthly fees, verify amenities, and book a free trial visit!";
@endphp

@section('title', "Best Study Libraries in {$state->name} - Book Top Study Rooms | Findstudyroom")
@section('meta_description', $stateLibDesc)
@section('meta_keywords', "study library in {$state->name}, {$state->name} study room, library membership {$state->name}")
@section('og_title', "Best Study Libraries in {$state->name} - Book Top Study Rooms")
@section('og_description', $stateLibDesc)
@section('twitter_title', "Best Study Libraries in {$state->name} - Book Top Study Rooms")
@section('twitter_description', $stateLibDesc)

@section('content')

<!-- Hero Section -->
<section class="pt-28 pb-8 px-6 bg-gradient-to-r from-teal-50 to-teal-50">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('libraries.all') }}" class="text-teal-600 hover:text-teal-700 font-semibold flex items-center gap-1">
                ← Back to All Libraries
            </a>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-2">📍 Libraries in {{ $state->name }}</h1>
        <p class="text-lg text-gray-600">Browse all {{ count($libraries) }} verified study rooms and libraries in {{ $state->name }}</p>
    </div>
</section>

<!-- Other States Quick Access -->
<section class="px-6 py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto">
        <h3 class="text-lg font-bold text-gray-900 mb-4">🗺️ Other States</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 overflow-x-auto pb-2">
            @foreach($states as $st)
                <a href="{{ route('libraries.state', \Illuminate\Support\Str::slug($st->name)) }}" 
                   class="state-link block p-4 rounded-lg text-center transition {{ $st->id == $state->id ? 'bg-teal-600 text-white font-bold' : 'bg-gray-100 text-gray-900 hover:bg-gray-200' }}">
                    <div class="font-semibold">{{ $st->name }}</div>
                    <div class="text-xs {{ $st->id == $state->id ? 'opacity-90' : 'text-gray-600' }} mt-1">{{ $st->libraries_count }} libraries</div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Filters Section -->


<!-- Districts Grid Section -->
@if(count($districts) > 0)
<section class="px-6 py-8 bg-gradient-to-b from-teal-50 to-white border-b">
    <div class="max-w-7xl mx-auto">
        <h3 class="text-lg font-bold text-gray-900 mb-4">📍 Browse by District in {{ $state->name }}</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 overflow-x-auto pb-2">
            @foreach($districts as $district)
                <a href="{{ route('libraries.district', \Illuminate\Support\Str::slug($district->name)) }}" 
                   class="district-card p-4 rounded-lg text-center transition bg-white text-gray-900 border-2 border-gray-200 hover:border-teal-500 hover:shadow-lg">
                    <div class="text-2xl mb-2">📚</div>
                    <div class="font-semibold text-sm line-clamp-2">{{ $district->name }}</div>
                    <div class="text-xs text-gray-600 mt-1">{{ $district->libraries_count }} libraries</div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Libraries Grid Section -->
<section class="px-6 py-12">
    <div class="max-w-7xl mx-auto">
        <div id="library-container" class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($libraries as $lib)
                <div class="library-card bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group" 
                     data-facilities="{{ json_encode($lib->facilities ?? []) }}">
                    <!-- Image Container -->
                    <a href="{{ route('library.details',$lib->slug) }}" class="relative block overflow-hidden h-48 bg-gray-200">
                        <img src="{{ !empty($lib->images) && count($lib->images) > 0 ? asset('storage/app/public/'.$lib->images[0]) : ImageService::getLibraryImage() }}"
                             onerror="this.onerror=null;this.src='{{ ImageService::getLibraryImage() }}'"
                             alt="{{ $lib->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        
                        <!-- Verified Badge -->
                        <div class="absolute top-3 right-3 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                            ✓ Verified
                        </div>

                        <!-- Status Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="{{ $lib->status=='open' ? 'bg-green-500' : 'bg-red-500' }} text-white px-3 py-1 rounded-full text-xs font-semibold">
                                {{ ucfirst($lib->status) }}
                            </span>
                        </div>
                    </a>

                    <!-- Content -->
                    <div class="p-5 space-y-3">
                        <!-- Name & Rating -->
                        <div class="flex justify-between items-start gap-2">
                            <h3 class="font-bold text-lg text-gray-900 line-clamp-2">
                                {{ $lib->name }}
                                @if($lib->is_verified)
                                    <span class="inline-flex items-center justify-center bg-teal-100 text-teal-700 text-[10px] font-bold px-1.5 py-0.5 rounded ml-1 align-middle" title="Verified Premium Library">
                                        <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.403 12.652a3 3 0 000-5.304 3 3 0 00-3.75-3.751 3 3 0 00-5.305 0 3 3 0 00-3.751 3.75 3 3 0 000 5.305 3 3 0 003.75 3.751 3 3 0 005.305 0 3 3 0 003.751-3.75zm-2.546-4.46a.75.75 0 00-1.214-.883l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"></path></svg>
                                        VERIFIED
                                    </span>
                                @endif
                            </h3>
                            <div class="text-right">
                                <div class="text-yellow-500 font-bold">⭐ {{ $lib->rating ?? '4.5' }}</div>
                                <div class="text-xs text-gray-500">{{ rand(50, 200) }} reviews</div>
                            </div>
                        </div>

                        <!-- Location & District -->
                        <div>
                            <p class="text-gray-600 text-sm flex items-center gap-1 mb-1">
                                📍 <span class="line-clamp-1">{{ $lib->location }} - {{ $lib->pincode }}</span>
                            </p>
                            <p class="text-teal-600 text-xs font-semibold">
                                📍 {{ $lib->district->name ?? 'Unknown District' }}
                            </p>
                        </div>

                        <!-- Timing -->
                        <p class="text-gray-700 text-sm font-medium">
                            ⏰ {{ isset($lib->open_time) ? date('h:i A', strtotime($lib->open_time)) : '09:00 AM' }} - 
                            {{ isset($lib->close_time) ? date('h:i A', strtotime($lib->close_time)) : '09:00 PM' }}
                        </p>

                        <!-- Facilities Tags -->
                        @if($lib->facilities && count($lib->facilities) > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach(array_slice($lib->facilities, 0, 2) as $facility)
                                    <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full font-semibold">{{ $facility }}</span>
                                @endforeach
                                @if(count($lib->facilities) > 2)
                                    <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full font-semibold">+{{ count($lib->facilities) - 2 }}</span>
                                @endif
                            </div>
                        @endif

                        <!-- Price & CTA -->
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                            <div>
                                <span class="text-2xl font-bold text-teal-600">{{ $lib->price ? '₹'.$lib->price : 'Price coming soon' }}</span>
                                @if($lib->price)
                                <span class="text-xs text-gray-500">/month</span>
                                @endif
                            </div>
                            <a href="{{ route('library.details',$lib->slug) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                                View Details
                            </a>
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

@endsection

@push('scripts')
<script>
let allLibrariesData = @json($libraries);
let librariesByDistrict = @json($librariesByDistrict);

document.addEventListener('DOMContentLoaded', function() {
    const districtTabs = document.querySelectorAll('.district-tab');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const sortBy = document.getElementById('sortBy');
    const libraryContainer = document.getElementById('library-container');

    // Display all libraries initially
    displayLibraries(allLibrariesData);

    // District tab click handler
    districtTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const district = this.getAttribute('data-district');
            
            // Update active tab
            districtTabs.forEach(t => {
                t.classList.remove('active', 'bg-teal-600', 'text-white');
                t.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            });
            
            this.classList.add('active', 'bg-teal-600', 'text-white');
            this.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            
            // Filter libraries by district
            if (district === 'all') {
                displayLibraries(allLibrariesData);
            } else {
                const filtered = allLibrariesData.filter(lib => (lib.district?.name || 'Unknown') === district);
                displayLibraries(filtered);
            }
        });
    });

    // Filter functionality
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterBtns.forEach(b => {
                b.classList.remove('active', 'bg-teal-600', 'text-white');
                b.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            });
            
            this.classList.add('active', 'bg-teal-600', 'text-white');
            this.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            
            // Filter libraries
            const libraryCards = libraryContainer.querySelectorAll('.library-card');
            
            libraryCards.forEach(card => {
                if (filter === 'all') {
                    card.style.display = 'block';
                } else {
                    const facilities = JSON.parse(card.getAttribute('data-facilities'));
                    const facilityMap = {
                        'ac': 'AC',
                        'wifi': 'WiFi',
                        'silent': 'Silent Zone',
                        'girls': 'Girls Zone',
                        'tea': 'Tea/Coffee',
                        'backup': 'Power Backup'
                    };
                    
                    const hasFilter = facilities.some(f => 
                        f.toLowerCase().includes(facilityMap[filter].toLowerCase())
                    );
                    
                    card.style.display = hasFilter ? 'block' : 'none';
                }
            });
        });
    });

    // Sort functionality
    if (sortBy) {
        sortBy.addEventListener('change', function() {
            const sortValue = this.value;
            const cardsArray = Array.from(libraryContainer.querySelectorAll('.library-card'));
            
            cardsArray.sort((a, b) => {
                if (sortValue === 'latest') {
                    return 0;
                }
                // Add more sort logic as needed
                return 0;
            });
            
            cardsArray.forEach(card => libraryContainer.appendChild(card));
        });
    }
});

function displayLibraries(libraries) {
    const container = document.getElementById('library-container');
    container.innerHTML = '';
    
    if (!libraries || libraries.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-16">
                <div class="text-6xl mb-4">📚</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Libraries Found</h3>
                <p class="text-gray-600">Try adjusting your filters or search criteria</p>
            </div>
        `;
        return;
    }
    
    libraries.forEach(lib => {
        const card = createLibraryCard(lib);
        container.appendChild(card);
    });
}

function createLibraryCard(lib) {
    const div = document.createElement('div');
    div.className = 'library-card bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group';
    div.setAttribute('data-facilities', JSON.stringify(lib.facilities || []));
    div.setAttribute('data-district', (lib.district?.name || 'Unknown'));
    
    const facilitiesHtml = lib.facilities ? lib.facilities.slice(0, 2).map(f => 
        `<span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full font-semibold">${f}</span>`
    ).join('') + (lib.facilities.length > 2 ? `<span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full font-semibold">+${lib.facilities.length - 2}</span>` : '') : '';
    
    const defaultImage = '{{ \App\Services\ImageService::getLibraryImage() }}';
    const image = (lib.images && lib.images.length > 0) ? `/storage/app/public/${lib.images[0]}` : defaultImage;
    const districtName = lib.district?.name || 'Unknown District';
    
    div.innerHTML = `
        <a href="/library/${lib.slug}" class="relative block overflow-hidden h-48 bg-gray-200">
            <img src="${image}" onerror="this.onerror=null;this.src='{{ \App\Services\ImageService::getLibraryImage() }}'" alt="${lib.name}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            <div class="absolute top-3 right-3 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                ✓ Verified
            </div>
            <div class="absolute top-3 left-3">
                <span class="${lib.status=='open' ? 'bg-green-500' : 'bg-red-500'} text-white px-3 py-1 rounded-full text-xs font-semibold">
                    ${lib.status ? lib.status.charAt(0).toUpperCase() + lib.status.slice(1) : 'Open'}
                </span>
            </div>
        </a>
        <div class="p-5 space-y-3">
            <div class="flex justify-between items-start gap-2">
                <h3 class="font-bold text-lg text-gray-900 line-clamp-2">${lib.name}</h3>
                <div class="text-right">
                    <div class="text-yellow-500 font-bold">⭐ ${lib.rating || '4.5'}</div>
                    <div class="text-xs text-gray-500">${Math.floor(Math.random() * 150) + 50} reviews</div>
                </div>
            </div>
            <div>
                <p class="text-gray-600 text-sm flex items-center gap-1 mb-1">
                    📍 <span class="line-clamp-1">${lib.location} - ${lib.pincode}</span>
                </p>
                <p class="text-teal-600 text-xs font-semibold">
                    📍 ${districtName}
                </p>
            </div>
            <p class="text-gray-700 text-sm font-medium">
                ⏰ ${lib.open_time || '09:00 AM'} - ${lib.close_time || '09:00 PM'}
            </p>
            ${facilitiesHtml ? `<div class="flex flex-wrap gap-1">${facilitiesHtml}</div>` : ''}
            <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                <div>
                    <span class="text-2xl font-bold text-teal-600">${lib.price ? '₹' + lib.price : 'Price coming soon'}</span>
                    ${lib.price ? '<span class="text-xs text-gray-500">/month</span>' : ''}
                </div>
                <a href="/library/${lib.slug}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    View Details
                </a>
            </div>
        </div>
    `;
    
    return div;
}
</script>
@endpush
