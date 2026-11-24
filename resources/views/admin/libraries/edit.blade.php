@extends('admin.master')

@section('content')
<style>
    .premium-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(15px);
    color: #fff;
}

.premium-header {
    background: linear-gradient(135deg,#4f46e5,#8b5cf6);
    color: #fff;
}

.premium-input {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.2);
    color: #fff;
    border-radius: 10px;
}

.premium-input:focus {
    border-color: #8b5cf6;
    box-shadow: 0 0 10px rgba(139,92,246,0.5);
}

.preview-box img {
    width: 90px;
    border-radius: 10px;
    border: 2px solid #6366f1;
}

.premium-btn {
    background: linear-gradient(135deg,#22c55e,#16a34a);
    border: none;
    color: #fff;
}
</style>
<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card premium-card border-0 rounded-4">

                <!-- HEADER -->
                <div class="card-header premium-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📚 Edit Library</h5>
                    <a href="{{ route('libraries.index') }}" class="btn btn-light btn-sm">← Back</a>
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('libraries.update',$library->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">

                            <!-- NAME -->
                            <div class="col-md-6">
                                <label class="form-label">Library Name</label>
                                <input type="text" name="name" value="{{ $library->name }}" class="form-control premium-input">
                            </div>

<div class="col-md-6">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" value="{{ $library->slug }}" class="form-control premium-input" readonly>
                            </div>

                            <!-- IMAGE -->
                            <div class="col-md-6">
    <label class="form-label">Upload Main Image (Logo/Cover)</label>
    <input type="file" name="images[]" multiple class="form-control premium-input">

    <!-- OLD IMAGES -->
    <div class="d-flex flex-wrap mt-3">
        @foreach($library->images ?? [] as $key => $img)
            <div class="me-3 text-center">
                <img src="{{ asset('storage/app/public/'.$img) }}" height="80" class="mb-1">

                <div>
                    <input type="checkbox" name="remove_images[]" value="{{ $img }}">
                    <label style="font-size:12px;">Remove</label>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="col-md-6">
    <label class="form-label">Upload Image Gallery</label>
    <input type="file" name="image_gallery[]" multiple class="form-control premium-input">

    <!-- OLD GALLERY IMAGES -->
    @php
        $galleryImages = is_string($library->image_gallery) ? json_decode($library->image_gallery, true) : ($library->image_gallery ?? []);
    @endphp
    <div class="d-flex flex-wrap mt-3">
        @foreach($galleryImages as $key => $img)
            <div class="me-3 text-center">
                <img src="{{ asset('storage/app/public/'.$img) }}" height="80" class="mb-1" style="object-fit:cover; border-radius:6px; border:2px solid #8b5cf6;">

                <div>
                    <input type="checkbox" name="remove_gallery_images[]" value="{{ $img }}">
                    <label style="font-size:12px;">Remove</label>
                </div>
            </div>
        @endforeach
    </div>
</div>

                            <!-- LOCATION -->
                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" value="{{ $library->location }}" class="form-control premium-input">
                            </div>

 <div class="col-md-6">
                                <label class="form-label">Pincode</label>
                                <input type="text" name="pincode" value="{{ $library->pincode }}" class="form-control premium-input">
                            </div>



 <div class="col-md-6">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude"  value="{{ $library->longitude }}" 
                            class="form-control custom-input @error('longitude') is-invalid @enderror">
                       
                    </div>

                    <!-- Pincode -->
                    <div class="col-md-6">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" value="{{ $library->latitude }}" 
                            class="form-control custom-input @error('latitude') is-invalid @enderror">
                      
                    </div>



                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" value="{{ $library->phone }}" class="form-control premium-input">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">WhatsApp Number</label>
                                <input type="text" name="whatsapp_number" value="{{ $library->whatsapp_number }}" class="form-control premium-input">
                            </div>

                            <!-- CITY -->
                            <div class="col-md-6">
    <label class="form-label">State</label>
    <select name="state_id" id="state" class="form-control premium-input">
        <option value="">Select State</option>
        @foreach($state as $s)
            <option value="{{ $s->id }}" 
                {{ $library->state_id == $s->id ? 'selected' : '' }}>
                {{ $s->name }}
            </option>
        @endforeach
    </select>
</div>
<div class="col-md-6">
    <label class="form-label">District</label>
    <select name="city_id" id="district" class="form-control premium-input">
        <option value="">Select District</option>

        @foreach($district as $d)
            @if($d->state_id == $library->state_id)
                <option value="{{ $d->id }}" 
                    {{ $library->city_id == $d->id ? 'selected' : '' }}>
                    {{ $d->name }}
                </option>
            @endif
        @endforeach

    </select>
</div>

                            <!-- PRICE -->
                            <div class="col-md-4">
                                <label class="form-label">Price</label>
                                <input type="number" name="price" value="{{ $library->price }}" class="form-control premium-input">
                            </div>

                            <!-- RATING -->
                            <div class="col-md-4">
                                <label class="form-label">Rating</label>
                                <input type="text" name="rating" value="{{ $library->rating }}" class="form-control premium-input">
                            </div>

                            
                     <div class="col-md-12">
                        <label class="form-label">Descriptions</label>
                        <textarea name="descriptions" class="form-control premium-input" rows="4">{{ $library->descriptions }}</textarea>
                    </div>



                            <!-- STATUS -->
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control premium-input">
                                    <option value="open" {{ $library->status=='open'?'selected':'' }}>🟢 Open</option>
                                    <option value="closed" {{ $library->status=='closed'?'selected':'' }}>🔴 Closed</option>
                                </select>
                            </div>

                            <!-- IS VERIFIED -->
                            <div class="col-md-12 mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_verified" id="is_verified" {{ $library->is_verified ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-success" for="is_verified">✅ Premium Verified Library</label>
                                </div>
                            </div>

                            <!-- TIME -->
                            <div class="col-md-6">
                                <label class="form-label">Open Time</label>
                                <input type="time" name="open_time" value="{{ $library->open_time }}" class="form-control premium-input">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Close Time</label>
                                <input type="time" name="close_time" value="{{ $library->close_time }}" class="form-control premium-input">
                            </div>

                            <!-- FACILITIES -->
                            <!--<div class="col-12">-->
                            <!--    <label class="form-label fw-bold">Facilities</label>-->

                            <!--    <div id="facility-wrapper">-->
                            <!--        @foreach($library->facilities ?? [] as $facility)-->
                            <!--        <div class="input-group mb-2">-->
                            <!--            <input type="text" name="facilities[]" class="form-control premium-input" value="{{ $facility }}">-->
                            <!--            <button type="button" class="btn btn-danger remove">✖</button>-->
                            <!--        </div>-->
                            <!--        @endforeach-->
                            <!--    </div>-->

                            <!--    <button type="button" id="add-facility" class="btn btn-outline-info btn-sm mt-2">-->
                            <!--        ➕ Add Facility-->
                            <!--    </button>-->
                            <!--</div>-->
                            <div class="col-12">
    <label class="form-label fw-bold">Facilities</label>

    @php
        $mustHave = ['WiFi','Air Conditioning (AC)','Silent Environment','Proper Lighting','Comfortable Seating'];
        $goodToHave = ['Charging Points','Personal Desk','Clean Washroom','Drinking Water','Flexible Timing'];
        $premium = ['Cabin Seating','Refreshment Area','Locker Facility','CCTV Security'];

        $selectedFacilities = $library->facilities ?? [];
    @endphp

    <!-- MUST HAVE -->
    <p class="mb-1 fw-bold text-info">MUST-HAVE</p>
    <div class="d-flex flex-wrap gap-2">
        @foreach($mustHave as $item)
            <button type="button"
                class="btn btn-outline-success facility-btn {{ in_array($item,$selectedFacilities) ? 'active' : '' }}"
                data-value="{{ $item }}">
                {{ $item }}
            </button>
        @endforeach
    </div>

    <!-- GOOD TO HAVE -->
    <p class="mt-3 mb-1 fw-bold text-warning">GOOD TO HAVE</p>
    <div class="d-flex flex-wrap gap-2">
        @foreach($goodToHave as $item)
            <button type="button"
                class="btn btn-outline-warning facility-btn {{ in_array($item,$selectedFacilities) ? 'active' : '' }}"
                data-value="{{ $item }}">
                {{ $item }}
            </button>
        @endforeach
    </div>

    <!-- PREMIUM -->
    <p class="mt-3 mb-1 fw-bold text-primary">PREMIUM</p>
    <div class="d-flex flex-wrap gap-2">
        @foreach($premium as $item)
            <button type="button"
                class="btn btn-outline-primary facility-btn {{ in_array($item,$selectedFacilities) ? 'active' : '' }}"
                data-value="{{ $item }}">
                {{ $item }}
            </button>
        @endforeach
    </div>

    <!-- Selected Output -->
    <div id="facility-wrapper" class="mt-3">
        @foreach($selectedFacilities as $facility)
            <div class="input-group mb-2">
                <input type="text" name="facilities[]" value="{{ $facility }}" class="form-control premium-input" readonly>
                <button type="button" class="btn btn-danger remove">✖</button>
            </div>
        @endforeach
    </div>

</div>

                        </div>

                        <div class="text-end mt-4">
                            <button class="btn premium-btn px-4">💾 Update Library</button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
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
</script>

<script>
document.addEventListener('DOMContentLoaded', function(){

    // CLICK FACILITY BUTTON
    document.addEventListener('click', function(e){

        if(e.target.classList.contains('facility-btn')){

            let value = e.target.getAttribute('data-value');

            // already exists → remove
            let existing = document.querySelector(`input[value="${value}"]`);
            if(existing){
                existing.closest('.input-group').remove();
                e.target.classList.remove('active');
                return;
            }

            // add new
            let html = `
            <div class="input-group mb-2">
                <input type="text" name="facilities[]" value="${value}" class="form-control premium-input" readonly>
                <button type="button" class="btn btn-danger remove">✖</button>
            </div>`;

            document.getElementById('facility-wrapper').insertAdjacentHTML('beforeend', html);

            e.target.classList.add('active');
        }

        // REMOVE BUTTON
        if(e.target.classList.contains('remove')){
            let value = e.target.previousElementSibling.value;

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
@endsection
// <script>
// document.addEventListener('DOMContentLoaded', function(){

//     document.getElementById('add-facility').addEventListener('click', function(){
//         let html = `
//         <div class="input-group mb-2">
//             <input type="text" name="facilities[]" class="form-control premium-input" placeholder="Enter facility">
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
