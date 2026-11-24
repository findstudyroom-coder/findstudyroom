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
    .preview-box {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px dashed #475569;
        display: none;
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
            <a class="nav-link" href="{{ route('commando-classes.index') }}">
                <i class="fas fa-fist-raised text-warning me-1"></i> Disciplines &amp; Classes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('commando-instructors.index') }}">
                <i class="fas fa-user-ninja text-warning me-1"></i> Master Instructors
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('commando-schedules.index') }}">
                <i class="fas fa-calendar-alt text-warning me-1"></i> Weekly Schedules
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('commando-trials.index') }}">
                <i class="fas fa-user-check text-info me-1"></i> Trial Bookings &amp; Inquiries
            </a>
        </li>
    </ul>

    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('coachings.index') }}" class="text-warning text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i> Back to Coachings
                </a>
            </div>
            <h3 class="fw-bold mb-0 text-white mt-1">
                <i class="fas fa-plus-circle text-warning me-2"></i> Register New Coaching Institute
            </h3>
            <p class="text-muted small mb-0 mt-0.5">Add a new institute, academy, or movement studio to FindStudy.</p>
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

    <form action="{{ route('coachings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 1. Basic Information -->
        <div class="card form-card mb-4 shadow-sm">
            <div class="card-body p-4">
                <div class="form-section-title">
                    <i class="fas fa-info-circle me-1"></i> 1. Basic Information
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Coaching / Academy Name <span class="text-danger">*</span></label>
                        <input type="text" name="coaching_name" id="coaching_name" value="{{ old('coaching_name') }}" required class="form-control bg-dark border-secondary text-white" placeholder="e.g. The Movement, The Achievers Academy">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Custom URL Slug <span class="text-muted">(Optional - auto generated)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-secondary border-secondary text-white-50 small">/coaching/</span>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="form-control bg-dark border-secondary text-white" placeholder="the-achievers">
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
                                <option value="{{ $cat }}" {{ old('coaching_type') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Established Year</label>
                        <input type="number" name="established_year" value="{{ old('established_year', date('Y')) }}" min="1900" max="2100" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 2018">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Teaching Mode</label>
                        <select name="mode" class="form-select bg-dark border-secondary text-white">
                            <option value="Offline" {{ old('mode', 'Offline') === 'Offline' ? 'selected' : '' }}>Offline (In-Person)</option>
                            <option value="Online" {{ old('mode') === 'Online' ? 'selected' : '' }}>Online (Virtual)</option>
                            <option value="Hybrid" {{ old('mode') === 'Hybrid' ? 'selected' : '' }}>Hybrid (Offline + Online)</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold">Short Tagline / Catchphrase</label>
                        <input type="text" name="short_description" value="{{ old('short_description') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Alwar's Flagship Movement Sanctuary & Elite Martial Arts Center">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold">Detailed Overview &amp; Story</label>
                        <textarea name="description" rows="4" class="form-control bg-dark border-secondary text-white" placeholder="Describe facilities, training methodology, philosophy, and history...">{{ old('description') }}</textarea>
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
                        <div class="text-muted small mt-1">PNG, JPG, SVG or WEBP (Max 3MB). Square recommended.</div>
                        <img id="logo_preview" class="preview-box mt-2" alt="Logo Preview">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Cover Banner Image</label>
                        <input type="file" name="cover_file" id="cover_file" class="form-control bg-dark border-secondary text-white" accept="image/*">
                        <div class="text-muted small mt-1">High resolution cover banner (Max 5MB).</div>
                        <img id="cover_preview" class="preview-box mt-2" style="width: 140px; height: 80px;" alt="Cover Preview">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Gallery Photos (Multiple)</label>
                        <input type="file" name="gallery_files[]" class="form-control bg-dark border-secondary text-white" accept="image/*" multiple>
                        <div class="text-muted small mt-1">Select multiple photos of training halls, classrooms, etc.</div>
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
                        <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Master Devraj Chauhan">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Mobile Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="mobile_number" value="{{ old('mobile_number') }}" required class="form-control bg-dark border-secondary text-white" placeholder="e.g. 9876543210">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">WhatsApp Number</label>
                        <input type="tel" name="whatsapp_number" value="{{ old('whatsapp_number') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 9876543210">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Official Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control bg-dark border-secondary text-white" placeholder="info@themovement.in">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Website URL</label>
                        <input type="url" name="website" value="{{ old('website') }}" class="form-control bg-dark border-secondary text-white" placeholder="https://themovement.in">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Instagram Handle / URL</label>
                        <input type="text" name="instagram" value="{{ old('instagram') }}" class="form-control bg-dark border-secondary text-white" placeholder="@themovement.alwar">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Facebook Page URL</label>
                        <input type="url" name="facebook" value="{{ old('facebook') }}" class="form-control bg-dark border-secondary text-white" placeholder="https://facebook.com/themovement">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">YouTube Channel URL</label>
                        <input type="url" name="youtube" value="{{ old('youtube') }}" class="form-control bg-dark border-secondary text-white" placeholder="https://youtube.com/@themovement">
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
                        <input type="text" name="state" value="{{ old('state', 'Rajasthan') }}" class="form-control bg-dark border-secondary text-white" placeholder="Rajasthan">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" value="{{ old('city', 'Alwar') }}" required class="form-control bg-dark border-secondary text-white" placeholder="Alwar">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Area / Locality / Sector</label>
                        <input type="text" name="area_locality" value="{{ old('area_locality') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Nehru Park Enclave">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold">Full Postal Address</label>
                        <textarea name="full_address" rows="2" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Plot No. 14, Main Road, Near Nehru Park, Alwar, Rajasthan - 301001">{{ old('full_address') }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Landmark</label>
                        <input type="text" name="landmark" value="{{ old('landmark') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Opp. City Sports Complex">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Pincode</label>
                        <input type="text" name="pincode" value="{{ old('pincode', '301001') }}" class="form-control bg-dark border-secondary text-white" placeholder="301001">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Google Maps Embed / Navigation URL</label>
                        <input type="url" name="google_map_url" value="{{ old('google_map_url') }}" class="form-control bg-dark border-secondary text-white" placeholder="https://maps.google.com/...">
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

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Disciplines / Courses Offered</label>
                        <input type="text" name="courses_offered" value="{{ old('courses_offered') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Shotokan Karate, Kodokan Judo, Vinyasa Yoga, Combat Kickboxing (comma-separated)">
                        <div class="text-muted small mt-1">Enter disciplines separated by commas.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Class / Target Levels</label>
                        <input type="text" name="class_level" value="{{ old('class_level') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Beginners, Intermediate Cadets, Advanced Black Belt (comma-separated)">
                        <div class="text-muted small mt-1">Enter target levels separated by commas.</div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold">Subjects / Specializations</label>
                        <input type="text" name="subjects" value="{{ old('subjects') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Kata, Kumite, Randori, Somatic Breathwork, Joint Mobility (comma-separated)">
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
                        <input type="number" step="0.01" name="starting_fees" value="{{ old('starting_fees') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 2500">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Standard Monthly Fee (₹)</label>
                        <input type="number" step="0.01" name="monthly_fees" value="{{ old('monthly_fees') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 3500">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Full Course Fee (₹)</label>
                        <input type="number" step="0.01" name="course_fees" value="{{ old('course_fees') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 18000">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Registration / Kit Fee (₹)</label>
                        <input type="number" step="0.01" name="registration_fees" value="{{ old('registration_fees') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 1000">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label text-white small fw-bold">Batch Size / Capacity</label>
                        <input type="text" name="batch_size" value="{{ old('batch_size', '20-25 Students per Batch') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 20-25 Students">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label text-white small fw-bold">Publishing Status</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ old('status', 1) ? 'checked' : '' }}>
                            <label class="form-check-label text-white switch-label" for="status">Active &amp; Published on Website</label>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="form-label text-white small fw-bold mb-2">Available Batches &amp; Special Features</label>
                        <div class="row g-2">
                            <div class="col-6 col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="morning_batch" value="1" id="morning" {{ old('morning_batch', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small" for="morning">Morning Batch</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="afternoon_batch" value="1" id="afternoon" {{ old('afternoon_batch', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small" for="afternoon">Afternoon Batch</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="evening_batch" value="1" id="evening" {{ old('evening_batch', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small" for="evening">Evening Batch</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="weekend_batch" value="1" id="weekend" {{ old('weekend_batch', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small" for="weekend">Weekend Batch</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="demo_class_available" value="1" id="demo" {{ old('demo_class_available', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small" for="demo">Free Trial / Demo</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="scholarship_available" value="1" id="scholar" {{ old('scholarship_available') ? 'checked' : '' }}>
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
                        <input type="text" name="faculty_names" value="{{ old('faculty_names') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Sensei Devraj Chauhan, Sensei K. Rathore, Acharya Meera Joshi (comma-separated)">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Faculty Experience Overview</label>
                        <textarea name="faculty_experience" rows="2" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 15+ years of combined international dojo coaching and competitive medals...">{{ old('faculty_experience') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Accreditations &amp; Qualifications</label>
                        <textarea name="qualification" rows="2" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 5th Dan WKF Black Belts, Kodokan Certified, E-RYT 500 Yoga Alliance...">{{ old('qualification') }}</textarea>
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
                <i class="fas fa-save me-1"></i> Save Coaching Institute
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
            preview.style.display = 'block';
        }
    });

    // Live cover preview
    document.getElementById('cover_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const preview = document.getElementById('cover_preview');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    });

    // Auto slug from coaching name
    const nameInput = document.getElementById('coaching_name');
    const slugInput = document.getElementById('slug');
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            if (!slugInput.dataset.manual) {
                slugInput.value = nameInput.value.toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });
        slugInput.addEventListener('input', function() {
            slugInput.dataset.manual = 'true';
        });
    }
</script>
@endsection
