@extends('admin.master')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color:#6366f1;">✏️ Edit Member: {{ $member->name }}</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('members.show', $member->id) }}" class="btn btn-outline-info btn-sm">👁 View</a>
            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>⚠ {{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('members.update', $member->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

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

                            <div class="col-md-12">
                                <label class="form-label">🏛 Library</label>
                                <select name="library_id" id="library_id" class="form-select bg-dark text-white border-secondary" required>
                                    @foreach($libraries as $lib)
                                        <option value="{{ $lib->id }}" {{ $member->library_id == $lib->id ? 'selected' : '' }}>
                                            {{ $lib->name }} — {{ $lib->location }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control bg-dark text-white border-secondary"
                                       value="{{ old('name', $member->name) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">📱 Mobile</label>
                                <input type="text" name="mobile" class="form-control bg-dark text-white border-secondary"
                                       value="{{ old('mobile', $member->mobile) }}" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">📍 Location</label>
                                <input type="text" name="location" class="form-control bg-dark text-white border-secondary"
                                       value="{{ old('location', $member->location) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">📋 Plan</label>
                                <select name="plan_id" class="form-select bg-dark text-white border-secondary">
                                    <option value="">— No Plan —</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ $member->plan_id == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} ({{ $plan->validity }} days — ₹{{ $plan->amount }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">⏰ Shift Type</label>
                                <select name="shift_type" id="shift_type" class="form-select bg-dark text-white border-secondary" required>
                                    @foreach($shifts as $key => $label)
                                        <option value="{{ $key }}" {{ $member->shift_type == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">🪑 Seat</label>
                                <select name="seat_id" id="seat_id" class="form-select bg-dark text-white border-secondary">
                                    <option value="">— No Seat —</option>
                                    @foreach($seats as $seat)
                                        <option value="{{ $seat->id }}" {{ $member->seat_id == $seat->id ? 'selected' : '' }}>
                                            Seat {{ $seat->seat_number }} ({{ $seat->seat_type }})
                                            {{ $seat->status === 'occupied' ? '[Current]' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">📅 Start Date</label>
                                <input type="date" name="start_date" class="form-control bg-dark text-white border-secondary"
                                       value="{{ old('start_date', $member->start_date?->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">📅 End Date</label>
                                <input type="date" name="end_date" class="form-control bg-dark text-white border-secondary"
                                       value="{{ old('end_date', $member->end_date?->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select bg-dark text-white border-secondary">
                                    <option value="active"   {{ $member->status === 'active'   ? 'selected' : '' }}>✅ Active</option>
                                    <option value="inactive" {{ $member->status === 'inactive' ? 'selected' : '' }}>⏸ Inactive</option>
                                    <option value="expired"  {{ $member->status === 'expired'  ? 'selected' : '' }}>❌ Expired</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">📝 Notes</label>
                                <textarea name="notes" rows="2" class="form-control bg-dark text-white border-secondary">{{ old('notes', $member->notes) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Photo + Doc --}}
            <div class="col-lg-4">
                <div class="card shadow border-0 rounded-4 bg-dark text-white mb-3">
                    <div class="card-header rounded-top-4 text-white"
                         style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                        <h6 class="mb-0">📷 Photo</h6>
                    </div>
                    <div class="card-body p-4 text-center">
                        <img id="photoPreview" src="{{ $member->photo_url }}"
                             class="rounded-circle border border-secondary mb-3"
                             style="width:100px;height:100px;object-fit:cover;">
                        <br>
                        <input type="file" name="photo" id="photo" accept="image/*" class="d-none">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('photo').click()">
                            📤 Change Photo
                        </button>
                        <p class="text-muted small mt-2 mb-0">Leave blank to keep current.</p>
                    </div>
                </div>

                <div class="card shadow border-0 rounded-4 bg-dark text-white mb-3">
                    <div class="card-header rounded-top-4 text-white"
                         style="background:linear-gradient(135deg,#10b981,#059669);">
                        <h6 class="mb-0">📄 Document</h6>
                    </div>
                    <div class="card-body p-4 text-center">
                        @if($member->document)
                            <a href="{{ $member->document_url }}" target="_blank" class="btn btn-sm btn-outline-info mb-2">
                                📂 View Current Doc
                            </a>
                            <br>
                        @endif
                        <input type="file" name="document" id="document" accept=".jpg,.jpeg,.png,.pdf" class="d-none">
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="document.getElementById('document').click()">
                            📤 Replace Document
                        </button>
                        <p id="docName" class="text-muted small mt-2 mb-0">Leave blank to keep current.</p>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning w-100 py-2 fw-bold shadow text-dark">
                    💾 Update Member
                </button>
            </div>

        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('photo').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => { document.getElementById('photoPreview').src = e.target.result; };
    reader.readAsDataURL(file);
});

document.getElementById('document').addEventListener('change', function () {
    const file = this.files[0];
    if (file) document.getElementById('docName').textContent = file.name;
});
</script>
@endsection
