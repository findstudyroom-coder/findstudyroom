@extends('admin.master')

@section('content')
<style>
    .form-control, .form-select {
        background-color: #0f172a !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }
    .form-control:focus, .form-select:focus {
        background-color: #1e293b !important;
        border-color: #f97316 !important;
        color: #ffffff !important;
        box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.2) !important;
    }
    .form-check-input:checked {
        background-color: #22c55e !important;
        border-color: #22c55e !important;
    }
</style>
<div class="container-fluid py-4">

    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('commando-galleries.index') }}" class="text-warning text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i> Back to Studio Gallery
                </a>
            </div>
            <h4 class="fw-bold mb-0 text-white mt-2">Add New Studio Photo</h4>
            <p class="text-muted small mb-0 mt-1">Upload a dojo facility picture, training moment, or sparring photo assigned to a coaching institute.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card bg-dark border-secondary shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('commando-galleries.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    
                    <!-- Section 1: Coaching Assignment -->
                    <div class="col-12">
                        <h6 class="text-warning text-uppercase fw-bold border-bottom border-secondary pb-2 mb-3">
                            <i class="fas fa-graduation-cap me-1"></i> 1. Institute Assignment
                        </h6>
                    </div>

                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label text-warning small fw-bold mb-0">
                                Select Coaching <span class="text-danger">*</span>
                            </label>
                            <a href="{{ route('coachings.create') }}" target="_blank" class="small text-info text-decoration-none">
                                <i class="fas fa-plus-circle me-1"></i> Add New Coaching
                            </a>
                        </div>
                        <select name="coaching_id" class="form-select bg-dark border-secondary text-white fw-bold" required>
                            @foreach($coachings as $coaching)
                                <option value="{{ $coaching->id }}" {{ old('coaching_id', 1) == $coaching->id ? 'selected' : '' }}>
                                    {{ $coaching->coaching_name }} @if($coaching->city) ({{ $coaching->city }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="text-muted small mt-1">This photo will appear in the Studio Gallery of this coaching.</div>
                    </div>

                    <!-- Section 2: Photo Details -->
                    <div class="col-12 mt-4">
                        <h6 class="text-warning text-uppercase fw-bold border-bottom border-secondary pb-2 mb-3">
                            <i class="fas fa-camera me-1"></i> 2. Photo Information &amp; Category
                        </h6>
                    </div>

                    <!-- Title / Caption -->
                    <div class="col-md-7">
                        <label class="form-label text-white small fw-bold">Photo Title / Caption <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="form-control bg-dark border-secondary text-white" placeholder="e.g., Tatami Dojo Floor, Heavy Bag Sparring, Cadet Graduation">
                    </div>

                    <!-- Category -->
                    <div class="col-md-5">
                        <label class="form-label text-white small fw-bold">Category Tag</label>
                        <input type="text" name="category" value="{{ old('category', 'Studio') }}" list="categoryList" class="form-control bg-dark border-secondary text-white" placeholder="e.g., Studio, Dojo, Sparring, Equipment, Training">
                        <datalist id="categoryList">
                            <option value="Studio">
                            <option value="Dojo Floor">
                            <option value="Sparring & Combat">
                            <option value="Tactical Gear">
                            <option value="Youth Cadets">
                            <option value="Belts & Ceremonies">
                        </datalist>
                    </div>

                    <!-- Section 3: Image Upload & Preview -->
                    <div class="col-12 mt-4">
                        <h6 class="text-warning text-uppercase fw-bold border-bottom border-secondary pb-2 mb-3">
                            <i class="fas fa-image me-1"></i> 3. Photo Upload / Image URL
                        </h6>
                    </div>

                    <!-- File Upload -->
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Upload Image File (Recommended)</label>
                        <input type="file" name="image_file" id="imageFileInput" accept="image/*" class="form-control bg-dark border-secondary text-white">
                        <div class="text-muted small mt-1">Supported: JPG, PNG, WEBP. Max size: 5 MB.</div>

                        <div class="mt-3">
                            <label class="form-label text-muted small">Or Enter External Image URL / Path (Optional)</label>
                            <input type="text" name="image" id="imageUrlInput" value="{{ old('image') }}" class="form-control bg-dark border-secondary text-white" placeholder="https://images.unsplash.com/... or uploads/commando/gallery/...">
                        </div>
                    </div>

                    <!-- Live Image Preview -->
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Live Image Preview</label>
                        <div class="d-flex align-items-center justify-content-center p-2 rounded-3 border border-secondary" style="height: 180px; background: #0f172a;">
                            <img id="imagePreview" src="https://images.unsplash.com/photo-1555597673-b21d5c935865?auto=format&fit=crop&w=600&q=80" alt="Preview" style="max-height: 100%; max-width: 100%; object-fit: contain; border-radius: 6px;">
                        </div>
                    </div>

                    <!-- Order No & Status -->
                    <div class="col-md-6 mt-3">
                        <label class="form-label text-white small fw-bold">Display Order</label>
                        <input type="number" name="order_no" value="{{ old('order_no', 0) }}" class="form-control bg-dark border-secondary text-white" placeholder="0">
                        <div class="text-muted small mt-1">Lower numbers display first in the gallery grid.</div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label text-white small fw-bold">Visibility Status</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', '1') ? 'checked' : '' }}>
                            <label class="form-check-label text-success fw-bold" for="status">Active &amp; Visible in Gallery</label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-12 mt-4 pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
                        <a href="{{ route('commando-galleries.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-warning fw-bold px-4 text-dark shadow">
                            <i class="fas fa-save me-1"></i> Save Gallery Photo
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('imageFileInput').addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('imagePreview').src = event.target.result;
        };
        reader.readAsDataURL(this.files[0]);
    }
});

document.getElementById('imageUrlInput').addEventListener('input', function(e) {
    let clean = this.value.trim().replace(/^file:\/\/\/?/i, '');
    this.value = clean;
    if (clean !== '') {
        document.getElementById('imagePreview').src = clean.startsWith('http') ? clean : ('/' + clean.replace(/^\/+/, ''));
    }
});
</script>
@endsection
