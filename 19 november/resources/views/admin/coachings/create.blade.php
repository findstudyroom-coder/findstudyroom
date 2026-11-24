@extends('admin.master')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Select2 Dark Theme Customization */
    .select2-container--default .select2-selection--multiple,
    .select2-container--default .select2-selection--single {
        background-color: #212529; /* bg-dark */
        border: 1px solid #6c757d; /* border-secondary */
        color: #fff;
        min-height: 38px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #fff;
        line-height: 36px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #343a40;
        border: 1px solid #6c757d;
        color: #fff;
        margin-top: 5px;
    }
    .select2-container--default .select2-results__option--selected {
        background-color: #495057;
    }
    .select2-dropdown {
        background-color: #212529;
        border: 1px solid #6c757d;
        color: #fff;
    }
    .select2-search--dropdown .select2-search__field {
        background-color: #343a40 !important;
        color: #fff !important;
        border: 1px solid #6c757d !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        border-right: 1px solid #6c757d;
        padding: 0 4px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        background-color: #dc3545;
        color: #fff;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #343a40;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #0ea5e9;
    }
    .select2-container .select2-search--inline .select2-search__field {
        margin-top: 6px;
        background-color: transparent !important;
        border: none !important;
        color: #fff !important;
        box-shadow: none !important;
    }
    .select2-container .select2-search--inline .select2-search__field::placeholder {
        color: #adb5bd;
    }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white">🎓 Add New Coaching</h2>
    <a href="{{ route('coachings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<form action="{{ route('coachings.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <!-- Basic Information -->
    <div class="card bg-dark border-secondary mb-4">
        <div class="card-header bg-secondary text-white">Basic Information</div>
        <div class="card-body">
            <div class="row g-3 text-white">
                <div class="col-md-6">
                    <label>Coaching Name *</label>
                    <input type="text" name="coaching_name" class="form-control bg-dark text-white border-secondary" required>
                </div>
                <div class="col-md-6">
                    <label>Coaching Type</label>
                    <select name="coaching_type" class="form-select bg-dark text-white border-secondary">
                        <option value="">Select Type</option>
                        <option value="Tuition">Tuition</option>
                        <option value="Coaching Institute">Coaching Institute</option>
                        <option value="Home Tuition">Home Tuition</option>
                        <option value="Online Coaching">Online Coaching</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Established Year</label>
                    <input type="number" name="established_year" class="form-control bg-dark text-white border-secondary" min="1900" max="2100">
                </div>
                <div class="col-md-12">
                    <label>Short Description</label>
                    <input type="text" name="short_description" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-12">
                    <label>Description</label>
                    <textarea name="description" class="form-control bg-dark text-white border-secondary" rows="4"></textarea>
                </div>
                <div class="col-md-4">
                    <label>Logo</label>
                    <input type="file" name="logo" class="form-control bg-dark text-white border-secondary" accept="image/*">
                </div>
                <div class="col-md-4">
                    <label>Cover Image</label>
                    <input type="file" name="cover_image" class="form-control bg-dark text-white border-secondary" accept="image/*">
                </div>
                <div class="col-md-4">
                    <label>Gallery Images (Multiple)</label>
                    <input type="file" name="gallery_images[]" class="form-control bg-dark text-white border-secondary" accept="image/*" multiple>
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
                    <input type="text" name="contact_person" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-4">
                    <label>Mobile Number</label>
                    <input type="text" name="mobile_number" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-4">
                    <label>WhatsApp Number</label>
                    <input type="text" name="whatsapp_number" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-4">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-4">
                    <label>Website</label>
                    <input type="url" name="website" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-4">
                    <label>Facebook URL</label>
                    <input type="url" name="facebook" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-4">
                    <label>Instagram URL</label>
                    <input type="url" name="instagram" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-4">
                    <label>YouTube URL</label>
                    <input type="url" name="youtube" class="form-control bg-dark text-white border-secondary">
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
                    <input type="text" name="state" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-4">
                    <label>City</label>
                    <input type="text" name="city" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-4">
                    <label>Area / Locality</label>
                    <input type="text" name="area_locality" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-12">
                    <label>Full Address</label>
                    <textarea name="full_address" class="form-control bg-dark text-white border-secondary" rows="2"></textarea>
                </div>
                <div class="col-md-4">
                    <label>Landmark</label>
                    <input type="text" name="landmark" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-4">
                    <label>Pincode</label>
                    <input type="text" name="pincode" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-12">
                    <label>Google Map URL</label>
                    <input type="url" name="google_map_url" class="form-control bg-dark text-white border-secondary">
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
                    <select name="courses_offered[]" class="form-select select2-multiple bg-dark text-white border-secondary" multiple>
                        @foreach(['UPSC', 'SSC', 'Banking', 'Railway', 'NEET', 'JEE', 'GATE', 'CAT', 'CLAT', 'CA', 'CS', 'CMA', 'NDA', 'CUET', 'School Tuition', 'College Tuition', 'Spoken English', 'Computer Courses'] as $course)
                            <option value="{{ $course }}">{{ $course }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Class Level (Multiple)</label>
                    <select name="class_level[]" class="form-select select2-multiple bg-dark text-white border-secondary" multiple>
                        @foreach(['Class 1-5', '6-8', '9-10', '11-12', 'Graduation', 'Competitive Exams'] as $level)
                            <option value="{{ $level }}">{{ $level }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Subjects (Type and press Enter, optional)</label>
                    <select name="subjects[]" class="form-select select2-tags bg-dark text-white border-secondary" multiple data-placeholder="Enter subjects..."></select>
                </div>
                <div class="col-md-6">
                    <label>Mode</label>
                    <select name="mode" class="form-select bg-dark text-white border-secondary">
                        <option value="">Select Mode</option>
                        <option value="Offline">Offline</option>
                        <option value="Online">Online</option>
                        <option value="Hybrid">Hybrid</option>
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
                    <input type="number" step="0.01" name="starting_fees" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-3">
                    <label>Monthly Fees</label>
                    <input type="number" step="0.01" name="monthly_fees" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-3">
                    <label>Course Fees</label>
                    <input type="number" step="0.01" name="course_fees" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-3">
                    <label>Registration Fees</label>
                    <input type="number" step="0.01" name="registration_fees" class="form-control bg-dark text-white border-secondary">
                </div>
                
                <div class="col-md-12 mt-4"><hr><h5>Batch Timings & Features</h5></div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="morning_batch" value="1" id="morning">
                        <label class="form-check-label" for="morning">Morning Batch</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="afternoon_batch" value="1" id="afternoon">
                        <label class="form-check-label" for="afternoon">Afternoon Batch</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="evening_batch" value="1" id="evening">
                        <label class="form-check-label" for="evening">Evening Batch</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="weekend_batch" value="1" id="weekend">
                        <label class="form-check-label" for="weekend">Weekend Batch</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="demo_class_available" value="1" id="demo">
                        <label class="form-check-label" for="demo">Demo Available</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="scholarship_available" value="1" id="scholar">
                        <label class="form-check-label" for="scholar">Scholarship</label>
                    </div>
                </div>
                <div class="col-md-4 mt-3">
                    <label>Batch Size</label>
                    <input type="text" name="batch_size" class="form-control bg-dark text-white border-secondary" placeholder="e.g. 30-40 Students">
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
                    <label>Faculty Names (Type and press Enter)</label>
                    <select name="faculty_names[]" class="form-select select2-tags bg-dark text-white border-secondary" multiple data-placeholder="Enter faculty names..."></select>
                </div>
                <div class="col-md-6">
                    <label>Faculty Experience</label>
                    <textarea name="faculty_experience" class="form-control bg-dark text-white border-secondary" rows="2"></textarea>
                </div>
                <div class="col-md-6">
                    <label>Qualification</label>
                    <textarea name="qualification" class="form-control bg-dark text-white border-secondary" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-5 text-end">
        <button type="submit" class="btn btn-lg btn-success">
            <i class="fas fa-save"></i> Save Coaching
        </button>
    </div>

</form>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-multiple').select2({
            width: '100%',
            placeholder: 'Select options'
        });
        
        $('.select2-tags').select2({
            width: '100%',
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: 'Type and press enter'
        });
    });
</script>
@endsection

@endsection
