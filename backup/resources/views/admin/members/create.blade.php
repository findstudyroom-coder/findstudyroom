@extends('admin.master')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color:#6366f1;">➕ Add New Member</h4>
        <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>⚠ {{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data" id="memberForm">
        @csrf

        <div class="row g-4">

            {{-- LEFT: Member Info --}}
            <div class="col-lg-8">
                <div class="card shadow border-0 rounded-4 bg-dark text-white">
                    <div class="card-header rounded-top-4 text-white"
                         style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                        <h6 class="mb-0">👤 Member Information</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- Library --}}
                            <div class="col-md-12">
                                <label class="form-label">🏛 Select Library <span class="text-danger">*</span></label>
                                <select name="library_id" id="library_id" class="form-select bg-dark text-white border-secondary" required>
                                    <option value="">— Choose Library —</option>
                                    @foreach($libraries as $lib)
                                        <option value="{{ $lib->id }}" {{ old('library_id') == $lib->id ? 'selected' : '' }}>
                                            {{ $lib->name }} — {{ $lib->location }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Name --}}
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control bg-dark text-white border-secondary"
                                       value="{{ old('name') }}" placeholder="e.g. Rahul Sharma" required>
                            </div>

                            {{-- Mobile --}}
                            <div class="col-md-6">
                                <label class="form-label">📱 Mobile Number <span class="text-danger">*</span></label>
                                <input type="text" name="mobile" class="form-control bg-dark text-white border-secondary"
                                       value="{{ old('mobile') }}" placeholder="10-digit mobile" maxlength="15" required>
                            </div>

                            {{-- Location --}}
                            <div class="col-md-12">
                                <label class="form-label">📍 Location / Address</label>
                                <input type="text" name="location" class="form-control bg-dark text-white border-secondary"
                                       value="{{ old('location') }}" placeholder="Home address or area">
                            </div>

                            {{-- Plan --}}
                            <div class="col-md-6">
                                <label class="form-label">📋 Plan</label>
                                <select name="plan_id" class="form-select bg-dark text-white border-secondary">
                                    <option value="">— No Plan —</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} ({{ $plan->validity }} days — ₹{{ $plan->amount }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Shift Type --}}
                            <div class="col-md-6">
                                <label class="form-label">⏰ Shift Type <span class="text-danger">*</span></label>
                                <select name="shift_type" id="shift_type" class="form-select bg-dark text-white border-secondary" required>
                                    @foreach($shifts as $key => $label)
                                        <option value="{{ $key }}" {{ old('shift_type', 'full_day') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Seat --}}
                            <div class="col-md-12">
                                <label class="form-label">🪑 Seat Number</label>
                                <select name="seat_id" id="seat_id" class="form-select bg-dark text-white border-secondary">
                                    <option value="">— Select Library & Shift first —</option>
                                </select>
                                <small class="text-muted">Select a library and shift to load available seats.</small>
                            </div>

                            {{-- Dates --}}
                            <div class="col-md-6">
                                <label class="form-label">📅 Start Date</label>
                                <input type="date" name="start_date" class="form-control bg-dark text-white border-secondary"
                                       value="{{ old('start_date', date('Y-m-d')) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">📅 End Date</label>
                                <input type="date" name="end_date" class="form-control bg-dark text-white border-secondary"
                                       value="{{ old('end_date') }}">
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select bg-dark text-white border-secondary">
                                    <option value="active"   {{ old('status','active') === 'active'   ? 'selected' : '' }}>✅ Active</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>⏸ Inactive</option>
                                    <option value="expired"  {{ old('status') === 'expired'  ? 'selected' : '' }}>❌ Expired</option>
                                </select>
                            </div>

                            {{-- Notes --}}
                            <div class="col-md-12">
                                <label class="form-label">📝 Notes</label>
                                <textarea name="notes" rows="2" class="form-control bg-dark text-white border-secondary"
                                          placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Photo + Document --}}
            <div class="col-lg-4">

                {{-- Photo Upload --}}
                <div class="card shadow border-0 rounded-4 bg-dark text-white mb-3">
                    <div class="card-header rounded-top-4 text-white"
                         style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                        <h6 class="mb-0">📷 Member Photo</h6>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div id="photoPreviewBox"
                             style="width:120px;height:120px;border-radius:50%;overflow:hidden;background:#2a2a3e;border:3px dashed #6366f1;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;cursor:pointer;"
                             onclick="document.getElementById('photo').click()">
                            <img id="photoPreview" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;">
                            <span id="photoPlaceholder" class="text-muted small">Click to upload</span>
                        </div>
                        <input type="file" name="photo" id="photo" accept="image/*" class="d-none">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('photo').click()">
                            📤 Upload Photo
                        </button>
                        <p class="text-muted small mt-2 mb-0">JPG/PNG, max 2MB</p>
                    </div>
                </div>

                {{-- Document Upload --}}
                <div class="card shadow border-0 rounded-4 bg-dark text-white">
                    <div class="card-header rounded-top-4 text-white"
                         style="background:linear-gradient(135deg,#10b981,#059669);">
                        <h6 class="mb-0">📄 Identity Document</h6>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div id="docPreviewBox"
                             style="width:80px;height:100px;background:#2a2a3e;border:3px dashed #10b981;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;border-radius:8px;cursor:pointer;"
                             onclick="document.getElementById('document').click()">
                            <span id="docPlaceholder" class="text-muted" style="font-size:2rem;">📄</span>
                        </div>
                        <input type="file" name="document" id="document" accept=".jpg,.jpeg,.png,.pdf" class="d-none">
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="document.getElementById('document').click()">
                            📤 Upload Document
                        </button>
                        <p id="docName" class="text-muted small mt-2 mb-0">PDF/JPG, max 5MB</p>
                        <p class="text-muted small mb-0">Aadhar, PAN, etc.</p>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="mt-3">
                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow">
                        💾 Save Member
                    </button>
                </div>

            </div>
        </div>

    </form>
</div>
@endsection

@section('scripts')
<script>
// ── Photo Preview ──
document.getElementById('photo').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('photoPreview').src = e.target.result;
        document.getElementById('photoPreview').style.display = 'block';
        document.getElementById('photoPlaceholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
});

// ── Document Name Preview ──
document.getElementById('document').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    document.getElementById('docPlaceholder').textContent = '✅';
    document.getElementById('docName').textContent = file.name;
});

// ── Load Available Seats via AJAX ──
function loadSeats() {
    const libraryId = document.getElementById('library_id').value;
    const shiftType = document.getElementById('shift_type').value;
    const seatSelect = document.getElementById('seat_id');

    if (!libraryId) {
        seatSelect.innerHTML = '<option value="">— Select Library first —</option>';
        return;
    }

    seatSelect.innerHTML = '<option value="">Loading...</option>';

    fetch(`/members/ajax/seats?library_id=${libraryId}&shift_type=${shiftType}`)
        .then(r => r.json())
        .then(data => {
            seatSelect.innerHTML = '<option value="">— Choose a seat (optional) —</option>';
            if (data.data && data.data.length > 0) {
                data.data.forEach(seat => {
                    const opt = document.createElement('option');
                    opt.value = seat.id;
                    opt.textContent = `Seat ${seat.seat_number} (${seat.seat_type})`;
                    seatSelect.appendChild(opt);
                });
            } else {
                seatSelect.innerHTML = '<option value="">No seats available</option>';
            }
        })
        .catch(() => {
            seatSelect.innerHTML = '<option value="">Error loading seats</option>';
        });
}

document.getElementById('library_id').addEventListener('change', loadSeats);
document.getElementById('shift_type').addEventListener('change', loadSeats);
</script>
@endsection
