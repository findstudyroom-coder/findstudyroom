@extends('master')

@php
use Illuminate\Support\Str;
use App\Services\ImageService;
$libraryDescription = $library->descriptions ? strip_tags($library->descriptions) : 'Book a study library in '.($library->location ?? 'your area').' with comfortable seating, high-speed WiFi, and good lighting.';
$libraryDescriptionShort = Str::limit($libraryDescription, 155, '...');
$ogImage = !empty($library->images) && count($library->images) > 0 
    ? asset('storage/app/public/'.$library->images[0]) 
    : ImageService::getLibraryImage();
$openingHours = ($library->open_time && $library->close_time)
    ? 'Mo-Su '.date('H:i', strtotime($library->open_time)).'-'.date('H:i', strtotime($library->close_time))
    : null;
$librarySchema = [
    '@context' => 'https://schema.org',
    '@type' => 'Library',
    'name' => $library->name,
    'description' => $libraryDescription,
    'image' => $ogImage,
    'url' => url()->current(),
    'telephone' => $library->phone,
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => $library->location,
        'addressLocality' => $library->district->name ?? '',
        'addressRegion' => $library->state->name ?? '',
        'postalCode' => $library->pincode,
    ],
    'geo' => [
        '@type' => 'GeoCoordinates',
        'latitude' => $library->latitude,
        'longitude' => $library->longitude,
    ],
];
if ($openingHours) {
    $librarySchema['openingHours'] = $openingHours;
}
@endphp

@section('title', $library->name . ' | Study Library in ' . ($library->location ?? 'Your Area') . ' | Findstudyroom')
@section('meta_description', $libraryDescriptionShort)
@section('meta_keywords', $library->name . ', study library, library membership, ' . ($library->location ?? '') . ', study room, library booking')
@section('og_title', $library->name . ' - Study Library in ' . ($library->location ?? 'Your Area'))
@section('og_description', $libraryDescriptionShort)
@section('twitter_title', $library->name . ' - Study Library in ' . ($library->location ?? 'Your Area'))
@section('twitter_description', $libraryDescriptionShort)

@section('canonical', url('/library/' . ($library->slug ?? '')))

@push('head')
<script type="application/ld+json">
{!! json_encode($librarySchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')

<section class="relative bg-bg pt-6 pb-16 overflow-hidden">
  <!-- Decorative background elements -->
  <div class="gradient-blob w-96 h-96 bg-accent top-0 -left-48 opacity-40"></div>
  <div class="gradient-blob w-80 h-80 bg-coral bottom-0 right-0 opacity-30"></div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-2 relative z-10">

    <!-- Back -->
    <!-- <a href="{{ route('libraries.all') }}" class="inline-flex items-center gap-2 text-muted hover:text-accent font-medium mb-6 transition-colors animate-slide-up">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
      Back to Libraries
    </a> -->

    <!-- GRID -->
    <div class="grid lg:grid-cols-3 gap-10">

      <!-- LEFT -->
      <div class="lg:col-span-2 space-y-8 animate-slide-up stagger-1">

        <!-- Main Image Area -->
        <div>
          <div class="relative rounded-3xl overflow-hidden group border border-border shadow-sm">
            <!-- Image -->
            <img id="mainImage"
                 src="{{ !empty($library->images) && is_array($library->images) && count($library->images) > 0 ? asset('storage/app/public/'.$library->images[0]) : ImageService::getLibraryImage() }}"
                 onerror="this.onerror=null;this.src='{{ ImageService::getLibraryImage() }}'"
                 class="w-full h-[500px] object-cover transition-transform duration-700 group-hover:scale-105">
                 
            <!-- Top Badges -->
            <div class="absolute top-4 left-4 flex flex-wrap gap-2 z-10">
              <span class="bg-accent text-white text-[11px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wide shadow-md">Verified Library</span>
              <span class="bg-teal-500 text-white text-[11px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wide shadow-md">Top Rated ({{ $library->rating ?? '4.8' }})</span>
              <span class="bg-orange-500 text-white text-[11px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wide shadow-md">Featured</span>
            </div>

            <!-- Bottom Hover Buttons -->
            <div class="absolute bottom-6 left-0 right-0 flex justify-center gap-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
              <button class="bg-white/90 backdrop-blur text-fg font-bold px-6 py-2.5 rounded-xl hover:bg-white transition-colors text-sm shadow-lg">Video Tour</button>
              <button class="bg-white/90 backdrop-blur text-fg font-bold px-6 py-2.5 rounded-xl hover:bg-white transition-colors text-sm shadow-lg">360° View</button>
              <button class="bg-white/90 backdrop-blur text-fg font-bold px-6 py-2.5 rounded-xl hover:bg-white transition-colors text-sm shadow-lg">Full Screen Gallery</button>
            </div>
          </div>

          <!-- Thumbnails -->
          <div class="flex items-center gap-4 mt-6">
            <button class="w-10 h-10 flex-shrink-0 rounded-full border border-gray-200 bg-white flex items-center justify-center text-gray-400 hover:text-accent hover:border-accent transition shadow-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            
            <div class="flex gap-4 overflow-x-auto flex-1 scrollbar-hide py-1">
              @if(!empty($library->images) && is_array($library->images))
                @foreach($library->images as $img)
                  <img src="{{ asset('storage/app/public/'.$img) }}"
                       onerror="this.onerror=null;this.src='{{ ImageService::getLibraryImage() }}'"
                       onclick="changeImage('{{ asset('storage/app/public/'.$img) }}')"
                       class="h-20 w-32 object-cover rounded-xl cursor-pointer border-2 border-transparent hover:border-accent transition-all duration-300 shadow-sm flex-shrink-0">
                @endforeach
              @endif
            </div>
            
            <button class="w-10 h-10 flex-shrink-0 rounded-full border border-gray-200 bg-white flex items-center justify-center text-gray-400 hover:text-accent hover:border-accent transition shadow-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
          </div>
        </div>

      </div>

      <!-- RIGHT CARD -->
      <div class="lg:col-span-1 animate-slide-up stagger-2">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-border sticky top-2">
          
          <!-- Status & Distance -->
          <div class="flex justify-between items-center mb-4 text-sm">
            <span class="flex items-center gap-2 {{ $library->status == 'open' ? 'text-accent' : 'text-red-500' }} font-bold">
              <span class="w-2.5 h-2.5 {{ $library->status == 'open' ? 'bg-accent' : 'bg-red-500' }} rounded-full animate-pulse"></span>
              {{ $library->status == 'open' ? 'Open Now' : 'Closed' }}
            </span>
            <span class="text-gray-400 font-medium">1.2 km away</span>
          </div>

          <!-- Title & Rating -->
          <h1 class="text-2xl font-bold text-fg mb-1">{{ $library->name }}</h1>
          <div class="flex items-center gap-2 mb-3">
            <span class="font-bold text-fg text-sm">{{ $library->rating ?? '4.8' }}</span>
            <span class="text-gray-400 text-sm">(356 Reviews)</span>
          </div>

          <!-- Timing -->
          <p class="text-sm text-gray-500 mb-6">
            Today's Timing: 
            <span class="font-bold text-fg">
              {{ $library->open_time ? date('h:i A', strtotime($library->open_time)) : '06:00 AM' }} - 
              {{ $library->close_time ? date('h:i A', strtotime($library->close_time)) : '11:30 PM' }}
            </span>
          </p>

          <!-- Membership Price Box -->
          <div class="bg-gray-50 rounded-2xl p-5 mb-4 border border-gray-100">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Membership Price</p>
            <div class="flex items-end gap-1 mb-1">
              <span class="text-3xl font-display font-bold text-fg">{{ $library->price ? '₹'.$library->price : '₹1,500' }}</span>
              <span class="text-gray-500 font-medium mb-1.5 text-sm">/month</span>
            </div>
            <p class="text-[11px] text-gray-400 font-medium">+Inclusive of high-speed internet & AC</p>
          </div>

          <!-- Seats Box -->
        

          <!-- Action Buttons -->
          <div class="space-y-3">
            <button onclick="openModal()" class="w-full bg-accent text-white font-bold py-3.5 rounded-xl hover:bg-teal-600 transition shadow-sm text-sm">
              Book Free Trial Visit
            </button>
            <button class="w-full bg-teal-400 text-white font-bold py-3.5 rounded-xl hover:bg-teal-500 transition shadow-sm text-sm">
              Book Membership
            </button>
            
            <div class="grid grid-cols-2 gap-3 pt-1">
              <a href="#" class="bg-green-50 text-green-600 font-bold py-3 rounded-xl hover:bg-green-100 transition text-center text-sm flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                WhatsApp
              </a>
              @if(!empty($library->phone) && !in_array(strtolower(trim($library->phone)), ['n/a','na','null']))
              <button type="button" 
                      data-phone="{{ $library->phone }}" 
                      data-name="{{ $library->name }}" 
                      data-id="{{ $library->id }}" 
                      onclick="openLibraryCallModal(this.dataset.phone, this.dataset.name, this.dataset.id)" 
                      class="bg-teal-50 text-teal-600 font-bold py-3 rounded-xl hover:bg-teal-100 transition text-center text-sm flex items-center justify-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                Call Owner
              </button>
              @else
              <span class="bg-gray-50 text-gray-400 font-bold py-3 rounded-xl text-center text-sm flex items-center justify-center gap-1.5 cursor-not-allowed">
                Call N/A
              </span>
              @endif
            </div>
          </div>

        </div>
      </div>

    </div>

    <!-- Key Quick Info Section -->
    @if(!empty($library->facilities) && is_array($library->facilities) && count($library->facilities) > 0)
    <div class="mt-10 pt-12 border-t border-border animate-slide-up stagger-3">
      
      <!-- Header -->
      <div class="text-center max-w-2xl mx-auto mb-8 flex flex-col items-center">
        <h4 class="flex items-center justify-center gap-2 text-accent text-xs font-bold uppercase tracking-wider mb-3">
          <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
          Premium Facilities
        </h4>
        <h2 class="font-display text-3xl sm:text-4xl font-bold text-fg mb-4 flex items-center justify-center gap-3">
          Library Amenities
        </h2>
        <p class="text-muted text-[15px]">We offer state-of-the-art facilities to make your study sessions as smooth as possible.</p>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
        @foreach($library->facilities as $facility)
        @php
            $fLower = strtolower($facility);
            $iconPath = 'M5 13l4 4L19 7'; // Default checkmark
            
            if (str_contains($fLower, 'wifi') || str_contains($fLower, 'internet') || str_contains($fLower, 'wi-fi')) {
                $iconPath = 'M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M7.399 7.399c5.38-5.38 14.102-5.38 19.482 0'; // wifi
            } elseif (str_contains($fLower, 'ac') || str_contains($fLower, 'air condition')) {
                $iconPath = 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z'; // moon / cool
            } elseif (str_contains($fLower, 'water') || str_contains($fLower, 'ro ')) {
                $iconPath = 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'; // sun / droplet replacement
            } elseif (str_contains($fLower, 'cctv') || str_contains($fLower, 'camera') || str_contains($fLower, 'security')) {
                $iconPath = 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'; // video camera
            } elseif (str_contains($fLower, 'park')) {
                $iconPath = 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'; // map pin / parking
            } elseif (str_contains($fLower, 'book') || str_contains($fLower, 'magazine') || str_contains($fLower, 'newspaper') || str_contains($fLower, 'news paper')) {
                $iconPath = 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'; // book
            } elseif (str_contains($fLower, 'washroom') || str_contains($fLower, 'toilet')) {
                $iconPath = 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'; // building / washroom
            } elseif (str_contains($fLower, 'power') || str_contains($fLower, 'backup') || str_contains($fLower, 'electricity')) {
                $iconPath = 'M13 10V3L4 14h7v7l9-11h-7z'; // lightning
            } elseif (str_contains($fLower, 'locker')) {
                $iconPath = 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'; // lock
            } elseif (str_contains($fLower, 'desk') || str_contains($fLower, 'seat') || str_contains($fLower, 'chair')) {
                $iconPath = 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'; // briefcase / professional
            }
        @endphp
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-border hover:shadow-md transition-all flex flex-col items-center justify-center text-center group cursor-default">
          <div class="w-14 h-14 bg-teal-50 group-hover:bg-accent group-hover:text-white transition-colors duration-300 rounded-full mb-4 flex items-center justify-center text-accent">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"></path></svg>
          </div>
          <div class="text-xs font-bold text-fg group-hover:text-accent transition-colors duration-300">{{ $facility }}</div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    <!-- About Our Space Section -->
    <div class="mt-12 animate-slide-up stagger-4">
      <div class="bg-white rounded-3xl border border-border shadow-sm overflow-hidden flex flex-col lg:flex-row">
        
        <!-- Left Side: About Text -->
        <div class="lg:w-1/2 p-8 lg:p-12 space-y-10">
          <div>
            <h4 class="text-accent text-xs font-bold uppercase tracking-wider mb-3">About Our Space</h4>
            <h2 class="font-display text-3xl font-bold text-fg mb-6">{{ $library->name }}</h2>
            
            <div class="text-muted leading-relaxed space-y-5 text-[15px]">
              <p>Welcome to <strong>{{ $library->name }}</strong>, the finest academic environment designed specifically for dedicated students and competitive exam aspirants in Noida. Established with a single-minded goal: to eliminate every barrier to focused learning, we provide an oasis of concentration amid a bustling city.</p>
              
              <p>
                {!! $library->descriptions 
                  ? nl2br(e($library->descriptions)) 
                  : 'Whether you are preparing for UPSC, GATE, CAT, NEET, CA, or simply need a peaceful environment for corporate projects, our premises are engineered for productivity. With ergonomic seating, perfect natural lighting balance, and distraction-free quiet zoning, your dream score is within reach.' 
                !!}
              </p>
            </div>
          </div>

          <!-- Mission Box -->
          <div class="border-l-2 border-accent pl-5 py-1">
            <h4 class="font-bold text-fg mb-1.5 text-[15px]">Our Mission</h4>
            <p class="text-muted italic text-[14px]">"To create an unmatched, distraction-free environment that empowers students and professionals to unlock their highest potential and succeed in their careers."</p>
          </div>

          <!-- Target Audience -->
        
        </div>

        <!-- Right Side: Features Grid -->
        <div class="lg:w-1/2 p-8 lg:p-12 lg:border-l border-border bg-gray-50/50 space-y-12">
          
          <div class="border border-green-100 bg-white p-6 lg:p-8 rounded-2xl shadow-sm">
            <h4 class="text-accent font-bold mb-2">Curated Study Environment</h4>
            <p class="text-[14px] text-muted leading-relaxed">Our library offers tailored zones matching your specific study mood. No more noisy coffee shops or cramped bedrooms.</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-10 gap-x-8">
            <div>
              <h5 class="font-bold text-fg mb-1.5 text-[15px]">Silent Zone</h5>
              <p class="text-[13px] text-muted leading-relaxed">Strict noise monitoring. Zero talking zone.</p>
            </div>
            <div>
              <h5 class="font-bold text-fg mb-1.5 text-[15px]">Exam Friendly</h5>
              <p class="text-[13px] text-muted leading-relaxed">Special desk designs matching examination environments.</p>
            </div>
            <div>
              <h5 class="font-bold text-fg mb-1.5 text-[15px]">Reading Area</h5>
              <p class="text-[13px] text-muted leading-relaxed">Separate spacious tables for books and newspapers.</p>
            </div>
            <div>
              <h5 class="font-bold text-fg mb-1.5 text-[15px]">Study Cabins</h5>
              <p class="text-[13px] text-muted leading-relaxed">Individual cubicles with privacy partitioning panels.</p>
            </div>
            <div>
              <h5 class="font-bold text-fg mb-1.5 text-[15px]">Discussion Room</h5>
              <p class="text-[13px] text-muted leading-relaxed">Soundproof space for peer learning and project talks.</p>
            </div>
            <div>
              <h5 class="font-bold text-fg mb-1.5 text-[15px]">Natural Lighting</h5>
              <p class="text-[13px] text-muted leading-relaxed">Well ventilated design with anti-glare window systems.</p>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Library Photo Gallery Section -->
    <div class="mt-16 pt-12 border-t border-border animate-slide-up stagger-5">
      
      <!-- Header -->
      <div class="text-center max-w-2xl mx-auto mb-12">
        <h4 class="text-accent text-xs font-bold uppercase tracking-wider mb-3">Visual Tour</h4>
        <h2 class="font-display text-3xl sm:text-4xl font-bold text-fg mb-4">Library Photo Gallery</h2>
        <p class="text-muted text-[15px]">Take a visual tour around our study hall, cabins, reception, and student relaxation spots.</p>
      </div>

      <!-- Filters -->
   

      <!-- Masonry Grid -->
      @if(!empty($library->images) && is_array($library->images) && count($library->images) > 0)
        <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
          @foreach($library->images as $img)
          <div class="break-inside-avoid">
            <img src="{{ asset('storage/app/public/'.$img) }}" 
                 onerror="this.onerror=null;this.src='{{ ImageService::getLibraryImage() }}'"
                 class="w-full rounded-3xl shadow-sm border border-border object-cover cursor-pointer hover:opacity-90 transition-opacity" 
                 onclick="changeImage('{{ asset('storage/app/public/'.$img) }}')" />
          </div>
          @endforeach
        </div>
      @else
        <!-- Fallback mock grid -->
        <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
          <div class="break-inside-avoid">
            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80" class="w-full rounded-3xl shadow-sm border border-border object-cover" />
          </div>
          <div class="break-inside-avoid">
            <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=800&q=80" class="w-full rounded-3xl shadow-sm border border-border object-cover" />
          </div>
          <div class="break-inside-avoid">
            <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=800&q=80" class="w-full rounded-3xl shadow-sm border border-border object-cover" />
          </div>
          <div class="break-inside-avoid">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80" class="w-full rounded-3xl shadow-sm border border-border object-cover" />
          </div>
          <div class="break-inside-avoid">
            <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=800&q=80" class="w-full rounded-3xl shadow-sm border border-border object-cover" />
          </div>
        </div>
      @endif
    </div>

 
    @endif

    <!-- Nearby Libraries Section -->
    <div class="mt-10 pt-12 border-t border-border animate-slide-up stagger-5">
      
      <!-- Header -->
      <div class="flex items-end justify-between mb-8">
        <div>
          <h4 class="text-accent text-xs font-bold uppercase tracking-wider mb-2">Explore Nearby</h4>
          <h2 class="font-display text-3xl font-bold text-fg">Nearby Libraries</h2>
        </div>
        <!-- Navigation Arrows -->
        <div class="flex gap-2 hidden sm:flex">
          <div class="w-10 h-10 rounded-full border border-border flex items-center justify-center text-gray-400 hover:bg-gray-50 cursor-pointer transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
          </div>
          <div class="w-10 h-10 rounded-full border border-border flex items-center justify-center text-gray-400 hover:bg-gray-50 cursor-pointer transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </div>
        </div>
      </div>

      <!-- Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Card 1 -->
        <div class="bg-white rounded-[20px] shadow-sm border border-border overflow-hidden group hover:shadow-md transition-all flex flex-col">
          <div class="relative h-44 shrink-0">
            <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=500&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute bottom-2 right-2 bg-black/80 text-white text-[11px] font-bold px-2.5 py-1 rounded-md backdrop-blur-sm">₹1,200/mo</div>
          </div>
          <div class="p-5 flex flex-col flex-grow">
            <div class="flex items-center gap-1.5 mb-2 text-[12px] font-bold text-fg">
              <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> 4.6</span> <span class="text-gray-400 font-normal">(0.8 km)</span>
            </div>
            <h3 class="font-bold text-fg text-[15px] mb-1 truncate">Noida Study Point</h3>
            <p class="text-gray-400 text-[12px] mb-4">8 Seats Left</p>
            
            <div class="flex gap-2 mt-auto">
              <button class="flex-1 bg-accent hover:bg-green-600 text-white text-[13px] font-bold py-2.5 rounded-lg transition-colors">Book Visit</button>
              <button class="w-[42px] h-[42px] rounded-lg border border-border flex items-center justify-center text-gray-400 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
              </button>
              <button class="w-[42px] h-[42px] rounded-lg border border-border flex items-center justify-center text-gray-400 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-[20px] shadow-sm border border-border overflow-hidden group hover:shadow-md transition-all flex flex-col">
          <div class="relative h-44 shrink-0">
            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=500&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute bottom-2 right-2 bg-black/80 text-white text-[11px] font-bold px-2.5 py-1 rounded-md backdrop-blur-sm">₹1,400/mo</div>
          </div>
          <div class="p-5 flex flex-col flex-grow">
            <div class="flex items-center gap-1.5 mb-2 text-[12px] font-bold text-fg">
              <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> 4.5</span> <span class="text-gray-400 font-normal">(1.5 km)</span>
            </div>
            <h3 class="font-bold text-fg text-[15px] mb-1 truncate">Success Library Zone</h3>
            <p class="text-gray-400 text-[12px] mb-4">14 Seats Left</p>
            
            <div class="flex gap-2 mt-auto">
              <button class="flex-1 bg-accent hover:bg-green-600 text-white text-[13px] font-bold py-2.5 rounded-lg transition-colors">Book Visit</button>
              <button class="w-[42px] h-[42px] rounded-lg border border-border flex items-center justify-center text-gray-400 hover:bg-gray-50 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg></button>
              <button class="w-[42px] h-[42px] rounded-lg border border-border flex items-center justify-center text-gray-400 hover:bg-gray-50 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></button>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-[20px] shadow-sm border border-border overflow-hidden group hover:shadow-md transition-all flex flex-col">
          <div class="relative h-44 shrink-0">
            <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=500&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute bottom-2 right-2 bg-black/80 text-white text-[11px] font-bold px-2.5 py-1 rounded-md backdrop-blur-sm">₹1,600/mo</div>
          </div>
          <div class="p-5 flex flex-col flex-grow">
            <div class="flex items-center gap-1.5 mb-2 text-[12px] font-bold text-fg">
              <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> 4.9</span> <span class="text-gray-400 font-normal">(2.1 km)</span>
            </div>
            <h3 class="font-bold text-fg text-[15px] mb-1 truncate">Vidya Premium Lounge</h3>
            <p class="text-gray-400 text-[12px] mb-4">4 Seats Left</p>
            
            <div class="flex gap-2 mt-auto">
              <button class="flex-1 bg-accent hover:bg-green-600 text-white text-[13px] font-bold py-2.5 rounded-lg transition-colors">Book Visit</button>
              <button class="w-[42px] h-[42px] rounded-lg border border-border flex items-center justify-center text-gray-400 hover:bg-gray-50 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg></button>
              <button class="w-[42px] h-[42px] rounded-lg border border-border flex items-center justify-center text-gray-400 hover:bg-gray-50 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></button>
            </div>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-[20px] shadow-sm border border-border overflow-hidden group hover:shadow-md transition-all flex flex-col">
          <div class="relative h-44 shrink-0">
            <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=500&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute bottom-2 right-2 bg-black/80 text-white text-[11px] font-bold px-2.5 py-1 rounded-md backdrop-blur-sm">₹1,100/mo</div>
          </div>
          <div class="p-5 flex flex-col flex-grow">
            <div class="flex items-center gap-1.5 mb-2 text-[12px] font-bold text-fg">
              <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> 4.2</span> <span class="text-gray-400 font-normal">(2.8 km)</span>
            </div>
            <h3 class="font-bold text-fg text-[15px] mb-1 truncate">Apex Reading Room</h3>
            <p class="text-gray-400 text-[12px] mb-4">18 Seats Left</p>
            
            <div class="flex gap-2 mt-auto">
              <button class="flex-1 bg-accent hover:bg-green-600 text-white text-[13px] font-bold py-2.5 rounded-lg transition-colors">Book Visit</button>
              <button class="w-[42px] h-[42px] rounded-lg border border-border flex items-center justify-center text-gray-400 hover:bg-gray-50 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg></button>
              <button class="w-[42px] h-[42px] rounded-lg border border-border flex items-center justify-center text-gray-400 hover:bg-gray-50 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></button>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Similar Libraries Section -->
    <div class="mt-16 pt-12 border-t border-border animate-slide-up stagger-5">
      
      <!-- Header -->
      <div class="text-center max-w-2xl mx-auto mb-12">
        <h4 class="text-accent text-xs font-bold uppercase tracking-wider mb-3">Recommended For You</h4>
        <h2 class="font-display text-3xl font-bold text-fg mb-4">Similar Libraries</h2>
        <p class="text-muted text-[14px]">Based on matching pricing layers, top facilities, and proximity filters.</p>
      </div>

      <!-- Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Card 1 -->
        <div class="bg-white rounded-[20px] shadow-sm border border-border overflow-hidden group hover:shadow-md transition-all flex flex-col">
          <div class="relative h-48 shrink-0">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
          </div>
          <div class="p-6 flex flex-col flex-grow">
            <div class="flex items-center justify-between mb-3">
              <span class="font-bold text-fg text-[14px]">4.7</span> 
              <span class="text-accent font-bold text-[13px]">₹1,399 / Mo</span>
            </div>
            <h3 class="font-bold text-fg text-[16px] mb-2 truncate">Target Study Hub</h3>
            <p class="text-accent text-[12px] mb-6 font-medium">95% Match &bull; Same location & price class</p>
            
            <div class="flex gap-3 mt-auto">
              <button class="flex-1 bg-accent hover:bg-green-600 text-white text-[13px] font-bold py-2.5 rounded-lg transition-colors">Book Visit</button>
              <button class="flex-1 bg-white border border-border hover:bg-gray-50 text-fg text-[13px] font-bold py-2.5 rounded-lg transition-colors">Compare</button>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-[20px] shadow-sm border border-border overflow-hidden group hover:shadow-md transition-all flex flex-col">
          <div class="relative h-48 shrink-0">
            <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
          </div>
          <div class="p-6 flex flex-col flex-grow">
            <div class="flex items-center justify-between mb-3">
              <span class="font-bold text-fg text-[14px]">4.8</span> 
              <span class="text-accent font-bold text-[13px]">₹1,500 / Mo</span>
            </div>
            <h3 class="font-bold text-fg text-[16px] mb-2 truncate">Focus Reading Library</h3>
            <p class="text-accent text-[12px] mb-6 font-medium">92% Match &bull; Same high speed fiber network</p>
            
            <div class="flex gap-3 mt-auto">
              <button class="flex-1 bg-accent hover:bg-green-600 text-white text-[13px] font-bold py-2.5 rounded-lg transition-colors">Book Visit</button>
              <button class="flex-1 bg-white border border-border hover:bg-gray-50 text-fg text-[13px] font-bold py-2.5 rounded-lg transition-colors">Compare</button>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-[20px] shadow-sm border border-border overflow-hidden group hover:shadow-md transition-all flex flex-col">
          <div class="relative h-48 shrink-0">
            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
          </div>
          <div class="p-6 flex flex-col flex-grow">
            <div class="flex items-center justify-between mb-3">
              <span class="font-bold text-fg text-[14px]">4.6</span> 
              <span class="text-accent font-bold text-[13px]">₹1,450 / Mo</span>
            </div>
            <h3 class="font-bold text-fg text-[16px] mb-2 truncate">Ambition UPSC Zone</h3>
            <p class="text-accent text-[12px] mb-6 font-medium">89% Match &bull; UPSC focused book catalog</p>
            
            <div class="flex gap-3 mt-auto">
              <button class="flex-1 bg-accent hover:bg-green-600 text-white text-[13px] font-bold py-2.5 rounded-lg transition-colors">Book Visit</button>
              <button class="flex-1 bg-white border border-border hover:bg-gray-50 text-fg text-[13px] font-bold py-2.5 rounded-lg transition-colors">Compare</button>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Recently Viewed Libraries Section -->
    <!-- <div class="mt-16 pt-12 border-t border-border animate-slide-up stagger-5"> -->
      
      <!-- Header -->
      <!-- <div class="mb-8">
        <h4 class="text-accent text-xs font-bold uppercase tracking-wider mb-2">Your Browsing History</h4>
        <h2 class="font-display text-3xl font-bold text-fg">Recently Viewed Libraries</h2>
      </div> -->

      <!-- Grid -->
      <!-- <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4"> -->
        
        <!-- Small Card 1 -->
        <!-- <div class="bg-white rounded-xl shadow-sm border border-border p-3 flex items-center gap-4 hover:shadow-md transition-all cursor-pointer">
          <div class="w-14 h-14 rounded-lg overflow-hidden shrink-0">
            <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=150&q=80" class="w-full h-full object-cover" />
          </div>
          <div>
            <h4 class="font-bold text-fg text-[13px] truncate">Noida Study Point</h4>
            <p class="text-gray-400 text-[11px]">Sector 62, Noida</p>
          </div>
        </div> -->

        <!-- Small Card 2 -->
        <!-- <div class="bg-white rounded-xl shadow-sm border border-border p-3 flex items-center gap-4 hover:shadow-md transition-all cursor-pointer">
          <div class="w-14 h-14 rounded-lg overflow-hidden shrink-0">
            <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=150&q=80" class="w-full h-full object-cover" />
          </div>
          <div>
            <h4 class="font-bold text-fg text-[13px] truncate">Vidya Premium Lounge</h4>
            <p class="text-gray-400 text-[11px]">Sector 59, Noida</p>
          </div>
        </div> -->

        <!-- Small Card 3 -->
        <!-- <div class="bg-white rounded-xl shadow-sm border border-border p-3 flex items-center gap-4 hover:shadow-md transition-all cursor-pointer">
          <div class="w-14 h-14 rounded-lg overflow-hidden shrink-0">
            <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=150&q=80" class="w-full h-full object-cover" />
          </div>
          <div>
            <h4 class="font-bold text-fg text-[13px] truncate">Apex Reading Room</h4>
            <p class="text-gray-400 text-[11px]">Sector 63, Noida</p>
          </div>
        </div> -->

      <!-- </div> -->
    <!-- </div> -->

  </div>
</section>

@endsection
<!-- VISIT MODAL -->
<div id="visitModal"
     style="display:none;"
     class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    
    <div class="w-full max-w-md p-6 bg-white rounded-2xl shadow-xl relative">

        <!-- Close -->
        <button onclick="closeModal()" 
            class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-xl">
            ✖
        </button>

        <h2 class="text-xl font-bold mb-4 text-center">📅 Book a Visit</h2>

        <!-- FORM -->
        <form action="{{route('booking.store')}}" method="POST">
            @csrf

            <input type="hidden" name="library_id" value="{{ $library->id }}">

            <input type="text" name="name" placeholder="Your Name"
                class="w-full border p-3 rounded-lg mb-3" required>

            <input type="tel" name="phone" placeholder="Phone Number"
                class="w-full border p-3 rounded-lg mb-3" required>

            <input type="date" name="date"
                class="w-full border p-3 rounded-lg mb-4" required>

            <button class="w-full bg-teal-600 text-white py-3 rounded-lg hover:bg-teal-700 transition">
                Submit
            </button>
        </form>

    </div>
</div>

<!-- SUCCESS MODAL -->
<div id="successModal"
     style="display:none;"
     class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50">
 
    <div class="w-[90%] max-w-sm p-6 bg-white rounded-2xl shadow-xl text-center">

        <div class="text-green-500 text-5xl mb-3">✅</div>
        <h2 class="text-xl font-bold">Success!</h2>
        <p class="text-gray-600 text-sm mb-4">Request submitted successfully</p>

        <button onclick="closeSuccessModal()"
            class="bg-teal-500 text-white px-5 py-2 rounded-lg">
            OK
        </button>
    </div>
</div>
<script>
function changeImage(imageSrc) {
    document.getElementById('mainImage').src = imageSrc;
}
</script>
@if(session('success'))
    <span id="triggerSuccess" style="display:none;"></span>
@endif

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ✅ Show success modal — server-side only, refresh-safe
    if (document.getElementById('triggerSuccess')) {
        document.getElementById('successModal').style.display = 'flex';
    }

    // ✅ Close visit modal on outside click
    const visitModal = document.getElementById('visitModal');
    if (visitModal) {
        visitModal.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    }

});

function openModal() {
    document.getElementById('visitModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('visitModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none';
}
</script>

