@extends('admin.master')

@section('content')

<style>
    .stat-card {
        background: #111827;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 1rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
    }
    .admin-index-table { 
        width: 100%; 
        color: #e2e8f0; 
        --bs-table-bg: #111827 !important;
        --bs-table-color: #e2e8f0 !important;
        --bs-table-hover-bg: #17233b !important;
        --bs-table-hover-color: #ffffff !important;
        border-color: #334155 !important;
    }
    .admin-index-table th, 
    .admin-index-table td { 
        background-color: #111827 !important; 
        color: #e2e8f0 !important; 
        vertical-align: middle; 
        border-color: #334155 !important; 
    }
    .admin-index-table tbody tr:hover td { 
        background-color: #17233b !important; 
    }
    .admin-index-table thead th { 
        background-color: #0f172a !important; 
        color: #f8fafc !important; 
        font-size: 11px; 
        text-transform: uppercase; 
        letter-spacing: 0.75px; 
        border-color: #334155 !important;
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
    .coaching-logo-thumb {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #334155;
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

    <!-- Header & Action Buttons -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1">
                <i class="fas fa-school text-warning me-2"></i> Coaching Institutes Master
            </h3>
            <p class="text-muted small mb-0">Manage coaching centers (The Movement, The Achievers, etc.) and their linked disciplines, staff & schedules.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('coachings.create') }}" class="btn btn-warning fw-bold px-3 py-2 shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Add New Coaching
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success bg-success bg-opacity-25 border-success text-white alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger bg-danger bg-opacity-25 border-danger text-white alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Metric Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span class="fw-semibold">Total Coachings</span>
                    <i class="fas fa-university text-warning"></i>
                </div>
                <div class="h3 fw-bold text-white mt-2 mb-0">{{ number_format($stats['total']) }}</div>
                <div class="text-xs text-muted mt-1">Institutes registered</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card p-3 border-success border-opacity-25">
                <div class="d-flex justify-content-between align-items-center text-success small">
                    <span class="fw-semibold">Active &amp; Published</span>
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="h3 fw-bold text-success mt-2 mb-0">{{ number_format($stats['active']) }}</div>
                <div class="text-xs text-muted mt-1">Visible on website</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card p-3 border-secondary border-opacity-25">
                <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span class="fw-semibold">Inactive / Draft</span>
                    <i class="fas fa-eye-slash"></i>
                </div>
                <div class="h3 fw-bold text-muted mt-2 mb-0">{{ number_format($stats['inactive']) }}</div>
                <div class="text-xs text-muted mt-1">Hidden from public</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card p-3 border-info border-opacity-25">
                <div class="d-flex justify-content-between align-items-center text-info small">
                    <span class="fw-semibold">Cities Covered</span>
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="h3 fw-bold text-info mt-2 mb-0">{{ number_format($stats['cities']) }}</div>
                <div class="text-xs text-muted mt-1">Locations present</div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card bg-dark border-secondary mb-4 shadow-sm">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('coachings.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-secondary border-secondary text-white"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-dark border-secondary text-white" placeholder="Search by name, city, phone, type...">
                    </div>
                </div>

                <div class="col-6 col-md-2">
                    <select name="city" class="form-select form-select-sm bg-dark border-secondary text-white">
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <select name="coaching_type" class="form-select form-select-sm bg-dark border-secondary text-white">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('coaching_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <select name="status" class="form-select form-select-sm bg-dark border-secondary text-white">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active Only</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive Only</option>
                    </select>
                </div>

                <div class="col-6 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-warning btn-sm fw-bold flex-grow-1">Filter</button>
                    @if(request()->hasAny(['search', 'status', 'city', 'coaching_type']))
                        <a href="{{ route('coachings.index') }}" class="btn btn-secondary btn-sm" title="Clear Filters">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Coachings Table -->
    <div class="card bg-dark border-secondary shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle admin-index-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
                        <th style="width: 70px;">Logo</th>
                        <th style="min-width: 230px;">Institute Name &amp; Details</th>
                        <th style="min-width: 170px;">Sub-Modules</th>
                        <th style="min-width: 160px;">Contact &amp; Location</th>
                        <th style="min-width: 140px;">Timings &amp; Batches</th>
                        <th style="width: 110px;" class="text-center">Status</th>
                        <th style="width: 140px;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coachings as $coaching)
                    <tr>
                        <td class="text-center text-muted fw-bold">
                            {{ $loop->iteration + $coachings->firstItem() - 1 }}
                        </td>

                        <td>
                            <img src="{{ $coaching->logo_url }}" 
                                 alt="{{ $coaching->coaching_name }}"
                                 class="coaching-logo-thumb shadow-sm"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($coaching->coaching_name) }}&background=0ea5e9&color=fff';">
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-white fs-6">{{ $coaching->coaching_name }}</span>
                                @if($coaching->established_year)
                                    <span class="badge bg-secondary text-white-50" style="font-size: 10px;">Est. {{ $coaching->established_year }}</span>
                                @endif
                            </div>
                            <div class="small text-warning fw-semibold mt-0.5">
                                <i class="fas fa-tag me-1"></i> {{ $coaching->coaching_type ?: 'Coaching Institute' }}
                            </div>
                            <div class="text-muted small mt-1 font-monospace" style="font-size: 11px;">
                                <i class="fas fa-link me-1"></i> slug: <span class="text-info">/coaching/{{ $coaching->slug }}</span>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex flex-column gap-1">
                                <a href="{{ route('commando-classes.index', ['coaching_id' => $coaching->id]) }}" class="badge bg-primary bg-opacity-25 text-info border border-primary border-opacity-50 text-decoration-none py-1.5 px-2 text-start" title="Manage Disciplines">
                                    <i class="fas fa-list me-1"></i> Disciplines: <strong class="text-white">{{ $coaching->classes_count ?? 0 }}</strong>
                                </a>
                                <a href="{{ route('commando-instructors.index', ['coaching_id' => $coaching->id]) }}" class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50 text-decoration-none py-1.5 px-2 text-start" title="Manage Instructors">
                                    <i class="fas fa-user-ninja me-1"></i> Instructors: <strong class="text-white">{{ $coaching->instructors_count ?? 0 }}</strong>
                                </a>
                                <a href="{{ route('commando-schedules.index', ['coaching_id' => $coaching->id]) }}" class="badge bg-info bg-opacity-25 text-light border border-info border-opacity-50 text-decoration-none py-1.5 px-2 text-start" title="Manage Weekly Schedule">
                                    <i class="fas fa-calendar-alt me-1"></i> Schedule: <strong class="text-white">{{ $coaching->schedules_count ?? 0 }} days</strong>
                                </a>
                                <a href="{{ route('commando-trials.index', ['coaching_id' => $coaching->id]) }}" class="badge bg-success bg-opacity-25 text-emerald border border-success border-opacity-50 text-decoration-none py-1 px-2 text-start" title="View Trial Inquiries" style="color: #4ade80;">
                                    <i class="fas fa-user-check me-1"></i> Inquiries: <strong class="text-white">{{ $coaching->trials_count ?? 0 }}</strong>
                                </a>
                            </div>
                        </td>

                        <td>
                            @if($coaching->city || $coaching->area_locality)
                                <div class="small text-white">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $coaching->area_locality ? $coaching->area_locality . ', ' : '' }}{{ $coaching->city ?? '-' }}
                                </div>
                            @endif

                            @if($coaching->mobile_number)
                                <div class="small text-success fw-bold mt-1 d-flex align-items-center gap-1">
                                    <i class="fas fa-phone me-1"></i> {{ $coaching->mobile_number }}
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $coaching->whatsapp_number ?: $coaching->mobile_number);
                                        if (strlen($cleanPhone) === 10) $cleanPhone = '91' . $cleanPhone;
                                    @endphp
                                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-success ms-1" title="Chat on WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>
                            @endif

                            @if($coaching->contact_person)
                                <div class="text-muted small mt-0.5">
                                    <i class="fas fa-user me-1"></i> {{ $coaching->contact_person }}
                                </div>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @if($coaching->morning_batch)<span class="badge bg-secondary" style="font-size: 10px;">Morning</span>@endif
                                @if($coaching->afternoon_batch)<span class="badge bg-secondary" style="font-size: 10px;">Afternoon</span>@endif
                                @if($coaching->evening_batch)<span class="badge bg-secondary" style="font-size: 10px;">Evening</span>@endif
                                @if($coaching->weekend_batch)<span class="badge bg-secondary" style="font-size: 10px;">Weekend</span>@endif
                                @if($coaching->demo_class_available)<span class="badge bg-success bg-opacity-75" style="font-size: 10px;">Demo</span>@endif
                                @if($coaching->mode)<span class="badge bg-info bg-opacity-75 text-dark" style="font-size: 10px;">{{ $coaching->mode }}</span>@endif
                            </div>
                            @if($coaching->starting_fees)
                                <div class="text-muted small mt-1">
                                    From: <span class="text-white fw-bold">₹{{ number_format($coaching->starting_fees) }}</span>
                                </div>
                            @endif
                        </td>

                        <td class="text-center">
                            <form action="{{ route('coachings.toggle-status', $coaching->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $coaching->status ? 'btn-outline-success' : 'btn-outline-secondary' }} px-2 py-1 fw-bold rounded-pill" title="Click to toggle status">
                                    <i class="fas {{ $coaching->status ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                    {{ $coaching->status ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>

                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <!-- View Live Page -->
                                @if($coaching->slug === 'the-movement')
                                    <a href="{{ route('the-movement') }}" target="_blank" class="btn btn-sm btn-outline-info" title="View Public Page">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @else
                                    <a href="{{ route('coaching.show', $coaching->slug) }}" target="_blank" class="btn btn-sm btn-outline-info" title="View Public Page">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @endif

                                <!-- Edit -->
                                <a href="{{ route('coachings.edit', $coaching->id) }}" class="btn btn-sm btn-warning text-dark fw-bold" title="Edit Coaching Details">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('coachings.destroy', $coaching->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($coaching->coaching_name) }}\'? This will also affect linked classes and schedules.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete Coaching">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-school fa-3x mb-3 text-secondary opacity-50"></i>
                            <div class="fs-6">No coaching institutes found matching your query.</div>
                            <a href="{{ route('coachings.create') }}" class="btn btn-warning btn-sm mt-3 fw-bold">
                                <i class="fas fa-plus-circle me-1"></i> Add First Coaching
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coachings->hasPages())
        <div class="card-footer bg-dark border-secondary py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Showing {{ $coachings->firstItem() }} to {{ $coachings->lastItem() }} of {{ $coachings->total() }} coachings
                </div>
                <div>
                    {{ $coachings->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
