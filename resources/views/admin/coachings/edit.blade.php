@extends('admin.master')

@section('content')

<style>
    .form-card {
        background: #111827;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 1rem;
    }
    .form-section-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.75px;
        color: #f59e0b;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding-bottom: 8px;
        margin-bottom: 16px;
    }
    .nav-tabs-dark {
        border-bottom: 2px solid #1f2937;
    }
    .nav-tabs-dark .nav-link {
        color: #9ca3af;
        border: none;
        padding: 10px 20px;
        font-weight: 600;
        border-radius: 8px 8px 0 0;
        transition: all 0.2s;
    }
    .nav-tabs-dark .nav-link:hover {
        color: #f97316;
    }
    .nav-tabs-dark .nav-link.active {
        color: #fff;
        background: #1f2937;
        border-bottom: 3px solid #f97316;
    }
    .thumb-preview {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #475569;
    }
    .cover-thumb-preview {
        width: 140px;
        height: 70px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #475569;
    }
    .gallery-thumb {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #475569;
    }
    .switch-label {
        cursor: pointer;
    }
</style>

<div class="container-fluid py-4">

    <!-- Top Navigation Tabs -->
    <ul class="nav nav-tabs nav-tabs-dark mb-4">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('coachings.index') }}">
                <i class="fas fa-graduation-cap text-warning me-1"></i> All Coachings
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('commando-classes.index', ['coaching_id' => $coaching->id]) }}">
                <i class="fas fa-fist-raised text-warning me-1"></i> Disciplines &amp; Classes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('commando-instructors.index', ['coaching_id' => $coaching->id]) }}">
                <i class="fas fa-user-ninja text-warning me-1"></i> Master Instructors
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('commando-schedules.index', ['coaching_id' => $coaching->id]) }}">
                <i class="fas fa-calendar-alt text-warning me-1"></i> Weekly Schedules
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('commando-trials.index', ['coaching_id' => $coaching->id]) }}">
                <i class="fas fa-user-check text-info me-1"></i> Trial Bookings &amp; Inquiries
            </a>
        </li>
    </ul>

    <!-- Header & Action Shortcuts -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('coachings.index') }}" class="text-warning text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i> Back to Coachings
                </a>
            </div>
            <h3 class="fw-bold mb-0 text-white mt-1">
                <i class="fas fa-edit text-warning me-2"></i> Edit Coaching: {{ $coaching->coaching_name }}
            </h3>
            <p class="text-muted small mb-0 mt-0.5">Modify information, contact details, curriculum offerings, and visual branding.</p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('commando-classes.index', ['coaching_id' => $coaching->id]) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-list me-1"></i> Disciplines ({{ $coaching->classes()->count() }})
            </a>
            <a href="{{ route('commando-instructors.index', ['coaching_id' => $coaching->id]) }}" class="btn btn-outline-warning btn-sm">
                <i class="fas fa-user-ninja me-1"></i> Instructors ({{ $coaching->instructors()->count() }})
            </a>
            <a href="{{ route('commando-schedules.index', ['coaching_id' => $coaching->id]) }}" class="btn btn-outline-info btn-sm">
                <i class="fas fa-calendar-alt me-1"></i> Schedule ({{ $coaching->schedules()->count() }})
            </a>
            @if($coaching->slug === 'the-movement')
                <a href="{{ route('the-movement') }}" target="_blank" class="btn btn-info btn-sm text-dark fw-bold">
                    <i class="fas fa-external-link-alt me-1"></i> View Live
                </a>
            @else
                <a href="{{ route('coaching.show', $coaching->slug) }}" target="_blank" class="btn btn-info btn-sm text-dark fw-bold">
                    <i class="fas fa-external-link-alt me-1"></i> View Live
                </a>
            @endif
        </div>
    </div>

    <!-- Error Alerts -->
    @if ($errors->any())
        <div class="alert alert-danger bg-danger bg-opacity-25 border-danger text-white border-0 rounded-3 shadow-sm mb-4">
            <div class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-1"></i> Please correct the following errors:</div>
            <ul class="mb-0 small ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('coachings.update', $coaching->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- 1. Basic Information -->
        <div class="card form-card mb-4 shadow-sm">
            <div class="card-body p-4">
                <div class="form-section-title">
                    <i class="fas fa-info-circle me-1"></i> 1. Basic Information
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Coaching / Academy Name <span class="text-danger">*</span></label>
                        <input type="text" name="coaching_name" id="coaching_name" value="{{ old('coaching_name', $coaching->coaching_name) }}" required class="form-control bg-dark border-secondary text-white" placeholder="e.g. The Movement, The Achievers Academy">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Custom URL Slug <span class="text-muted">(Public web page identifier)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-secondary border-secondary text-white-50 small">/coaching/</span>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $coaching->slug) }}" class="form-control bg-dark border-secondary text-white" placeholder="the-movement">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Coaching Category / Type</label>
                        <select name="coaching_type" class="form-select bg-dark border-secondary text-white">
                            <option value="">Select Category</option>
                            @foreach([
                                'Coaching Institute',
                                'Martial Arts & Movement Studio',
                                'Competitive Exam Center',
                                'Tuition & Academic Coaching',
                                'Sports & Fitness Academy',
                                'Online Coaching Academy',
                                'Home Tuition Bureau'
                            ] as $cat)
                                <option value="{{ $cat }}" {{ old('coaching_type', $coaching->coaching_type) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Established Year</label>
                        <input type="number" name="established_year" value="{{ old('established_year', $coaching->established_year) }}" min="1900" max="2100" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 2018">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Teaching Mode</label>
                        <select name="mode" class="form-select bg-dark border-secondary text-white">
                            <option value="Offline" {{ old('mode', $coaching->mode) === 'Offline' ? 'selected' : '' }}>Offline (In-Person)</option>
                            <option value="Online" {{ old('mode', $coaching->mode) === 'Online' ? 'selected' : '' }}>Online (Virtual)</option>
                            <option value="Hybrid" {{ old('mode', $coaching->mode) === 'Hybrid' ? 'selected' : '' }}>Hybrid (Offline + Online)</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold">Short Tagline / Catchphrase</label>
                        <input type="text" name="short_description" value="{{ old('short_description', $coaching->short_description) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Alwar's Flagship Movement Sanctuary & Elite Martial Arts Center">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold">Detailed Overview &amp; Story</label>
                        <textarea name="description" rows="4" class="form-control bg-dark border-secondary text-white" placeholder="Describe facilities, training methodology, philosophy, and history...">{{ old('description', $coaching->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Media & Visual Branding -->
        <div class="card form-card mb-4 shadow-sm">
            <div class="card-body p-4">
                <div class="form-section-title">
                    <i class="fas fa-camera me-1"></i> 2. Media &amp; Visual Branding
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Logo Upload</label>
                        <input type="file" name="logo_file" id="logo_file" class="form-control bg-dark border-secondary text-white" accept="image/*">
                        <div class="text-muted small mt-1">Leave empty to keep existing logo.</div>
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <img id="logo_preview" src="{{ $coaching->logo_url }}" class="thumb-preview" alt="Current Logo">
                            <span class="text-white-50 small">Current / Preview</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Cover Banner Image</label>
                        <input type="file" name="cover_file" id="cover_file" class="form-control bg-dark border-secondary text-white" accept="image/*">
                        <div class="text-muted small mt-1">Leave empty to keep existing banner.</div>
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <img id="cover_preview" src="{{ $coaching->cover_image_url }}" class="cover-thumb-preview" alt="Current Cover">
                            <span class="text-white-50 small">Current / Preview</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Gallery Photos (Append More)</label>
                        <input type="file" name="gallery_files[]" class="form-control bg-dark border-secondary text-white" accept="image/*" multiple>
                        <div class="text-muted small mt-1">Select new images to add to the photo gallery.</div>
                        @if(is_array($coaching->gallery_images) && count($coaching->gallery_images) > 0)
                            <div class="d-flex flex-wrap gap-1.5 mt-2">
                                @foreach($coaching->gallery_images as $gImg)
                                    <img src="{{ asset($gImg) }}" class="gallery-thumb" alt="Gallery Photo" onerror="this.style.display='none'">
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Contact Details & Social Handles -->
        <div class="card form-card mb-4 shadow-sm">
            <div class="card-body p-4">
                <div class="form-section-title">
                    <i class="fas fa-address-book me-1"></i> 3. Contact &amp; Social Links
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Contact Person / Sensei / Director</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person', $coaching->contact_person) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Master Devraj Chauhan">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Mobile Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="mobile_number" value="{{ old('mobile_number', $coaching->mobile_number) }}" required class="form-control bg-dark border-secondary text-white" placeholder="e.g. 9876543210">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">WhatsApp Number</label>
                        <input type="tel" name="whatsapp_number" value="{{ old('whatsapp_number', $coaching->whatsapp_number) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 9876543210">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Official Email</label>
                        <input type="email" name="email" value="{{ old('email', $coaching->email) }}" class="form-control bg-dark border-secondary text-white" placeholder="info@themovement.in">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Website URL</label>
                        <input type="url" name="website" value="{{ old('website', $coaching->website) }}" class="form-control bg-dark border-secondary text-white" placeholder="https://themovement.in">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Instagram Handle / URL</label>
                        <input type="text" name="instagram" value="{{ old('instagram', $coaching->instagram) }}" class="form-control bg-dark border-secondary text-white" placeholder="@themovement.alwar">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Facebook Page URL</label>
                        <input type="url" name="facebook" value="{{ old('facebook', $coaching->facebook) }}" class="form-control bg-dark border-secondary text-white" placeholder="https://facebook.com/themovement">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">YouTube Channel URL</label>
                        <input type="url" name="youtube" value="{{ old('youtube', $coaching->youtube) }}" class="form-control bg-dark border-secondary text-white" placeholder="https://youtube.com/@themovement">
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Location & Address -->
        <div class="card form-card mb-4 shadow-sm">
            <div class="card-body p-4">
                <div class="form-section-title">
                    <i class="fas fa-map-marker-alt me-1"></i> 4. Location &amp; Address
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">State</label>
                        <input type="text" name="state" value="{{ old('state', $coaching->state ?? 'Rajasthan') }}" class="form-control bg-dark border-secondary text-white" placeholder="Rajasthan">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" value="{{ old('city', $coaching->city ?? 'Alwar') }}" required class="form-control bg-dark border-secondary text-white" placeholder="Alwar">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Area / Locality / Sector</label>
                        <input type="text" name="area_locality" value="{{ old('area_locality', $coaching->area_locality) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Nehru Park Enclave">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold">Full Postal Address</label>
                        <textarea name="full_address" rows="2" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Plot No. 14, Main Road, Near Nehru Park, Alwar, Rajasthan - 301001">{{ old('full_address', $coaching->full_address) }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Landmark</label>
                        <input type="text" name="landmark" value="{{ old('landmark', $coaching->landmark) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Opp. City Sports Complex">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Pincode</label>
                        <input type="text" name="pincode" value="{{ old('pincode', $coaching->pincode ?? '301001') }}" class="form-control bg-dark border-secondary text-white" placeholder="301001">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Google Maps Embed / Navigation URL</label>
                        <input type="url" name="google_map_url" value="{{ old('google_map_url', $coaching->google_map_url) }}" class="form-control bg-dark border-secondary text-white" placeholder="https://maps.google.com/...">
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Academic Courses & Offerings -->
        <div class="card form-card mb-4 shadow-sm">
            <div class="card-body p-4">
                <div class="form-section-title">
                    <i class="fas fa-graduation-cap me-1"></i> 5. Courses &amp; Curriculum Offerings
                </div>

                @php
                    $coursesVal = is_array($coaching->courses_offered) ? implode(', ', $coaching->courses_offered) : (string)$coaching->courses_offered;
                    $levelsVal = is_array($coaching->class_level) ? implode(', ', $coaching->class_level) : (string)$coaching->class_level;
                    $subjectsVal = is_array($coaching->subjects) ? implode(', ', $coaching->subjects) : (string)$coaching->subjects;
                    $facultyVal = is_array($coaching->faculty_names) ? implode(', ', $coaching->faculty_names) : (string)$coaching->faculty_names;
                @endphp

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Disciplines / Courses Offered</label>
                        <input type="text" name="courses_offered" value="{{ old('courses_offered', $coursesVal) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Shotokan Karate, Kodokan Judo, Vinyasa Yoga (comma-separated)">
                        <div class="text-muted small mt-1">Enter disciplines separated by commas.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Class / Target Levels</label>
                        <input type="text" name="class_level" value="{{ old('class_level', $levelsVal) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Beginners, Intermediate Cadets, Advanced (comma-separated)">
                        <div class="text-muted small mt-1">Enter target levels separated by commas.</div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold">Subjects / Specializations</label>
                        <input type="text" name="subjects" value="{{ old('subjects', $subjectsVal) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Kata, Kumite, Randori, Somatic Breathwork, Joint Mobility (comma-separated)">
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Fees, Timings & Batches -->
        <div class="card form-card mb-4 shadow-sm">
            <div class="card-body p-4">
                <div class="form-section-title">
                    <i class="fas fa-rupee-sign me-1"></i> 6. Fees, Batches &amp; Amenities
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Starting Monthly Fee (₹)</label>
                        <input type="number" step="0.01" name="starting_fees" value="{{ old('starting_fees', $coaching->starting_fees) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 2500">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Standard Monthly Fee (₹)</label>
                        <input type="number" step="0.01" name="monthly_fees" value="{{ old('monthly_fees', $coaching->monthly_fees) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 3500">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Full Course Fee (₹)</label>
                        <input type="number" step="0.01" name="course_fees" value="{{ old('course_fees', $coaching->course_fees) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 18000">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Registration / Kit Fee (₹)</label>
                        <input type="number" step="0.01" name="registration_fees" value="{{ old('registration_fees', $coaching->registration_fees) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 1000">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label text-white small fw-bold">Batch Size / Capacity</label>
                        <input type="text" name="batch_size" value="{{ old('batch_size', $coaching->batch_size) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 20-25 Students">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label text-white small fw-bold">Publishing Status</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ old('status', $coaching->status) ? 'checked' : '' }}>
                            <label class="form-check-label text-white switch-label" for="status">Active &amp; Published on Website</label>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="form-label text-white small fw-bold mb-2">Available Batches &amp; Special Features</label>
                        <div class="row g-2">
                            <div class="col-6 col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="morning_batch" value="1" id="morning" {{ old('morning_batch', $coaching->morning_batch) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small" for="morning">Morning Batch</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="afternoon_batch" value="1" id="afternoon" {{ old('afternoon_batch', $coaching->afternoon_batch) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small" for="afternoon">Afternoon Batch</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="evening_batch" value="1" id="evening" {{ old('evening_batch', $coaching->evening_batch) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small" for="evening">Evening Batch</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="weekend_batch" value="1" id="weekend" {{ old('weekend_batch', $coaching->weekend_batch) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small" for="weekend">Weekend Batch</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="demo_class_available" value="1" id="demo" {{ old('demo_class_available', $coaching->demo_class_available) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small" for="demo">Free Trial / Demo</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="scholarship_available" value="1" id="scholar" {{ old('scholarship_available', $coaching->scholarship_available) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small" for="scholar">Scholarships</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7. Faculty & Team Credentials -->
        <div class="card form-card mb-4 shadow-sm">
            <div class="card-body p-4">
                <div class="form-section-title">
                    <i class="fas fa-users me-1"></i> 7. Faculty &amp; Lineage Credentials
                </div>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold">Master Instructors / Key Faculty Names</label>
                        <input type="text" name="faculty_names" value="{{ old('faculty_names', $facultyVal) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Sensei Devraj Chauhan, Sensei K. Rathore, Acharya Meera Joshi (comma-separated)">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Faculty Experience Overview</label>
                        <textarea name="faculty_experience" rows="2" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 15+ years of combined international dojo coaching and competitive medals...">{{ old('faculty_experience', $coaching->faculty_experience) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Accreditations &amp; Qualifications</label>
                        <textarea name="qualification" rows="2" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 5th Dan WKF Black Belts, Kodokan Certified, E-RYT 500 Yoga Alliance...">{{ old('qualification', $coaching->qualification) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-end gap-3 mb-5">
            <a href="{{ route('coachings.index') }}" class="btn btn-secondary px-4 py-2.5">
                <i class="fas fa-times me-1"></i> Cancel
            </a>
            <button type="submit" class="btn btn-warning text-dark fw-bold px-5 py-2.5 shadow-sm">
                <i class="fas fa-check-circle me-1"></i> Update Coaching Institute
            </button>
        </div>

    </form>

</div>

<script>
    // Live logo preview
    document.getElementById('logo_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const preview = document.getElementById('logo_preview');
            preview.src = URL.createObjectURL(file);
        }
    });

    // Live cover preview
    document.getElementById('cover_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const preview = document.getElementById('cover_preview');
            preview.src = URL.createObjectURL(file);
        }
    });
</script>
@endsection
