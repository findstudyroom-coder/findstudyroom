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
                <a href="{{ route('commando-plans.index') }}" class="text-warning text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i> Back to Membership Plans
                </a>
            </div>
            <h4 class="fw-bold mb-0 text-white mt-2">Add New Membership Plan</h4>
            <p class="text-muted small mb-0 mt-1">Configure pricing tier, perks list, button action, and theme styling for the coaching institute.</p>
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
            <form action="{{ route('commando-plans.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    
                    <!-- Section 1: Coaching Selection -->
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
                        <div class="text-muted small mt-1">This plan will be displayed on this coaching's landing page.</div>
                    </div>

                    <!-- Section 2: Plan Identity & Pricing -->
                    <div class="col-12 mt-4">
                        <h6 class="text-warning text-uppercase fw-bold border-bottom border-secondary pb-2 mb-3">
                            <i class="fas fa-tags me-1"></i> 2. Plan Identity &amp; Pricing
                        </h6>
                    </div>

                    <!-- Title -->
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Plan Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="form-control bg-dark border-secondary text-white" placeholder="e.g., Cadet Pass, Unlimited Dojo, Black Belt Elite">
                    </div>

                    <!-- Tagline -->
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Subtitle / Tagline</label>
                        <input type="text" name="tag" value="{{ old('tag') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., 2 DAYS / WEEK, UNLIMITED SESSIONS">
                    </div>

                    <!-- Subtitle / Short description -->
                    <div class="col-12">
                        <label class="form-label text-white small fw-bold">Short Description / Target Audience</label>
                        <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., Perfect for developing discipline, foundational kata, and beginner belt mastery.">
                    </div>

                    <!-- Price -->
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Price <span class="text-danger">*</span></label>
                        <input type="text" name="price" value="{{ old('price', '₹2,499') }}" required class="form-control bg-dark border-secondary text-white" placeholder="e.g., ₹2,499, ₹3,999, Free">
                    </div>

                    <!-- Billing Period -->
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Billing Period</label>
                        <input type="text" name="billing_period" value="{{ old('billing_period', '/month') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., /month, /quarter, /year, /session">
                    </div>

                    <!-- Button Text -->
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Call To Action Button Text</label>
                        <input type="text" name="button_text" value="{{ old('button_text', 'Select Plan') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., Select Plan, Enroll Today">
                    </div>

                    <!-- Section 3: Features & Styling -->
                    <div class="col-12 mt-4">
                        <h6 class="text-warning text-uppercase fw-bold border-bottom border-secondary pb-2 mb-3">
                            <i class="fas fa-list-check me-1"></i> 3. Features, Perks &amp; Visual Appearance
                        </h6>
                    </div>

                    <!-- Features -->
                    <div class="col-12">
                        <label class="form-label text-white small fw-bold">Perks &amp; Features Included</label>
                        <textarea name="features" rows="5" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="Enter one feature per line, for example:&#10;Access to 8 Dojo Sessions / Month&#10;Official White Belt Gi Provided&#10;Bi-weekly Biomechanics Assessment&#10;Dojo Locker Access">{{ old('features', "Access to Scheduled Sessions\nOfficial Training Uniform\nMonthly Progress Assessment\nLocker Room & Shower Access") }}</textarea>
                        <div class="text-muted small mt-1"><i class="fas fa-info-circle text-info me-1"></i> Tip: Enter each feature or perk on a new line. They will be rendered with checkmarks on the public card.</div>
                    </div>

                    <!-- Color Theme -->
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Card Theme / Color Accent</label>
                        <select name="color_theme" class="form-select bg-dark border-secondary text-white">
                            <option value="slate" {{ old('color_theme', 'slate') === 'slate' ? 'selected' : '' }}>Slate (Clean / Standard)</option>
                            <option value="amber" {{ old('color_theme') === 'amber' ? 'selected' : '' }}>Amber (Gold / Most Popular Accent)</option>
                            <option value="blue" {{ old('color_theme') === 'blue' ? 'selected' : '' }}>Blue (Vibrant Gradient / Premium)</option>
                        </select>
                    </div>

                    <!-- Featured Flag -->
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Featured / Highlighted Plan</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label text-light" for="is_featured">Highlight as Most Popular</label>
                        </div>
                    </div>

                    <!-- Featured Badge Text -->
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Featured Badge Text</label>
                        <input type="text" name="featured_badge" value="{{ old('featured_badge', 'MOST POPULAR') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., MOST POPULAR, BEST VALUE">
                    </div>

                    <!-- Order No & Status -->
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Display Order</label>
                        <input type="number" name="order_no" value="{{ old('order_no', 0) }}" class="form-control bg-dark border-secondary text-white" placeholder="0">
                        <div class="text-muted small mt-1">Lower numbers appear first.</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Visibility Status</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', '1') ? 'checked' : '' }}>
                            <label class="form-check-label text-success fw-bold" for="status">Active &amp; Visible to Public</label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-12 mt-4 pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
                        <a href="{{ route('commando-plans.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-warning fw-bold px-4 text-dark shadow">
                            <i class="fas fa-save me-1"></i> Save Membership Plan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
