@extends('admin.master')

@section('content')
<div class="container-fluid py-4">

    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('commando-instructors.index') }}" class="text-warning text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i> Back to Master Instructors
                </a>
            </div>
            <h4 class="fw-bold mb-0 text-white mt-2">Edit Master Instructor: {{ $instructor->name }}</h4>
            <p class="text-muted small mb-0 mt-1">Modify sensei credentials, specialties, bio, or photo.</p>
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
            <form action="{{ route('commando-instructors.update', $instructor->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    
                    <!-- Coaching Selection -->
                    <div class="col-12">
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
                                <option value="{{ $coaching->id }}" {{ old('coaching_id', $instructor->coaching_id ?? 1) == $coaching->id ? 'selected' : '' }}>
                                    {{ $coaching->coaching_name }} @if($coaching->city) ({{ $coaching->city }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="text-muted small mt-1">Select which coaching institute (e.g. The Movement, The Achievers) this instructor belongs to.</div>
                    </div>

                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Sensei / Instructor Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $instructor->name) }}" required class="form-control bg-dark border-secondary text-white">
                    </div>

                    <!-- Role Title -->
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Role Title / Designation</label>
                        <input type="text" name="role_title" value="{{ old('role_title', $instructor->role_title) }}" class="form-control bg-dark border-secondary text-white">
                    </div>

                    <!-- Rank Badge & Experience -->
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Rank / Belt Credential Badge</label>
                        <input type="text" name="rank_badge" value="{{ old('rank_badge', $instructor->rank_badge) }}" class="form-control bg-dark border-secondary text-white">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Experience Text</label>
                        <input type="text" name="experience" value="{{ old('experience', $instructor->experience) }}" class="form-control bg-dark border-secondary text-white">
                    </div>

                    <!-- Specialties -->
                    <div class="col-12">
                        <label class="form-label text-white small fw-bold">Specialties / Tags (Comma-Separated)</label>
                        <input type="text" name="specialties" value="{{ old('specialties', $instructor->specialties) }}" class="form-control bg-dark border-secondary text-white">
                        <small class="text-muted">Separate multiple skills with commas (e.g. Kata, Kumite Drills, Randori)</small>
                    </div>

                    <!-- Bio -->
                    <div class="col-12">
                        <label class="form-label text-white small fw-bold">Instructor Bio &amp; Pedigree</label>
                        <textarea name="bio" rows="3" class="form-control bg-dark border-secondary text-white">{{ old('bio', $instructor->bio) }}</textarea>
                    </div>

                    <!-- Current Photo Preview -->
                    <div class="col-12">
                        <label class="form-label text-white small fw-bold d-block">Current Instructor Photo</label>
                        <div class="d-flex align-items-center gap-3 p-2 bg-secondary bg-opacity-25 rounded-3 border border-secondary">
                            <img src="{{ $instructor->image_url }}" alt="{{ $instructor->name }}" class="rounded-3 object-fit-cover" style="width: 80px; height: 80px;">
                            <div class="small text-muted">
                                <div>Image source: <span class="font-monospace text-light">{{ $instructor->image ?: 'Default avatar' }}</span></div>
                                <div class="text-xs">Provide a new URL or upload a new photo file below to change it.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Image URL or Upload -->
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Photo URL</label>
                        <input type="text" name="image" value="{{ old('image', $instructor->image) }}" class="form-control bg-dark border-secondary text-white" placeholder="https://images.unsplash.com/...">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Upload New Photo File</label>
                        <input type="file" name="image_file" accept="image/*" class="form-control bg-dark border-secondary text-white">
                    </div>

                    <!-- Color Theme -->
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Accent Theme Color</label>
                        <select name="color_theme" class="form-select bg-dark border-secondary text-white">
                            <option value="blue" {{ old('color_theme', $instructor->color_theme) === 'blue' ? 'selected' : '' }}>Blue (Striking / Karate)</option>
                            <option value="amber" {{ old('color_theme', $instructor->color_theme) === 'amber' ? 'selected' : '' }}>Amber (Judo Gold)</option>
                            <option value="teal" {{ old('color_theme', $instructor->color_theme) === 'teal' ? 'selected' : '' }}>Teal (Yoga / Mobility)</option>
                            <option value="emerald" {{ old('color_theme', $instructor->color_theme) === 'emerald' ? 'selected' : '' }}>Emerald (Green / Recovery)</option>
                            <option value="purple" {{ old('color_theme', $instructor->color_theme) === 'purple' ? 'selected' : '' }}>Purple (Master Dan)</option>
                            <option value="rose" {{ old('color_theme', $instructor->color_theme) === 'rose' ? 'selected' : '' }}>Rose (Intense / Combat)</option>
                        </select>
                    </div>

                    <!-- Order No -->
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Display Order No</label>
                        <input type="number" name="order_no" value="{{ old('order_no', $instructor->order_no) }}" class="form-control bg-dark border-secondary text-white">
                        <small class="text-muted">Lower numbers appear first</small>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4 d-flex align-items-center pt-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" {{ $instructor->status ? 'checked' : '' }}>
                            <label class="form-check-label text-white fw-bold ms-2" for="statusSwitch">
                                Active (Visible on Movement Page)
                            </label>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 pt-4 border-top border-secondary d-flex justify-content-end gap-2">
                        <a href="{{ route('commando-instructors.index') }}" class="btn btn-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-warning px-5 fw-bold text-dark shadow-sm">
                            <i class="fas fa-save me-1"></i> Update Instructor
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</div>
@endsection
