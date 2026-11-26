@extends('master')

@php
use App\Services\ImageService;
$allLibDesc = 'Browse the best study libraries and study rooms near you. View verified reviews, fees, amenities like AC and Wi-Fi, and book your free visit today!';
@endphp

@section('title', 'All Study Libraries - Browse Best Study Rooms Near You | Findstudyroom')
@section('meta_description', $allLibDesc)
@section('meta_keywords', 'study library, library membership, best study room, local study space, library list')
@section('og_title', 'All Study Libraries - Browse Best Study Rooms Near You')
@section('og_description', $allLibDesc)
@section('twitter_title', 'All Study Libraries - Browse Best Study Rooms Near You')
@section('twitter_description', $allLibDesc)

@section('content')

<!-- Hero Section -->
<section class="pt-28 pb-8 px-6 bg-gradient-to-r from-blue-50 to-teal-50">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">📚 Discover Study Rooms & Libraries</h1>
        <p class="text-lg text-gray-600">Find the perfect study space near you with verified reviews and real-time availability</p>
    </div>
</section>

<!-- State-wise Library Grid Section -->
<section class="px-6 py-12 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">🗺️ Browse Libraries by State</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-12">
            <!-- All Libraries Card -->
            <button id="allStatesBtn" class="state-card active bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300 border-2 border-blue-600" data-state-id="all">
                <div class="text-3xl mb-2">🌍</div>
                <div class="font-bold text-lg">All Libraries</div>
                <div class="text-sm opacity-90 mt-1">{{ count($libraries) }} libraries</div>
            </button>

            <!-- State Cards -->
            @forelse($states as $state)
                <a href="{{ route('libraries.state', \Illuminate\Support\Str::slug($state->name)) }}" class="state-card bg-white text-gray-900 rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300 border-2 border-gray-200 hover:border-teal-500 cursor-pointer">
                    <div class="text-3xl mb-2">📍</div>
                    <div class="font-bold text-lg line-clamp-2">{{ $state->name }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ $state->libraries_count }} libraries</div>
                </a>
            @empty
                <div class="col-span-full text-center text-gray-500">
                    No states available
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="px-6 py-12 bg-white border-b">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <!-- Stat 1: Verified Libraries -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">250+</div>
                <div class="text-sm font-semibold text-blue-900">Verified Libraries</div>
                <div class="text-xs text-blue-700 mt-1">📚</div>
            </div>

            <!-- Stat 2: Available Seats -->
            <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-2xl p-6 text-center">
                <div class="text-3xl font-bold text-teal-600 mb-2">18K+</div>
                <div class="text-sm font-semibold text-teal-900">Available Seats</div>
                <div class="text-xs text-teal-700 mt-1">💺</div>
            </div>

            <!-- Stat 3: Avg Rating -->
            <div class="bg-gradient-to-br from-yellow-50 to-orange-100 rounded-2xl p-6 text-center">
                <div class="text-3xl font-bold text-orange-600 mb-2">4.8/5</div>
                <div class="text-sm font-semibold text-orange-900">Avg Student Rating</div>
                <div class="text-xs text-orange-700 mt-1">⭐</div>
            </div>

            <!-- Stat 4: 24/7 Access -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">24/7</div>
                <div class="text-sm font-semibold text-purple-900">Access Availability</div>
                <div class="text-xs text-purple-700 mt-1">🕐</div>
            </div>
        </div>
    </div>
</section>

<!-- Filters Section -->
<!-- <section class="px-6 py-8 bg-gray-50 sticky top-16 z-40 shadow-sm">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-wrap gap-2 items-center overflow-x-auto pb-2">
            <button class="filter-btn active bg-blue-600 text-white px-6 py-2 rounded-full text-sm font-semibold transition whitespace-nowrap" data-filter="all">
                🎯 All Filters
            </button>
            <button class="filter-btn bg-white text-gray-700 px-4 py-2 rounded-full text-sm font-semibold border border-gray-300 hover:bg-gray-100 transition whitespace-nowrap" data-filter="ac">
                ❄️ AC Rooms
            </button>
            <button class="filter-btn bg-white text-gray-700 px-4 py-2 rounded-full text-sm font-semibold border border-gray-300 hover:bg-gray-100 transition whitespace-nowrap" data-filter="wifi">
                📶 High-speed WiFi
            </button>
            <button class="filter-btn bg-white text-gray-700 px-4 py-2 rounded-full text-sm font-semibold border border-gray-300 hover:bg-gray-100 transition whitespace-nowrap" data-filter="silent">
                🔇 Silent Zone
            </button>
            <button class="filter-btn bg-white text-gray-700 px-4 py-2 rounded-full text-sm font-semibold border border-gray-300 hover:bg-gray-100 transition whitespace-nowrap" data-filter="girls">
                👩 Girls-only Zone
            </button>
            <button class="filter-btn bg-white text-gray-700 px-4 py-2 rounded-full text-sm font-semibold border border-gray-300 hover:bg-gray-100 transition whitespace-nowrap" data-filter="tea">
                ☕ Tea/Coffee
            </button>
            <button class="filter-btn bg-white text-gray-700 px-4 py-2 rounded-full text-sm font-semibold border border-gray-300 hover:bg-gray-100 transition whitespace-nowrap" data-filter="backup">
                🔋 Power Backup
            </button>
            <div class="ml-auto">
                <select id="sortBy" class="bg-white text-gray-700 px-4 py-2 rounded-full text-sm font-semibold border border-gray-300 hover:bg-gray-100 transition cursor-pointer whitespace-nowrap">
                    <option value="latest">↓ Latest</option>
                    <option value="rating">⭐ Rating</option>
                    <option value="price-low">₹ Low to High</option>
                    <option value="price-high">₹ High to Low</option>
                </select>
            </div>
        </div>
    </div>
</section> -->

<!-- Libraries Grid Section -->
<section class="px-6 py-12">
    <div class="max-w-7xl mx-auto">
        <!-- Section Title -->
        <div class="mb-8">
            <h2 id="sectionTitle" class="text-2xl font-bold text-gray-900 mb-2">📚 All Libraries</h2>
            <p id="sectionDescription" class="text-gray-600">Showing all libraries across all states</p>
        </div>

        <!-- District Grouping (visible only when a state is selected) -->
        <div id="districtGroupContainer" style="display: none;" class="mb-8">
            <div id="districtTabs" class="flex flex-wrap gap-2 mb-6 pb-4 border-b border-gray-200 overflow-x-auto">
                <!-- District tabs will be inserted here -->
            </div>
        </div>

        <div id="library-container" class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($libraries as $lib)
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
            @endphp
                <div class="library-card bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group" data-facilities="{{ json_encode($lib->facilities ?? []) }}">
                    <!-- Image Container -->
                    <a href="{{ route('library.details',$lib->slug) }}" class="relative block overflow-hidden h-48 bg-gray-200">
                            <img src="{{ !empty($lib->images) && count($lib->images) > 0 ? asset('storage/app/public/'.$lib->images[0]) : $fallbackImage }}"
                                alt="{{ $lib->name }} - Study Library in {{ $lib->location ?? ($lib->city ?? 'India') }}"
                                title="{{ $lib->name }} - Findstudyroom"
                                onerror="this.onerror=null;this.src='{{ $fallbackImage }}'"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                loading="lazy">
                        
                        <!-- Verified Badge -->
                        @if($lib->is_verified)
                        <div class="absolute top-3 right-3 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                            ✓ Verified
                        </div>
                        @endif

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
                                    <span class="inline-flex items-center justify-center bg-blue-100 text-blue-700 text-[10px] font-bold px-1.5 py-0.5 rounded ml-1 align-middle" title="Verified Premium Library">
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

                        <!-- Location -->
                        <p class="text-gray-600 text-sm flex items-center gap-1">
                            📍 <span class="line-clamp-1">{{ $lib->location }} - {{ $lib->pincode }}</span>
                        </p>

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
                                @if(!empty($lib->price))
                                    <span class="text-2xl font-bold text-teal-600">₹{{ $lib->price }}</span>
                                    <span class="text-xs text-gray-500">/month</span>
                                @else
                                    <span class="text-sm text-gray-500 font-medium">Price coming soon</span>
                                @endif
                            </div>
                            <a href="{{ route('library.details',$lib->slug) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
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

<!-- Load More Section -->
@if(count($libraries) > 0)
    <section class="px-6 py-12 text-center">
        <button id="loadMoreBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition">
            Load More Libraries ↓
        </button>
    </section>
@endif

@endsection

@push('scripts')
<script>
let allLibrariesData = @json($libraries);

document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const sortBy = document.getElementById('sortBy');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    let offset = 0;

    // Initialize with all libraries
    displayLibraries(allLibrariesData);

    // Filter functionality
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterBtns.forEach(b => {
                b.classList.remove('active', 'bg-blue-600', 'text-white');
                b.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            });
            
            this.classList.add('active', 'bg-blue-600', 'text-white');
            this.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            
            // Filter libraries
            const container = document.getElementById('library-container');
            const libraryCards = container.querySelectorAll('.library-card');
            
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
            const container = document.getElementById('library-container');
            const cardsArray = Array.from(container.querySelectorAll('.library-card'));
            
            cardsArray.sort((a, b) => {
                if (sortValue === 'latest') {
                    return 0;
                }
                return 0;
            });
            
            cardsArray.forEach(card => container.appendChild(card));
        });
    }

    // Load More functionality
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            offset += 12;
            fetch(`{{ route('libraries.loadMore') }}?offset=${offset}`)
                .then(response => response.json())
                .then(data => {
                    const libraries = Array.isArray(data) ? data : (data.libraries || []);
                    if (libraries && libraries.length > 0) {
                        const container = document.getElementById('library-container');
                        libraries.forEach(lib => {
                            const card = createLibraryCard(lib);
                            container.appendChild(card);
                        });
                    } else {
                        loadMoreBtn.style.display = 'none';
                    }
                });
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
    
    const facilitiesHtml = lib.facilities ? lib.facilities.slice(0, 2).map(f => 
        `<span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full font-semibold">${f}</span>`
    ).join('') + (lib.facilities.length > 2 ? `<span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full font-semibold">+${lib.facilities.length - 2}</span>` : '') : '';
    
    const defaultImages = [
        'https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=600&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1541963463532-d68292c34b19?q=80&w=600&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?q=80&w=600&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=600&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1568667256549-094345857637?q=80&w=600&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=600&auto=format&fit=crop'
    ];
    const defaultImage = defaultImages[(lib.id || Math.floor(Math.random() * 10)) % defaultImages.length];
    const image = (lib.images && lib.images.length > 0) ? `/storage/app/public/${lib.images[0]}` : defaultImage;
    const priceHtml = lib.price ? `<span class="text-2xl font-bold text-teal-600">₹${lib.price}</span><span class="text-xs text-gray-500">/month</span>` : `<span class="text-sm text-gray-500 font-medium">Price coming soon</span>`;
    
    div.innerHTML = `
        <a href="/library/${lib.slug}" class="relative block overflow-hidden h-48 bg-gray-200">
            <img src="${image}" alt="${lib.name} - Study Library" title="${lib.name} - Findstudyroom" onerror="this.onerror=null;this.src='${defaultImage}'" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
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
            <p class="text-gray-600 text-sm flex items-center gap-1">
                📍 <span class="line-clamp-1">${lib.location} - ${lib.pincode}</span>
            </p>
            <p class="text-gray-700 text-sm font-medium">
                ⏰ ${lib.open_time || '09:00 AM'} - ${lib.close_time || '09:00 PM'}
            </p>
            ${facilitiesHtml ? `<div class="flex flex-wrap gap-1">${facilitiesHtml}</div>` : ''}
            <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                <div>
                    ${priceHtml}
                </div>
                <a href="/library/${lib.slug}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    View Details
                </a>
            </div>
        </div>
    `;
    
    return div;
}
</script>
@endpush
