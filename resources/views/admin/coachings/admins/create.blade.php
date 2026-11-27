@extends('admin.master')

@section('content')
<div class="container-fluid py-4" style="max-width: 900px;">

    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-white"><i class="fas fa-user-plus text-warning me-2"></i> Create Coaching Admin Account</h4>
            <p class="text-muted small mb-0">Provide credentials for a Coaching Institute so they can access their private admin panel.</p>
        </div>
        <a href="{{ route('coachings.admins.index') }}" class="btn btn-outline-light btn-sm px-3">
            <i class="fas fa-arrow-left me-1"></i> Back to Admins List
        </a>
    </div>

    <!-- Error Alerts -->
    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-3 shadow-sm bg-danger text-white mb-4">
            <ul class="mb-0 small">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card bg-dark border-secondary rounded-3 shadow-sm p-4">
        <form action="{{ route('coachings.admins.store') }}" method="POST">
            @csrf

            <!-- 1. Select Coaching Institute -->
            <div class="mb-4 pb-3 border-bottom border-secondary">
                <label class="form-label text-warning fw-bold small text-uppercase">1. Select Coaching Institute *</label>
                <select name="coaching_id" class="form-select bg-dark border-secondary text-white @error('coaching_id') is-invalid @enderror" required>
                    <option value="">-- Choose Institute to grant access --</option>
                    @foreach($coachings as $c)
                        @php $hasAdmin = in_array($c->id, $assignedIds); @endphp
                        <option value="{{ $c->id }}" {{ old('coaching_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->coaching_name }} ({{ $c->city ?? 'No city' }}) {{ $hasAdmin ? '⚠️ [Already has an admin]' : '✅ [No Admin Yet]' }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Each coaching institute should have its own separate login credentials.</small>
            </div>

            <!-- 2. Admin User Details -->
            <div class="row g-3 mb-4 pb-3 border-bottom border-secondary">
                <div class="col-12"><strong class="text-warning small text-uppercase">2. Administrator Personal Details</strong></div>
                <div class="col-md-6">
                    <label class="form-label small text-muted">Admin Full Name *</label>
                    <input type="text" name="name" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Sensei Rajesh Kumar" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted">Mobile Number</label>
                    <input type="text" name="mobile" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 9876543210" value="{{ old('mobile') }}">
                </div>
            </div>

            <!-- 3. Login Credentials -->
            <div class="row g-3 mb-4 pb-3 border-bottom border-secondary">
                <div class="col-12"><strong class="text-warning small text-uppercase">3. Login Credentials</strong></div>
                <div class="col-md-12">
                    <label class="form-label small text-muted">Login Email / Username *</label>
                    <input type="email" name="email" class="form-control bg-dark border-secondary text-white" placeholder="e.g. themovement@findstudyroom.com" value="{{ old('email') }}" required>
                    <small class="text-muted">This email will be used to log in at <code>/coaching/login</code></small>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted">Password * (Min 6 chars)</label>
                    <input type="password" name="password" class="form-control bg-dark border-secondary text-white" placeholder="••••••••" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="form-control bg-dark border-secondary text-white" placeholder="••••••••" required>
                </div>
            </div>

            <!-- 4. Account Status -->
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input bg-dark border-secondary" type="checkbox" name="status" id="adminStatus" value="1" checked>
                    <label class="form-check-label text-white small" for="adminStatus">
                        Active Account (Allow coaching admin to immediately sign in)
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-end gap-2 pt-2">
                <a href="{{ route('coachings.admins.index') }}" class="btn btn-outline-secondary px-3">Cancel</a>
                <button type="submit" class="btn btn-warning px-4 fw-bold text-dark">
                    <i class="fas fa-check-circle me-1"></i> Create Coaching Admin
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
