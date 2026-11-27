@extends('coaching_admin.layout')

@section('title', 'Manage Coaching Profile')

@section('content')
<div class="pb-5">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fas fa-building text-teal-400 me-2"></i> Coaching Profile Management</h4>
            <p class="text-secondary small mb-0">Update your institute's public details, contact information, fees, courses, and multimedia photos.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('coaching.show', $coaching->slug ?? $coaching->id) }}" target="_blank" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-2">
                <i class="fas fa-external-link-alt"></i>
                <span>Preview Public Page</span>
            </a>
            <button type="submit" form="coachingProfileForm" class="btn btn-teal btn-sm px-3 d-flex align-items-center gap-2">
                <i class="fas fa-save"></i>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <form action="{{ route('coaching.profile.update') }}" method="POST" enctype="multipart/form-data" id="coachingProfileForm">
        @csrf
        @method('PUT')

        <!-- Navigation Tabs -->
        <ul class="nav nav-pills gap-2 mb-4 p-1.5 rounded-3 bg-dark border border-white/10" id="profileTab" role="tablist" style="width: fit-content;">
            <li class="nav-item">
                <button class="nav-link active text-sm py-2 px-3 fw-bold" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button">
                    <i class="fas fa-info-circle me-1.5"></i> Basic Info
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-sm py-2 px-3 fw-bold" id="contact-tab" data-bs-toggle="pill" data-bs-target="#contact" type="button">
                    <i class="fas fa-map-marker-alt me-1.5"></i> Contact &amp; Location
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-sm py-2 px-3 fw-bold" id="fees-tab" data-bs-toggle="pill" data-bs-target="#fees" type="button">
                    <i class="fas fa-coins me-1.5"></i> Fees &amp; Structure
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-sm py-2 px-3 fw-bold" id="batches-tab" data-bs-toggle="pill" data-bs-target="#batches" type="button">
                    <i class="fas fa-clock me-1.5"></i> Batches &amp; Facilities
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-sm py-2 px-3 fw-bold" id="media-tab" data-bs-toggle="pill" data-bs-target="#media" type="button">
                    <i class="fas fa-images me-1.5"></i> Media &amp; Gallery
                </button>
            </li>
        </ul>

        <div class="tab-content" id="profileTabContent">

            <!-- 1. GENERAL INFO TAB -->
            <div class="tab-pane fade show active" id="general" role="tabpanel">
                <div class="card-custom p-4">
                    <h5 class="fw-bold text-white mb-3 pb-2 border-bottom border-white/10">General Institute Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Coaching Name *</label>
                            <input type="text" name="coaching_name" class="form-control form-control-dark @error('coaching_name') is-invalid @enderror" value="{{ old('coaching_name', $coaching->coaching_name) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-secondary">Category / Discipline Type</label>
                            <input type="text" name="coaching_type" class="form-control form-control-dark" placeholder="e.g. Martial Arts, Sports, Yoga" value="{{ old('coaching_type', $coaching->coaching_type) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-secondary">Established Year</label>
                            <input type="number" name="established_year" class="form-control form-control-dark" placeholder="e.g. 2018" value="{{ old('established_year', $coaching->established_year) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Mode of Training</label>
                            <select name="mode" class="form-select form-select-dark">
                                <option value="Offline (At Studio / Dojo)" {{ $coaching->mode === 'Offline (At Studio / Dojo)' ? 'selected' : '' }}>Offline (At Studio / Dojo)</option>
                                <option value="Online Classes" {{ $coaching->mode === 'Online Classes' ? 'selected' : '' }}>Online Classes</option>
                                <option value="Hybrid (Both)" {{ $coaching->mode === 'Hybrid (Both)' ? 'selected' : '' }}>Hybrid (Both)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Batch Size (Capacity)</label>
                            <input type="text" name="batch_size" class="form-control form-control-dark" placeholder="e.g. 15-20 students per batch" value="{{ old('batch_size', $coaching->batch_size) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label small text-secondary">Short Catchy Tagline / Headline (Max 500 characters)</label>
                            <input type="text" name="short_description" class="form-control form-control-dark" placeholder="e.g. Premier Olympic tatami dojo and Shotokan karate sanctuary in Alwar." value="{{ old('short_description', $coaching->short_description) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label small text-secondary">Full Detailed Description &amp; Philosophy</label>
                            <textarea name="description" class="form-control form-control-dark" rows="6" placeholder="Describe your methodology, safety gear, belt hierarchy, training grounds...">{{ old('description', $coaching->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. CONTACT & LOCATION TAB -->
            <div class="tab-pane fade" id="contact" role="tabpanel">
                <div class="card-custom p-4">
                    <h5 class="fw-bold text-white mb-3 pb-2 border-bottom border-white/10">Contact &amp; Location Information</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small text-secondary">Contact Person / Head Sensei</label>
                            <input type="text" name="contact_person" class="form-control form-control-dark" value="{{ old('contact_person', $coaching->contact_person) }}" placeholder="e.g. Sensei Rajesh Kumar">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-secondary">Primary Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control form-control-dark" value="{{ old('mobile_number', $coaching->mobile_number) }}" placeholder="e.g. 9876543210">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-secondary">WhatsApp Support Number</label>
                            <input type="text" name="whatsapp_number" class="form-control form-control-dark" value="{{ old('whatsapp_number', $coaching->whatsapp_number) }}" placeholder="e.g. 9876543210">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small text-secondary">Official Email Address</label>
                            <input type="email" name="email" class="form-control form-control-dark" value="{{ old('email', $coaching->email) }}" placeholder="contact@coaching.com">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-secondary">Official Website URL</label>
                            <input type="text" name="website" class="form-control form-control-dark" value="{{ old('website', $coaching->website) }}" placeholder="https://coaching.com">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-secondary">Instagram Profile URL</label>
                            <input type="text" name="instagram" class="form-control form-control-dark" value="{{ old('instagram', $coaching->instagram) }}" placeholder="https://instagram.com/coaching">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small text-secondary">State</label>
                            <input type="text" name="state" class="form-control form-control-dark" value="{{ old('state', $coaching->state) }}" placeholder="e.g. Rajasthan">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-secondary">City</label>
                            <input type="text" name="city" class="form-control form-control-dark" value="{{ old('city', $coaching->city) }}" placeholder="e.g. Alwar">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-secondary">Area / Locality</label>
                            <input type="text" name="area_locality" class="form-control form-control-dark" value="{{ old('area_locality', $coaching->area_locality) }}" placeholder="e.g. Sector 7, Arya Nagar">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small text-secondary">Full Street Address</label>
                            <input type="text" name="full_address" class="form-control form-control-dark" value="{{ old('full_address', $coaching->full_address) }}" placeholder="Plot 42, Near Stadium, Main Road">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-secondary">Pincode</label>
                            <input type="text" name="pincode" class="form-control form-control-dark" value="{{ old('pincode', $coaching->pincode) }}" placeholder="301001">
                        </div>

                        <div class="col-12">
                            <label class="form-label small text-secondary">Google Map Embed / Direction Link</label>
                            <input type="text" name="google_map_url" class="form-control form-control-dark" value="{{ old('google_map_url', $coaching->google_map_url) }}" placeholder="https://maps.google.com/...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. FEES & STRUCTURE TAB -->
            <div class="tab-pane fade" id="fees" role="tabpanel">
                <div class="card-custom p-4">
                    <h5 class="fw-bold text-white mb-3 pb-2 border-bottom border-white/10">Fee Structure &amp; Pricing Packages</h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small text-secondary">Starting Fee (₹)</label>
                            <input type="number" step="0.01" name="starting_fees" class="form-control form-control-dark" value="{{ old('starting_fees', $coaching->starting_fees) }}" placeholder="e.g. 999">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-secondary">Monthly Membership Fee (₹)</label>
                            <input type="number" step="0.01" name="monthly_fees" class="form-control form-control-dark" value="{{ old('monthly_fees', $coaching->monthly_fees) }}" placeholder="e.g. 1499">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-secondary">Full Course Fee (₹)</label>
                            <input type="number" step="0.01" name="course_fees" class="form-control form-control-dark" value="{{ old('course_fees', $coaching->course_fees) }}" placeholder="e.g. 12000">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-secondary">One-time Registration Fee (₹)</label>
                            <input type="number" step="0.01" name="registration_fees" class="form-control form-control-dark" value="{{ old('registration_fees', $coaching->registration_fees) }}" placeholder="e.g. 500">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Faculty Experience</label>
                            <input type="text" name="faculty_experience" class="form-control form-control-dark" value="{{ old('faculty_experience', $coaching->faculty_experience) }}" placeholder="e.g. 15+ Years Olympic Certified Master Instructors">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Certifications &amp; Accreditations</label>
                            <input type="text" name="qualification" class="form-control form-control-dark" value="{{ old('qualification', $coaching->qualification) }}" placeholder="e.g. 5th Dan Black Belt, WKF Certified, Yoga Alliance">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. BATCHES & FACILITIES TAB -->
            <div class="tab-pane fade" id="batches" role="tabpanel">
                <div class="card-custom p-4">
                    <h5 class="fw-bold text-white mb-3 pb-2 border-bottom border-white/10">Operating Shifts, Batches &amp; Disciplines</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12"><strong class="small text-white">Active Shifts &amp; Amenities:</strong></div>
                        <div class="col-6 col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input bg-dark border-secondary" type="checkbox" name="morning_batch" id="morningBatch" value="1" {{ $coaching->morning_batch ? 'checked' : '' }}>
                                <label class="form-check-label text-white small" for="morningBatch">🌅 Morning Batch (Dawn)</label>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input bg-dark border-secondary" type="checkbox" name="afternoon_batch" id="afternoonBatch" value="1" {{ $coaching->afternoon_batch ? 'checked' : '' }}>
                                <label class="form-check-label text-white small" for="afternoonBatch">☀️ Afternoon Batch</label>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input bg-dark border-secondary" type="checkbox" name="evening_batch" id="eveningBatch" value="1" {{ $coaching->evening_batch ? 'checked' : '' }}>
                                <label class="form-check-label text-white small" for="eveningBatch">🌆 Evening Batch (Dusk)</label>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input bg-dark border-secondary" type="checkbox" name="weekend_batch" id="weekendBatch" value="1" {{ $coaching->weekend_batch ? 'checked' : '' }}>
                                <label class="form-check-label text-white small" for="weekendBatch">📅 Weekend Special Batch</label>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input bg-dark border-secondary" type="checkbox" name="demo_class_available" id="demoClass" value="1" {{ $coaching->demo_class_available ? 'checked' : '' }}>
                                <label class="form-check-label text-white small" for="demoClass">🥋 Free Demo / Trial Pass Available</label>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input bg-dark border-secondary" type="checkbox" name="scholarship_available" id="scholarshipAvail" value="1" {{ $coaching->scholarship_available ? 'checked' : '' }}>
                                <label class="form-check-label text-white small" for="scholarshipAvail">🎖️ Merit Scholarships Offered</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Courses / Disciplines Offered (Comma-separated)</label>
                            <input type="text" name="courses_offered" class="form-control form-control-dark" value="{{ is_array($coaching->courses_offered) ? implode(', ', $coaching->courses_offered) : $coaching->courses_offered }}" placeholder="e.g. Karate, Judo, Brazilian Jiu-Jitsu, Yoga">
                            <small class="text-muted" style="font-size: 11px;">Separate each course with a comma.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Target Age Groups / Levels (Comma-separated)</label>
                            <input type="text" name="class_level" class="form-control form-control-dark" value="{{ is_array($coaching->class_level) ? implode(', ', $coaching->class_level) : $coaching->class_level }}" placeholder="e.g. Kids (5-12), Teens, Adults, Advanced">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. MEDIA & GALLERY TAB -->
            <div class="tab-pane fade" id="media" role="tabpanel">
                <div class="card-custom p-4">
                    <h5 class="fw-bold text-white mb-3 pb-2 border-bottom border-white/10">Brand Visuals &amp; Photo Showcase</h5>
                    
                    <div class="row g-4 mb-4">
                        <!-- Logo Upload -->
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Institute Official Logo</label>
                            <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-dark border border-white/10">
                                <img src="{{ $coaching->logo_url }}" class="rounded-3 border border-secondary" style="width: 70px; height: 70px; object-fit: cover; background: #fff;" alt="Logo Preview" id="logoPreviewImg">
                                <div class="flex-grow-1">
                                    <input type="file" name="logo_file" class="form-control form-control-sm form-control-dark mb-1" accept="image/*" onchange="previewFile(this, 'logoPreviewImg')">
                                    <small class="text-muted" style="font-size: 11px;">Recommended: Square PNG/WEBP, min 250x250px</small>
                                </div>
                            </div>
                        </div>

                        <!-- Cover Banner Upload -->
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Studio Cover Banner</label>
                            <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-dark border border-white/10">
                                <img src="{{ $coaching->cover_image_url }}" class="rounded-3 border border-secondary" style="width: 100px; height: 70px; object-fit: cover;" alt="Cover Preview" id="coverPreviewImg">
                                <div class="flex-grow-1">
                                    <input type="file" name="cover_file" class="form-control form-control-sm form-control-dark mb-1" accept="image/*" onchange="previewFile(this, 'coverPreviewImg')">
                                    <small class="text-muted" style="font-size: 11px;">Recommended: 1200x500px high quality landscape</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Multi-image Studio Gallery -->
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Upload Additional Studio &amp; Training Photos</label>
                        <input type="file" name="gallery_files[]" class="form-control form-control-dark" multiple accept="image/*">
                        <small class="text-muted" style="font-size: 11px;">You can select multiple photos at once (training floor, equipment, belt ceremonies, awards).</small>
                    </div>

                    <!-- Existing Gallery Photos Grid -->
                    @if(!empty($coaching->gallery_images) && is_array($coaching->gallery_images) && count($coaching->gallery_images) > 0)
                    <div class="mt-4">
                        <h6 class="text-white small fw-bold mb-2">Existing Gallery Photos ({{ count($coaching->gallery_images) }})</h6>
                        <div class="row g-2">
                            @foreach($coaching->gallery_images as $gImg)
                            <div class="col-4 col-sm-3 col-md-2 position-relative" id="gallery-card-{{ $loop->index }}">
                                <div class="ratio ratio-1x1 rounded-3 overflow-hidden border border-white/10">
                                    <img src="{{ asset($gImg) }}" class="object-fit-cover w-100 h-100" alt="Gallery Photo">
                                </div>
                                <button type="button" class="btn btn-danger btn-sm p-1 position-absolute top-0 end-0 m-1 rounded-circle" style="width: 24px; height: 24px; font-size: 10px;" onclick="removeGalleryPhoto('{{ $gImg }}', 'gallery-card-{{ $loop->index }}')" title="Remove Photo">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        <div class="mt-4 pt-2 text-end">
            <button type="submit" class="btn btn-teal px-4 py-2 fw-bold">
                <i class="fas fa-check-circle me-1.5"></i> Update Institute Profile
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    function previewFile(input, targetId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(targetId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeGalleryPhoto(imagePath, elementId) {
        if (!confirm('Are you sure you want to remove this image from your institute gallery?')) {
            return;
        }

        fetch("{{ route('coaching.profile.removeGallery') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ image_path: imagePath })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const el = document.getElementById(elementId);
                if (el) el.remove();
            }
        })
        .catch(err => console.error(err));
    }
</script>
@endpush
