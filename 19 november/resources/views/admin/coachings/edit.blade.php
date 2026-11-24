@extends('admin.master')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white">✏️ Edit Coaching: {{ $coaching->coaching_name }}</h2>
    <a href="{{ route('coachings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<form action="{{ route('coachings.update', $coaching->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <!-- Basic Information -->
    <div class="card bg-dark border-secondary mb-4">
        <div class="card-header bg-secondary text-white">Basic Information</div>
        <div class="card-body">
            <div class="row g-3 text-white">
                <div class="col-md-6">
                    <label>Coaching Name *</label>
                    <input type="text" name="coaching_name" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->coaching_name }}" required>
                </div>
                <div class="col-md-6">
                    <label>Coaching Type</label>
                    <select name="coaching_type" class="form-select bg-dark text-white border-secondary">
                        <option value="">Select Type</option>
                        <option value="Tuition" {{ $coaching->coaching_type == 'Tuition' ? 'selected' : '' }}>Tuition</option>
                        <option value="Coaching Institute" {{ $coaching->coaching_type == 'Coaching Institute' ? 'selected' : '' }}>Coaching Institute</option>
                        <option value="Home Tuition" {{ $coaching->coaching_type == 'Home Tuition' ? 'selected' : '' }}>Home Tuition</option>
                        <option value="Online Coaching" {{ $coaching->coaching_type == 'Online Coaching' ? 'selected' : '' }}>Online Coaching</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Established Year</label>
                    <input type="number" name="established_year" class="form-control bg-dark text-white border-secondary" min="1900" max="2100" value="{{ $coaching->established_year }}">
                </div>
                <div class="col-md-12">
                    <label>Short Description</label>
                    <input type="text" name="short_description" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->short_description }}">
                </div>
                <div class="col-md-12">
                    <label>Description</label>
                    <textarea name="description" class="form-control bg-dark text-white border-secondary" rows="4">{{ $coaching->description }}</textarea>
                </div>
                <div class="col-md-4">
                    <label>Logo</label>
                    <input type="file" name="logo" class="form-control bg-dark text-white border-secondary" accept="image/*">
                    @if($coaching->logo)
                        <img src="{{ asset('storage/'.$coaching->logo) }}" class="mt-2" style="height:50px; border-radius:6px;">
                    @endif
                </div>
                <div class="col-md-4">
                    <label>Cover Image</label>
                    <input type="file" name="cover_image" class="form-control bg-dark text-white border-secondary" accept="image/*">
                    @if($coaching->cover_image)
                        <img src="{{ asset('storage/'.$coaching->cover_image) }}" class="mt-2" style="height:50px; border-radius:6px;">
                    @endif
                </div>
                <div class="col-md-4">
                    <label>Gallery Images (Append)</label>
                    <input type="file" name="gallery_images[]" class="form-control bg-dark text-white border-secondary" accept="image/*" multiple>
                    <!-- Display existing images could be added here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Details -->
    <div class="card bg-dark border-secondary mb-4">
        <div class="card-header bg-secondary text-white">Contact Details</div>
        <div class="card-body">
            <div class="row g-3 text-white">
                <div class="col-md-4">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->contact_person }}">
                </div>
                <div class="col-md-4">
                    <label>Mobile Number</label>
                    <input type="text" name="mobile_number" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->mobile_number }}">
                </div>
                <div class="col-md-4">
                    <label>WhatsApp Number</label>
                    <input type="text" name="whatsapp_number" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->whatsapp_number }}">
                </div>
                <div class="col-md-4">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->email }}">
                </div>
                <div class="col-md-4">
                    <label>Website</label>
                    <input type="url" name="website" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->website }}">
                </div>
                <div class="col-md-4">
                    <label>Facebook URL</label>
                    <input type="url" name="facebook" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->facebook }}">
                </div>
                <div class="col-md-4">
                    <label>Instagram URL</label>
                    <input type="url" name="instagram" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->instagram }}">
                </div>
                <div class="col-md-4">
                    <label>YouTube URL</label>
                    <input type="url" name="youtube" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->youtube }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Location -->
    <div class="card bg-dark border-secondary mb-4">
        <div class="card-header bg-secondary text-white">Location</div>
        <div class="card-body">
            <div class="row g-3 text-white">
                <div class="col-md-4">
                    <label>State</label>
                    <input type="text" name="state" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->state }}">
                </div>
                <div class="col-md-4">
                    <label>City</label>
                    <input type="text" name="city" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->city }}">
                </div>
                <div class="col-md-4">
                    <label>Area / Locality</label>
                    <input type="text" name="area_locality" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->area_locality }}">
                </div>
                <div class="col-md-12">
                    <label>Full Address</label>
                    <textarea name="full_address" class="form-control bg-dark text-white border-secondary" rows="2">{{ $coaching->full_address }}</textarea>
                </div>
                <div class="col-md-4">
                    <label>Landmark</label>
                    <input type="text" name="landmark" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->landmark }}">
                </div>
                <div class="col-md-4">
                    <label>Pincode</label>
                    <input type="text" name="pincode" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->pincode }}">
                </div>
                <div class="col-md-12">
                    <label>Google Map URL</label>
                    <input type="url" name="google_map_url" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->google_map_url }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Course Details -->
    <div class="card bg-dark border-secondary mb-4">
        <div class="card-header bg-secondary text-white">Course Details</div>
        <div class="card-body">
            <div class="row g-3 text-white">
                <div class="col-md-6">
                    <label>Courses Offered (Multiple)</label>
                    <select name="courses_offered[]" class="form-select bg-dark text-white border-secondary" multiple style="height: 120px;">
                        @php $savedCourses = is_array($coaching->courses_offered) ? $coaching->courses_offered : []; @endphp
                        @foreach(['UPSC', 'SSC', 'Banking', 'Railway', 'NEET', 'JEE', 'GATE', 'CAT', 'CLAT', 'CA', 'CS', 'CMA', 'NDA', 'CUET', 'School Tuition', 'College Tuition', 'Spoken English', 'Computer Courses'] as $course)
                            <option value="{{ $course }}" {{ in_array($course, $savedCourses) ? 'selected' : '' }}>{{ $course }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Class Level (Multiple)</label>
                    <select name="class_level[]" class="form-select bg-dark text-white border-secondary" multiple style="height: 120px;">
                        @php $savedLevels = is_array($coaching->class_level) ? $coaching->class_level : []; @endphp
                        @foreach(['Class 1-5', '6-8', '9-10', '11-12', 'Graduation', 'Competitive Exams'] as $level)
                            <option value="{{ $level }}" {{ in_array($level, $savedLevels) ? 'selected' : '' }}>{{ $level }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Subjects (Type and press Enter, optional)</label>
                    @php $savedSubjects = is_array($coaching->subjects) ? implode(', ', $coaching->subjects) : ''; @endphp
                    <textarea name="subjects[]" class="form-control bg-dark text-white border-secondary" placeholder="Enter subjects separated by comma...">{{ $savedSubjects }}</textarea>
                </div>
                <div class="col-md-6">
                    <label>Mode</label>
                    <select name="mode" class="form-select bg-dark text-white border-secondary">
                        <option value="">Select Mode</option>
                        <option value="Offline" {{ $coaching->mode == 'Offline' ? 'selected' : '' }}>Offline</option>
                        <option value="Online" {{ $coaching->mode == 'Online' ? 'selected' : '' }}>Online</option>
                        <option value="Hybrid" {{ $coaching->mode == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Fees & Batches -->
    <div class="card bg-dark border-secondary mb-4">
        <div class="card-header bg-secondary text-white">Fees & Batch Details</div>
        <div class="card-body">
            <div class="row g-3 text-white">
                <div class="col-md-3">
                    <label>Starting Fees</label>
                    <input type="number" step="0.01" name="starting_fees" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->starting_fees }}">
                </div>
                <div class="col-md-3">
                    <label>Monthly Fees</label>
                    <input type="number" step="0.01" name="monthly_fees" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->monthly_fees }}">
                </div>
                <div class="col-md-3">
                    <label>Course Fees</label>
                    <input type="number" step="0.01" name="course_fees" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->course_fees }}">
                </div>
                <div class="col-md-3">
                    <label>Registration Fees</label>
                    <input type="number" step="0.01" name="registration_fees" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->registration_fees }}">
                </div>
                
                <div class="col-md-12 mt-4"><hr><h5>Batch Timings & Features</h5></div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="morning_batch" value="1" id="morning" {{ $coaching->morning_batch ? 'checked' : '' }}>
                        <label class="form-check-label" for="morning">Morning Batch</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="afternoon_batch" value="1" id="afternoon" {{ $coaching->afternoon_batch ? 'checked' : '' }}>
                        <label class="form-check-label" for="afternoon">Afternoon Batch</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="evening_batch" value="1" id="evening" {{ $coaching->evening_batch ? 'checked' : '' }}>
                        <label class="form-check-label" for="evening">Evening Batch</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="weekend_batch" value="1" id="weekend" {{ $coaching->weekend_batch ? 'checked' : '' }}>
                        <label class="form-check-label" for="weekend">Weekend Batch</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="demo_class_available" value="1" id="demo" {{ $coaching->demo_class_available ? 'checked' : '' }}>
                        <label class="form-check-label" for="demo">Demo Available</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="scholarship_available" value="1" id="scholar" {{ $coaching->scholarship_available ? 'checked' : '' }}>
                        <label class="form-check-label" for="scholar">Scholarship</label>
                    </div>
                </div>
                <div class="col-md-4 mt-3">
                    <label>Batch Size</label>
                    <input type="text" name="batch_size" class="form-control bg-dark text-white border-secondary" value="{{ $coaching->batch_size }}" placeholder="e.g. 30-40 Students">
                </div>
            </div>
        </div>
    </div>

    <!-- Faculty -->
    <div class="card bg-dark border-secondary mb-4">
        <div class="card-header bg-secondary text-white">Faculty Details</div>
        <div class="card-body">
            <div class="row g-3 text-white">
                <div class="col-md-12">
                    <label>Faculty Names</label>
                    @php $savedFaculty = is_array($coaching->faculty_names) ? implode(', ', $coaching->faculty_names) : ''; @endphp
                    <textarea name="faculty_names[]" class="form-control bg-dark text-white border-secondary" placeholder="Enter faculty names separated by comma...">{{ $savedFaculty }}</textarea>
                </div>
                <div class="col-md-6">
                    <label>Faculty Experience</label>
                    <textarea name="faculty_experience" class="form-control bg-dark text-white border-secondary" rows="2">{{ $coaching->faculty_experience }}</textarea>
                </div>
                <div class="col-md-6">
                    <label>Qualification</label>
                    <textarea name="qualification" class="form-control bg-dark text-white border-secondary" rows="2">{{ $coaching->qualification }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-5 text-end">
        <button type="submit" class="btn btn-lg btn-warning text-dark fw-bold">
            <i class="fas fa-edit"></i> Update Coaching
        </button>
    </div>

</form>

@endsection
