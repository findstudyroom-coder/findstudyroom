@extends('admin.master')

@section('content')

<div class="container-fluid">

    <div class="card shadow-lg border-0 rounded-4 bg-dark text-white">

        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center text-white rounded-top-4"
             style="background: linear-gradient(135deg,#6366f1,#8b5cf6);">
            <h5 class="mb-0">📚 Add Library</h5>
        </div>

        <div class="card-body p-4">

            <!-- ✅ Global Error -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>⚠ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('libraries.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label">Library Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="form-control custom-input @error('name') is-invalid @enderror">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div class="col-md-6">
                        <label class="form-label">Upload Main Image (Logo/Cover)</label>
                        <input type="file" name="images[]" multiple
                            class="form-control custom-input @error('images.*') is-invalid @enderror">
                        @error('images.*')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <!-- Image Gallery -->
                    <div class="col-md-6">
                        <label class="form-label">Upload Image Gallery</label>
                        <input type="file" name="image_gallery[]" multiple
                            class="form-control custom-input @error('image_gallery.*') is-invalid @enderror">
                        @error('image_gallery.*')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="col-md-6">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}"
                            class="form-control custom-input @error('location') is-invalid @enderror">
                        @error('location')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                      <div class="col-md-6">
                        <label class="form-label">Pincode</label>
                        <input type="text" name="pincode" value="{{ old('pincode') }}"
                            class="form-control custom-input @error('pincode') is-invalid @enderror">
                        @error('pincode')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                      <div class="col-md-6">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" value="{{ old('longitude') }}"
                            class="form-control custom-input @error('longitude') is-invalid @enderror">
                        @error('longitude')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Pincode -->
                    <div class="col-md-6">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" value="{{ old('latitude') }}"
                            class="form-control custom-input @error('latitude') is-invalid @enderror">
                        @error('latitude')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="form-control custom-input @error('phone') is-invalid @enderror">
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}"
                            class="form-control custom-input @error('whatsapp_number') is-invalid @enderror">
                        @error('whatsapp_number')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- City -->
                    <div class="col-md-6">
    <label class="form-label">State</label>
    <select name="state_id" id="state" class="form-control">
        <option value="">Select State</option>
        @foreach($state as $s)
            <option value="{{ $s->id }}">{{ $s->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-6">
    <label class="form-label">District</label>
    <select name="city_id" id="district" class="form-control">
        <option value="">Select District</option>
    </select>
</div>

                    <!-- Price -->
                    <div class="col-md-4">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" name="price" value="{{ old('price') }}"
                            class="form-control custom-input @error('price') is-invalid @enderror">
                        @error('price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Rating -->
                    <div class="col-md-4">
                        <label class="form-label">Rating ⭐</label>
                        <input type="text" name="rating" value="{{ old('rating') }}"
                            class="form-control custom-input @error('rating') is-invalid @enderror">
                        @error('rating')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <label class="form-label">Descriptions</label>
                        <textarea name="descriptions"
                            class="form-control custom-input @error('descriptions') is-invalid @enderror" rows="4">{{ old('descriptions') }}</textarea>
                        @error('descriptions')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status"
                            class="form-control custom-input @error('status') is-invalid @enderror">
                            <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>🟢 Open</option>
                            <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>🔴 Closed</option>
                        </select>
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- IS VERIFIED -->
                    <div class="col-md-12 mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_verified" id="is_verified">
                            <label class="form-check-label fw-bold text-success" for="is_verified">✅ Premium Verified Library</label>
                        </div>
                    </div>

                    <!-- Open Time -->
                    <div class="col-md-6">
                        <label class="form-label">Open Time</label>
                        <input type="time" name="open_time" value="{{ old('open_time') }}"
                            class="form-control custom-input @error('open_time') is-invalid @enderror">
                        @error('open_time')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Close Time -->
                    <div class="col-md-6">
                        <label class="form-label">Close Time</label>
                        <input type="time" name="close_time" value="{{ old('close_time') }}"
                            class="form-control custom-input @error('close_time') is-invalid @enderror">
                        @error('close_time')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Facilities -->
                    <!--<div class="col-12">-->
                    <!--    <label class="form-label fw-bold">Facilities</label>-->

                    <!--    <div id="facility-wrapper">-->
                    <!--        @if(old('facilities'))-->
                    <!--            @foreach(old('facilities') as $facility)-->
                    <!--                <div class="input-group mb-2">-->
                    <!--                    <input type="text" name="facilities[]" value="{{ $facility }}" class="form-control custom-input">-->
                    <!--                    <button type="button" class="btn btn-danger remove">✖</button>-->
                    <!--                </div>-->
                    <!--            @endforeach-->
                    <!--        @else-->
                    <!--            <div class="input-group mb-2">-->
                    <!--                <input type="text" name="facilities[]" class="form-control custom-input">-->
                    <!--                <button type="button" class="btn btn-danger remove">✖</button>-->
                    <!--            </div>-->
                    <!--        @endif-->
                    <!--    </div>-->

                    <!--    @error('facilities.*')-->
                    <!--        <small class="text-danger">{{ $message }}</small>-->
                    <!--    @enderror-->

                    <!--    <button type="button" id="add-facility" class="btn btn-outline-info btn-sm mt-2">-->
                    <!--        ➕ Add More-->
                    <!--    </button>-->
                    <!--</div>-->
                    
                    <div class="col-12">
    <label class="form-label fw-bold">Facilities</label>

    <!-- Predefined Facility Buttons -->
    <div class="mb-3">
        <p class="mb-1 fw-bold text-info">MUST-HAVE</p>
        <div class="d-flex flex-wrap gap-2">
            @php
                $mustHave = ['WiFi','Air Conditioning (AC)','Silent Environment','Proper Lighting','Comfortable Seating'];
                $goodToHave = ['Charging Points','Personal Desk','Clean Washroom','Drinking Water','Flexible Timing'];
                $premium = ['Cabin Seating','Refreshment Area','Locker Facility','CCTV Security'];
            @endphp

            @foreach($mustHave as $item)
                <button type="button" class="btn btn-outline-success facility-btn" data-value="{{ $item }}">📶 {{ $item }}</button>
            @endforeach
        </div>

        <p class="mt-3 mb-1 fw-bold text-warning">GOOD TO HAVE</p>
        <div class="d-flex flex-wrap gap-2">
            @foreach($goodToHave as $item)
                <button type="button" class="btn btn-outline-warning facility-btn" data-value="{{ $item }}">🟡 {{ $item }}</button>
            @endforeach
        </div>

        <p class="mt-3 mb-1 fw-bold text-primary">PREMIUM</p>
        <div class="d-flex flex-wrap gap-2">
            @foreach($premium as $item)
                <button type="button" class="btn btn-outline-primary facility-btn" data-value="{{ $item }}">🔵 {{ $item }}</button>
            @endforeach
        </div>
    </div>

    <!-- Selected Facilities -->
    <div id="facility-wrapper"></div>

    @error('facilities.*')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

                </div>

                <div class="text-end mt-4">
                    <button class="btn btn-success px-4 shadow">💾 Save Library</button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('state').addEventListener('change', function () {

        let stateId = this.value;

        fetch('/get-districts/' + stateId)
            .then(res => res.json())
            .then(data => {

                let districtDropdown = document.getElementById('district');
                districtDropdown.innerHTML = '<option value="">Select District</option>';

                data.forEach(d => {
                    districtDropdown.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                });

            });

    });

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Add facility on button click
    document.addEventListener('click', function(e){
        if(e.target.classList.contains('facility-btn')){

            let value = e.target.getAttribute('data-value');

            // prevent duplicate
            if(document.querySelector(`input[value="${value}"]`)){
                return;
            }

            let html = `
            <div class="input-group mb-2">
                <input type="text" name="facilities[]" value="${value}" class="form-control custom-input" readonly>
                <button type="button" class="btn btn-danger remove">✖</button>
            </div>`;

            document.getElementById('facility-wrapper').insertAdjacentHTML('beforeend', html);

            // highlight selected
            e.target.classList.add('active');
        }

        // remove
        if(e.target.classList.contains('remove')){
            let value = e.target.previousElementSibling.value;

            // un-highlight button
            document.querySelectorAll('.facility-btn').forEach(btn => {
                if(btn.getAttribute('data-value') === value){
                    btn.classList.remove('active');
                }
            });

            e.target.closest('.input-group').remove();
        }
    });

});
</script>
// <script>
// document.addEventListener('DOMContentLoaded', function () {

//     document.getElementById('add-facility').addEventListener('click', function () {

//         let html = `
//         <div class="input-group mb-2">
//             <input type="text" name="facilities[]" class="form-control custom-input" placeholder="Enter facility">
//             <button type="button" class="btn btn-danger remove">✖</button>
//         </div>`;

//         document.getElementById('facility-wrapper').insertAdjacentHTML('beforeend', html);
//     });

//     document.addEventListener('click', function(e){
//         if(e.target.classList.contains('remove')){
//             e.target.closest('.input-group').remove();
//         }
//     });

// });
// </script>