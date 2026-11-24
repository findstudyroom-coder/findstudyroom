 @extends('master')
 
 @php
 use App\Services\ImageService;
 @endphp
 
 @section('content')

<section class="pt-24 pb-12 px-6">
<div class="max-w-7xl mx-auto">

<h2 class="text-2xl font-bold mb-6">📚 All Libraries</h2>

<div id="library-container" class="grid md:grid-cols-3 gap-6">

@foreach($libraries as $lib)

<div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">

    <a href="{{ route('library.details',$lib->slug) }}">
        <img src="{{ !empty($lib->images) && count($lib->images) > 0 ? asset('storage/app/public/'.$lib->images[0]) : ImageService::getLibraryImage() }}"
             class="w-full h-48 object-cover hover:scale-105 transition duration-500">
    </a>

    <div class="p-4 space-y-2">

        <!-- Name -->
        <h3 class="font-semibold text-lg">{{ $lib->name }}</h3>

        <!-- Location -->
        <p class="text-gray-500 text-sm">📍 {{ $lib->location }} - {{ $lib->pincode }}</p>

        <!-- Rating -->
        <p class="text-yellow-500 text-sm">⭐ {{ $lib->rating ?? 'N/A' }}</p>

        <!-- Price + Status -->
        <div class="flex justify-between">
            <span class="text-teal-600 font-semibold">₹{{ $lib->price }}</span>

            <span class="{{ $lib->status=='open' ? 'text-green-600' : 'text-red-500' }}">
                {{ ucfirst($lib->status) }}
            </span>
        </div>

        <!-- Timing -->
        <p class="text-gray-600 text-sm">
            ⏰ {{ date('h:i A', strtotime($lib->open_time)) }} - 
            {{ date('h:i A', strtotime($lib->close_time)) }}
        </p>

        <!-- Phone -->
        <!-- <p class="text-gray-600 text-sm">📞 {{ $lib->phone ?? 'N/A' }}</p> -->

        <!-- Facilities -->
        
        <!-- Description -->
      
    </div>

</div>

@endforeach

</div>

<!-- LOAD MORE BUTTON -->


<script>
if (typeof data !== 'undefined' && Array.isArray(data)) {
    data.forEach(function (lib) {

    let defaultImage = '{{ \App\Services\ImageService::getLibraryImage() }}';
    let image = (lib.images && lib.images.length > 0)
        ? `/storage/app/public/${lib.images[0]}`
        : defaultImage;

    let facilitiesHtml = '';
    if(lib.facilities){
        lib.facilities.forEach(f => {
            facilitiesHtml += `<span class="text-xs bg-indigo-100 text-indigo-600 px-2 py-1 rounded-full">${f}</span>`;
        });
    }

    $('#library-container').append(`
        <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">

            <a href="/library/${lib.slug}">
                <img src="${image}" class="w-full h-48 object-cover">
            </a>

            <div class="p-4 space-y-2">

                <h3 class="font-semibold text-lg">${lib.name}</h3>

                <p class="text-gray-500 text-sm">📍 ${lib.location} - ${lib.pincode}</p>

                <p class="text-yellow-500 text-sm">⭐ ${lib.rating ?? 'N/A'}</p>

                <div class="flex justify-between">
                    <span class="text-teal-600 font-semibold">₹${lib.price}</span>
                    <span class="${lib.status=='open' ? 'text-green-600' : 'text-red-500'}">
                        ${lib.status}
                    </span>
                </div>

                <p class="text-gray-600 text-sm">
                    ⏰ ${lib.open_time} - ${lib.close_time}
                </p>

                <p class="text-gray-500 text-xs mt-2">
                    ${lib.descriptions ?? ''}
                </p>

            </div>
        </div>
    `);
});
}
</script>

