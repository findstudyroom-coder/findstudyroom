@extends('admin.master')

@section('content')
<div class="container-fluid py-4">

    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('commando-classes.index') }}" class="text-warning text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i> Back to The Movement Disciplines
                </a>
            </div>
            <h4 class="fw-bold mb-0 text-white mt-2">Add New Discipline</h4>
            <p class="text-muted small mb-0 mt-1">Configure curriculum discipline, duration, instructor, and dojo operational parameters for The Movement.</p>
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
            <form action="{{ route('commando-classes.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    
                    <!-- Section 1: Core Discipline Identity -->
                    <div class="col-12">
                        <h6 class="text-warning text-uppercase fw-bold border-bottom border-secondary pb-2 mb-3">
                            <i class="fas fa-fist-raised me-1"></i> 1. Discipline &amp; Curriculum Identity
                        </h6>
                    </div>

                    <!-- Coaching Selection -->
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label text-warning small fw-bold mb-0">
                                <i class="fas fa-graduation-cap me-1"></i> Select Coaching <span class="text-danger">*</span>
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
                        <div class="text-muted small mt-1">Select which coaching institute (e.g. The Movement, The Achievers) this class belongs to.</div>
                    </div>

                    <!-- Title -->
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Class Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="form-control bg-dark border-secondary text-white" placeholder="e.g., Shotokan Karate, Kodokan Judo, Brazilian Jiu-Jitsu">
                    </div>

                    <!-- Tag / Category -->
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Tagline / Category Banner</label>
                        <input type="text" name="tag" value="{{ old('tag') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., DISCIPLINE & STRIKING, LEVERAGE & THROWS">
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label class="form-label text-white small fw-bold">Curriculum Description &amp; Philosophy</label>
                        <textarea name="description" rows="3" class="form-control bg-dark border-secondary text-white" placeholder="Describe the martial art or movement focus, biomechanical training, and student outcomes...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Duration & Level -->
                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Session Duration</label>
                        <input type="text" name="duration" value="{{ old('duration', '60 MIN') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., 60 MIN, 75 MIN">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Belt Tier / Level</label>
                        <input type="text" name="level" value="{{ old('level', 'ALL BELT TIERS') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., ALL BELT TIERS, TACTICAL TACTILITY">
                    </div>

                    <!-- Color Theme -->
                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Card Color Theme</label>
                        <select name="color_theme" class="form-select bg-dark border-secondary text-white">
                            <option value="blue" {{ old('color_theme') === 'blue' ? 'selected' : '' }}>Blue (Default / Traditional)</option>
                            <option value="amber" {{ old('color_theme') === 'amber' ? 'selected' : '' }}>Amber (Warm / Judo Gold)</option>
                            <option value="teal" {{ old('color_theme') === 'teal' ? 'selected' : '' }}>Teal (Mobility / Yoga)</option>
                            <option value="emerald" {{ old('color_theme') === 'emerald' ? 'selected' : '' }}>Emerald (Green / Recovery)</option>
                            <option value="purple" {{ old('color_theme') === 'purple' ? 'selected' : '' }}>Purple (Advanced / Master)</option>
                            <option value="rose" {{ old('color_theme') === 'rose' ? 'selected' : '' }}>Rose (High Intensity / Combat)</option>
                        </select>
                    </div>

                    <!-- Icon Preset -->
                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Icon Preset or Custom SVG</label>
                        <select name="icon" class="form-select bg-dark border-secondary text-white">
                            <option value="karate" {{ old('icon') === 'karate' ? 'selected' : '' }}>🥋 Karate Striking Silhouette</option>
                            <option value="judo" {{ old('icon') === 'judo' ? 'selected' : '' }}>🥋 Judo Grappling &amp; People</option>
                            <option value="yoga" {{ old('icon') === 'yoga' ? 'selected' : '' }}>🧘 Vinyasa / Sun &amp; Balance</option>
                            <option value="boxing" {{ old('icon') === 'boxing' ? 'selected' : '' }}>⚡ Lightning Strike / Combat</option>
                        </select>
                    </div>

                    <!-- Section 2: Instructor & Scheduling -->
                    <div class="col-12 pt-3">
                        <h6 class="text-warning text-uppercase fw-bold border-bottom border-secondary pb-2 mb-3">
                            <i class="fas fa-user-ninja me-1"></i> 2. Instructor &amp; Scheduling
                        </h6>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Lead Instructor / Sensei</label>
                        <input type="text" name="instructor_name" value="{{ old('instructor_name') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., Sensei Devraj Chauhan">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Slot Window</label>
                        <input type="text" name="slot_window" value="{{ old('slot_window', 'Dawn (06:30 AM), Dusk (07:00 PM)') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., Dawn (06:30 AM), Dusk (07:00 PM)">
                    </div>

                    <!-- Section 3: Studio Location & Operational Desk -->
                    <div class="col-12 pt-3">
                        <h6 class="text-warning text-uppercase fw-bold border-bottom border-secondary pb-2 mb-3">
                            <i class="fas fa-map-marker-alt me-1"></i> 3. Dojo Operational Parameters (Rendered on Movement Page)
                        </h6>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Location Address</label>
                        <input type="text" name="location_address" value="{{ old('location_address', 'Plot 14-B, Sector 3, Near Nehru Park Promenade, Alwar, Rajasthan 301001') }}" class="form-control bg-dark border-secondary text-white" placeholder="Physical dojo address">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Operational Timings Window</label>
                        <input type="text" name="timings" value="{{ old('timings', 'Mon - Sun: 06:00 AM - 09:30 PM (IST)') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., Mon - Sun: 06:00 AM - 09:30 PM (IST)">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Dojo Direct Desk (Phone)</label>
                        <input type="text" name="phone" value="{{ old('phone', '+91 (0144) 283-9901') }}" class="form-control bg-dark border-secondary text-white" placeholder="+91 (0144) 283-9901">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Google Maps Navigation URL</label>
                        <input type="text" name="map_url" value="{{ old('map_url', 'https://maps.google.com/?q=Nehru+Park+Alwar+Rajasthan') }}" class="form-control bg-dark border-secondary text-white" placeholder="https://maps.google.com/...">
                    </div>

                    <!-- Section 4: Display Order & Status -->
                    <div class="col-12 pt-3">
                        <h6 class="text-warning text-uppercase fw-bold border-bottom border-secondary pb-2 mb-3">
                            <i class="fas fa-sliders-h me-1"></i> 4. Order &amp; Visibility Status
                        </h6>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Display Order No</label>
                        <input type="number" name="order_no" value="{{ old('order_no', 0) }}" class="form-control bg-dark border-secondary text-white" placeholder="0">
                        <small class="text-muted">Cards are displayed in ascending order (1, 2, 3...)</small>
                    </div>

                    <div class="col-md-6 d-flex align-items-center pt-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" checked>
                            <label class="form-check-label text-white fw-bold ms-2" for="statusSwitch">
                                Active (Visible on The Movement page)
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-12 pt-4 border-top border-secondary d-flex justify-content-end gap-2">
                        <a href="{{ route('commando-classes.index') }}" class="btn btn-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-warning px-5 fw-bold text-dark shadow-sm">
                            <i class="fas fa-save me-1"></i> Save Commando Class
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</div>
@endsection
