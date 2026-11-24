@extends('admin.master')

@section('content')
<style>
 .card {
    background: #1e293b !important;
    border: 1px solid #334155;
    border-radius: 16px;
    padding: 24px;
}

.card h5 {
    color: #fff;
    font-weight: 600;
    margin-bottom: 20px;
}

label {
    color: #e2e8f0;
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
}

.select-box {
    width: 100%;
    height: 50px !important;
    border: 1px solid #475569;
    border-radius: 10px;
    background: #fff;
    padding: 10px;
    font-size: 14px;
}

.select-box:focus {
    border-color: #38bdf8;
    box-shadow: 0 0 0 3px rgba(56,189,248,.2);
}
</style>
<div class="container mt-4">

    <h3 class="mb-4">Add Tutor Profile</h3>

    <form method="POST" action="{{ route('tutors.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- ================= BASIC INFO ================= -->
        <div class="card p-4 mb-4">
            <h5 class="mb-3">Basic Information</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control"
                        placeholder="Enter full name (e.g. Rahul Sharma)">
                </div>

                <div class="col-md-6">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">Select Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Profile Image</label>
                    <input type="file" name="profile_image" class="form-control">
                </div>
            </div>
        </div>

        <!-- ================= CONTACT ================= -->
        <div class="card p-4 mb-4">
            <h5 class="mb-3">Contact Details</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control"
                        placeholder="Enter phone number (optional)">
                </div>

                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                        placeholder="Enter email address">
                </div>
            </div>
        </div>

        <!-- ================= TEACHING INFO ================= -->
        <div class="card p-4 mb-4">
            <h5 class="mb-3">Teaching Information</h5>

            <div class="row g-4">

                <div class="col-md-6">
                    <label>Subjects</label>
                    <div class="p-3 rounded" style="background: #0f172a; max-height: 200px; overflow-y: auto;">
                        @foreach($subject as $sub)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="subject[]" value="{{ $sub->name }}" id="subject_{{ $sub->id }}" {{ in_array($sub->name, old('subject', [])) ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="subject_{{ $sub->id }}">{{ $sub->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-6">
                    <label>Classes</label>
                    <div class="p-3 rounded" style="background: #0f172a; max-height: 200px; overflow-y: auto;">
                        @foreach($classes as $class)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="classes[]" value="{{ $class->name }}" id="class_{{ $class->id }}" {{ in_array($class->name, old('classes', [])) ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="class_{{ $class->id }}">{{ $class->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-6">
                    <label>Boards</label>
                    <div class="p-3 rounded" style="background: #0f172a; max-height: 200px; overflow-y: auto;">
                        @foreach($boards as $board)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="boards[]" value="{{ $board->board_name }}" id="board_{{ $board->id }}" {{ in_array($board->board_name, old('boards', [])) ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="board_{{ $board->id }}">{{ $board->board_name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-6">
                    <label>Exam Preparation</label>
                    <div class="p-3 rounded" style="background: #0f172a; max-height: 200px; overflow-y: auto;">
                        @foreach($examPreparations as $exam)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="exam_preparation[]" value="{{ $exam->title }}" id="exam_{{ $exam->id }}" {{ in_array($exam->title, old('exam_preparation', [])) ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="exam_{{ $exam->id }}">{{ $exam->title }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <!-- ================= EXPERIENCE ================= -->
        <div class="card p-4 mb-4">
            <h5 class="mb-3">Experience</h5>

            <div class="row g-3">

                <div class="col-md-6">
                    <label>Experience (Years)</label>
                    <input type="number" name="experience_years" class="form-control"
                        placeholder="e.g. 5">
                </div>

                <div class="col-md-6">
                    <label>Total Students Taught</label>
                    <input type="number" name="total_students_taught" class="form-control"
                        placeholder="e.g. 120">
                </div>

                <div class="col-md-12">
                    <label>About Me</label>
                    <textarea name="about_me" class="form-control"
                        placeholder="Write short bio about teaching style, strengths, etc."></textarea>
                </div>

            </div>
        </div>

        <!-- ================= EDUCATION ================= -->
        <div class="card p-4 mb-4">
            <h5 class="mb-3">Education & Achievements</h5>

            <div class="row g-3">

                <!--<div class="col-md-4">-->
                <!--    <label>Qualifications</label>-->
                <!--    <input type="text" name="qualifications" class="form-control"-->
                <!--        placeholder="e.g. B.Sc, M.Sc">-->
                <!--</div>-->

                <!--<div class="col-md-4">-->
                <!--    <label>Certifications</label>-->
                <!--    <input type="text" name="certifications" class="form-control"-->
                <!--        placeholder="e.g. CTET, NET">-->
                <!--</div>-->

                <!--<div class="col-md-4">-->
                <!--     <label>Achievements</label>-->
            
                <!--    <div id="achievements-wrapper">-->
                <!--        <div class="d-flex mb-2">-->
                <!--            <input type="text" name="achievements[]" class="form-control custom-input"-->
                <!--                placeholder="e.g. AIR 120 in GATE">-->
                <!--            <button type="button" class="btn btn-danger ms-2 remove-btn">✕</button>-->
                <!--        </div>-->
                <!--    </div>-->

                <!--    <button type="button" class="btn btn-sm btn-outline-light mt-2" onclick="addAchievement()">-->
                <!--        + Add More-->
                <!--    </button>-->
                <!--</div>-->
                
                <div class="row g-3">

    <div class="col-md-3">
        <label>Qualifications</label>
        <input type="text" name="qualifications" class="form-control custom-input"
            placeholder="B.Sc, M.Sc">
    </div>

    <div class="col-md-3">
        <label>Certifications</label>
        <input type="text" name="certifications" class="form-control custom-input"
            placeholder="CTET, NET">
    </div>

    <div class="col-md-6">
          <label>Achievements</label>
            
                    <div id="achievements-wrapper">
                        <div class="d-flex mb-2">
                            <input type="text" name="achievements[]" class="form-control custom-input"
                                placeholder="e.g. AIR 120 in GATE">
                            <button type="button" class="btn btn-danger ms-2 remove-btn">✕</button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-light mt-2" onclick="addAchievement()">
                        + Add More
                    </button>
    </div>

</div>
            </div>
        </div>

        <!-- ================= LOCATION ================= -->
        <div class="card p-4 mb-4">
            <h5 class="mb-3">Location</h5>

            <div class="row g-3">

                <div class="col-md-4">
                    <label>State</label>
                    <select name="state_id" id="state" class="form-control">
                        <option value="">Select State</option>
                        @foreach($state as $s)
                            <option value="{{ $s->id }}" {{ old('state_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label>District</label>
                    <select name="district_id" id="district" class="form-control">
                        <option value="">Select District</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Tehsil</label>
                    <select name="tehsil_id" id="tehsil" class="form-control">
                        <option value="">Select Tehsil</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Area</label>
                    <input type="text" name="location_area" value="{{ old('location_area') }}" class="form-control"
                        placeholder="Area (e.g. Vaishali Nagar)">
                </div>

                <div class="col-md-4">
                    <label>Pincode</label>
                    <input type="text" name="pincode" value="{{ old('pincode') }}" class="form-control"
                        placeholder="Pincode">
                </div>

                <div class="col-md-4">
                    <label>Full Address</label>
                    <input type="text" name="full_address" value="{{ old('full_address') }}" class="form-control"
                        placeholder="Full address (optional)">
                </div>

            </div>
        </div>

        <!-- ================= PRICING ================= -->
        <div class="card p-4 mb-4">
            <h5 class="mb-3">Pricing</h5>

            <div class="row g-3">

                <div class="col-md-4">
                    <input type="number" name="hourly_rate" class="form-control"
                        placeholder="Hourly rate (₹500)">
                </div>

                <div class="col-md-4">
                    <input type="number" name="demo_price" class="form-control"
                        placeholder="Demo price (optional)">
                </div>

                <div class="col-md-4">
                    <select name="teaching_mode" class="form-control">
                        <option value="">Teaching Mode</option>
                        <option>online</option>
                        <option>offline</option>
                        <option>both</option>
                    </select>
                </div>

            </div>

            <div class="mt-3">
                <label><input type="checkbox" name="demo_available"> Demo Available</label>
            </div>
        </div>

        <!-- ================= STATUS ================= -->
        <div class="card p-4 mb-4">
            <h5>Status</h5>

            <label><input type="checkbox" name="available"> Available</label><br>
            <label><input type="checkbox" name="is_verified"> Verified</label><br>
            <label><input type="checkbox" name="is_featured"> Featured</label>
        </div>

        <!-- SUBMIT -->
        <div class="text-end">
            <button class="btn btn-primary px-4">Save Tutor</button>
        </div>

    </form>

</div>
<script>
function addAchievement() {
    let html = `
        <div class="d-flex mb-2">
            <input type="text" name="achievements[]" class="form-control custom-input"
                placeholder="e.g. Rank, Award, Achievement">
            <button type="button" class="btn btn-danger ms-2 remove-btn">✕</button>
        </div>
    `;
    document.getElementById('achievements-wrapper').insertAdjacentHTML('beforeend', html);
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-btn')) {
        e.target.parentElement.remove();
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stateEl = document.getElementById('state');
    const districtEl = document.getElementById('district');
    const tehsilEl = document.getElementById('tehsil');

    function loadTehsils(districtId, selectedTehsil = null) {
        if (!districtId) {
            tehsilEl.innerHTML = '<option value="">Select Tehsil</option>';
            return;
        }

        fetch('/get-tehsils/' + districtId)
            .then(res => res.json())
            .then(data => {
                tehsilEl.innerHTML = '<option value="">Select Tehsil</option>';
                data.forEach(t => {
                    tehsilEl.innerHTML += `<option value="${t.id}" ${selectedTehsil == t.id ? 'selected' : ''}>${t.name}</option>`;
                });
            });
    }

    function loadDistricts(stateId, selectedDistrict = null, selectedTehsil = null) {
        if (!stateId) {
            districtEl.innerHTML = '<option value="">Select District</option>';
            tehsilEl.innerHTML = '<option value="">Select Tehsil</option>';
            return;
        }

        fetch('/get-districts/' + stateId)
            .then(res => res.json())
            .then(data => {
                districtEl.innerHTML = '<option value="">Select District</option>';
                tehsilEl.innerHTML = '<option value="">Select Tehsil</option>';

                data.forEach(d => {
                    districtEl.innerHTML += `<option value="${d.id}" ${selectedDistrict == d.id ? 'selected' : ''}>${d.name}</option>`;
                });

                if (selectedDistrict) {
                    loadTehsils(selectedDistrict, selectedTehsil);
                }
            });
    }

    stateEl?.addEventListener('change', function () {
        const stateId = this.value;
        loadDistricts(stateId);
    });

    districtEl?.addEventListener('change', function () {
        const districtId = this.value;
        loadTehsils(districtId);
    });

    const oldState = @json(old('state_id'));
    const oldDistrict = @json(old('district_id'));
    const oldTehsil = @json(old('tehsil_id'));

    if (oldState) {
        loadDistricts(oldState, oldDistrict, oldTehsil);
    }
});
</script>
@endsection