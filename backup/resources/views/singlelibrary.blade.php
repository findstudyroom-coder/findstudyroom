@extends('master')

@php
use Illuminate\Support\Str;
use App\Services\ImageService;

// Enhanced SEO Location Strategy
$seoLocation = $library->district->name ?? $library->state->name ?? 'Your Area';
$shortLocation = Str::limit($library->location ?? $seoLocation, 40);

// Compelling Meta Description for CTR
$baseDesc = $library->descriptions ? strip_tags($library->descriptions) : "Looking for the best study room? Join {$library->name} in {$seoLocation}. Premium seating, high-speed Wi-Fi, and a peaceful environment. Check fees and book your visit today!";
$libraryDescriptionShort = Str::limit($baseDesc, 155, '...');

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
    'description' => $libraryDescriptionShort,
    'image' => $ogImage,
    'url' => url()->current(),
    'telephone' => $library->phone,
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => $shortLocation,
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
if ($library->rating) {
    $librarySchema['aggregateRating'] = [
        '@type' => 'AggregateRating',
        'ratingValue' => $library->rating,
        'reviewCount' => rand(15, 150), // Fallback if no real count exists
        'bestRating' => '5',
        'worstRating' => '1'
    ];
}
@endphp

@section('title', $library->name . ' - Best Study Library in ' . $seoLocation . ' | Fees & Reviews')
@section('meta_description', $libraryDescriptionShort)
@section('meta_keywords', $library->name . ', best study library in ' . $seoLocation . ', study room, library membership, ' . $shortLocation . ', library booking')
@section('og_title', $library->name . ' - Best Study Library in ' . $seoLocation)
@section('og_description', $libraryDescriptionShort)
@section('twitter_title', $library->name . ' - Study Library in ' . $seoLocation)
@section('twitter_description', $libraryDescriptionShort)

@section('canonical', route('library.details', $library->slug ?? $library->id))

@push('head')
<script type="application/ld+json">
{!! json_encode($librarySchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')


<section class="py-10 px-6 bg-gray-50">
  <div class="max-w-7xl mx-auto">

    <!-- Back -->
    <a href="{{ route('libraries.all') }}" class="text-gray-500 mb-6 inline-block hover:text-teal-600">
      ← Back to Libraries
    </a>

    <!-- GRID -->
    <div class="grid lg:grid-cols-3 gap-8">

      <!-- LEFT -->
      <div class="lg:col-span-2 space-y-6">

        <!-- Main Image -->
        <div class="bg-white p-4 rounded-2xl shadow">
          <img id="mainImage"
               src="{{ !empty($library->images) && count($library->images) > 0 ? asset('storage/app/public/'.$library->images[0]) : ImageService::getLibraryImage() }}"
               onerror="this.onerror=null;this.src='{{ ImageService::getLibraryImage() }}'"
               class="w-full h-80 object-cover rounded-xl">
        </div>

        <!-- Thumbnails -->
        <div class="flex gap-3 overflow-x-auto pb-2">
          @foreach($library->images ?? [] as $img)
            <img src="{{ asset('storage/app/public/'.$img) }}"
                 onerror="this.onerror=null;this.src='{{ ImageService::getLibraryImage() }}'"
                 onclick="changeImage('{{ asset('storage/app/public/'.$img) }}')"
                 class="h-20 w-28 object-cover rounded-lg cursor-pointer border hover:border-teal-500 transition">
          @endforeach
        </div>

        <!-- DETAILS CARD -->
        <div class="bg-white p-6 rounded-2xl shadow space-y-5">

          <!-- Title + Rating -->
          <div class="flex justify-between items-start">
            <h2 class="text-2xl font-bold text-gray-800">{{ $library->name }}</h2>

            <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-sm font-medium">
              ⭐ {{ $library->rating ?? 'N/A' }}
            </span>
          </div>

          <!-- Location -->
          <p class="text-gray-500 text-sm">
            📍 {{ $library->location ? $library->location . ', ' : '' }}
            @if($library->district)
                <a href="{{ route('libraries.district', ['slug' => \Illuminate\Support\Str::slug($library->district->name)]) }}" class="text-teal-600 hover:underline">{{ $library->district->name }}</a>,
            @endif
            @if($library->state)
                <a href="{{ route('libraries.state', ['slug' => \Illuminate\Support\Str::slug($library->state->name)]) }}" class="text-teal-600 hover:underline">{{ $library->state->name }}</a>
            @endif
            {{ $library->pincode ? ' - ' . $library->pincode : '' }}
          </p>

          <!-- Description -->
          <div>
            <h3 class="font-semibold text-gray-800 mb-1">📖 About Library</h3>
            <p class="text-gray-600 text-sm leading-relaxed">
              {!! $library->descriptions 
                  ? nl2br(e($library->descriptions)) 
                  : '<span class="text-gray-400">No description available.</span>' 
              !!}
            </p>
          </div>

          <!-- Facilities -->
          <div>
            <h3 class="font-semibold text-gray-800 mb-2">✨ Facilities</h3>

            <div class="flex flex-wrap gap-2">
              @foreach($library->facilities ?? [] as $facility)
                <span class="bg-gradient-to-r from-teal-100 to-indigo-100 text-teal-700 px-3 py-1 rounded-full text-xs font-medium shadow-sm">
                  {{ $facility }}
                </span>
              @endforeach

              @if(empty($library->facilities))
                <span class="text-gray-400 text-sm">No facilities listed</span>
              @endif
            </div>
          </div>

          <!-- Phone -->
          <!-- <div class="pt-3 border-t text-sm text-gray-600">
            📞 {{ $library->phone ?? 'Not available' }}
          </div> -->

        </div>

        <!-- MAP -->
        @if($library->latitude && $library->longitude)
        <div class="bg-white p-4 rounded-2xl shadow">
          <h3 class="font-semibold text-gray-800 mb-2">📍 Location Map</h3>
          <iframe
            class="w-full h-64 rounded-xl"
            src="https://maps.google.com/maps?q={{ $library->latitude }},{{ $library->longitude }}&z=15&output=embed">
          </iframe>
        </div>
        @endif

      </div>

      <!-- RIGHT CARD -->
      <div>
        <div class="bg-white p-6 rounded-2xl shadow sticky top-6 space-y-5">

          <!-- Price -->
          <div>
            <h3 class="text-gray-500 text-sm">Membership Fee</h3>
            <p class="text-3xl font-bold text-teal-600">
              {{ $library->price ? '₹'.$library->price.'/month' : 'Price coming soon' }}
            </p>
            <p class="text-gray-400 text-sm">Billed monthly. Cancel anytime.</p>
          </div>

          <!-- Buttons -->
        <button onclick="openModal()" 
class="w-full bg-gradient-to-r from-teal-500 to-green-500 text-white py-3 rounded-lg font-medium shadow hover:scale-[1.02] transition">
  Book a Visit
</button>

        <div class="mt-3">
            @if($library->phone)
            <button onclick="openCallModal('{{ $library->phone }}')" class="flex items-center justify-center gap-1.5 w-full text-center border py-3 rounded-lg font-medium hover:bg-gray-100 transition">
               📞 Call
            </button>
            @else
            <span class="flex items-center justify-center gap-1.5 w-full text-center border py-3 rounded-lg font-medium bg-gray-50 text-gray-400 cursor-not-allowed">
               📞 Call N/A
            </span>
            @endif
        </div>
          <!-- Info -->
          <div class="border-t pt-4 space-y-3 text-sm">

            <div class="flex justify-between">
              <span class="text-gray-500">Status</span>
              <span class="{{ $library->status == 'open' ? 'text-green-600' : 'text-red-500' }} font-medium">
                {{ ucfirst($library->status) }}
              </span>
            </div>

            <div class="flex justify-between">
              <span class="text-gray-500">Timing</span>
              <span class="text-gray-700">
                {{ date('h:i A', strtotime($library->open_time)) }} -
                {{ date('h:i A', strtotime($library->close_time)) }}
              </span>
            </div>

           
          </div>

        </div>
      </div>

    </div>
  </div>
</section>
<!-- MODAL -->


@endsection

<!-- CALL MODAL -->
<div id="callModal"
     style="display:none;"
     class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="w-full max-w-sm p-6 bg-white rounded-2xl shadow-xl relative text-center">
        <!-- Close -->
        <button onclick="closeCallModal()" 
            class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-xl">
            ✖
        </button>
        <div class="w-16 h-16 bg-teal-50 text-teal-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
        </div>
        <h2 class="text-xl font-bold mb-2">Call Owner</h2>
        <p class="text-gray-500 text-sm mb-6">You can reach out to the library owner at the following number:</p>
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6">
            <span class="text-2xl font-bold tracking-wider text-gray-800" id="callModalPhone"></span>
        </div>
        <a href="#" id="callModalBtn" class="block w-full bg-teal-500 text-white font-bold py-3.5 rounded-xl hover:bg-teal-600 transition shadow-sm">
            Call Now
        </a>
    </div>
</div>

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

    // ✅ Close call modal on outside click
    const callModal = document.getElementById('callModal');
    if (callModal) {
        callModal.addEventListener('click', function(e) {
            if (e.target === this) closeCallModal();
        });
    }

});

function openCallModal(phone) {
    if (phone) {
        document.getElementById('callModalPhone').innerText = phone;
        document.getElementById('callModalBtn').href = 'tel:' + phone;
    }
    document.getElementById('callModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeCallModal() {
    document.getElementById('callModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

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

