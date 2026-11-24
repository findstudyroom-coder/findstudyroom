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
    .admin-index-table { table-layout: fixed; width: 100%; color: #e2e8f0; }
    .admin-index-table th, .admin-index-table td { white-space: normal !important; word-break: break-word; vertical-align: middle; border-color: #334155; }
    .admin-index-table tbody tr { background: #111827; }
    .admin-index-table tbody tr:hover { background: #17233b; }
    .admin-index-table thead th { background: #0f172a; color: #e2e8f0; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
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
    .theme-pill-blue { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
    .theme-pill-amber { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
    .theme-pill-teal { background: rgba(20, 184, 166, 0.2); color: #2dd4bf; border: 1px solid rgba(20, 184, 166, 0.3); }
    .theme-pill-emerald { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
    .theme-pill-purple { background: rgba(168, 85, 247, 0.2); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.3); }
    .theme-pill-rose { background: rgba(244, 63, 94, 0.2); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.3); }
</style>

<div class="container-fluid py-4">

    <!-- Top Navigation Tabs -->
    <ul class="nav nav-tabs nav-tabs-dark mb-4">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('commando-classes.index') }}">
                <i class="fas fa-fist-raised text-warning me-1"></i> The Movement Disciplines
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('commando-instructors.index') }}">
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
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="fs-4">🥋</span>
                <h4 class="fw-bold mb-0 text-white">The Movement — Master Instructors</h4>
            </div>
            <p class="text-muted small mb-0 mt-1">Manage the black belt senseis, cert credentials, bios, and specialty tags rendered on The Movement page.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('the-movement') }}#instructors" target="_blank" class="btn btn-outline-light btn-sm px-3 rounded-3 d-flex align-items-center gap-2">
                <i class="fas fa-external-link-alt text-warning"></i>
                <span>View Public Page</span>
            </a>
            <a href="{{ route('commando-instructors.create') }}" class="btn btn-warning btn-sm px-3 fw-bold rounded-3 text-dark d-flex align-items-center gap-2 shadow-sm">
                <i class="fas fa-plus-circle"></i>
                <span>Add Master Instructor</span>
            </a>
        </div>
    </div>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm bg-success text-white mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- KPI Summary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span class="fw-semibold">Total Instructors</span>
                    <i class="fas fa-users text-primary"></i>
                </div>
                <div class="h3 fw-bold text-white mt-2 mb-0">{{ number_format($stats['total']) }}</div>
                <div class="text-xs text-muted mt-1">Senseis configured</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-card p-3 border-success border-opacity-25">
                <div class="d-flex justify-content-between align-items-center text-success small">
                    <span class="fw-semibold">Active &amp; Visible</span>
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="h3 fw-bold text-success mt-2 mb-0">{{ number_format($stats['active']) }}</div>
                <div class="text-xs text-muted mt-1">Rendered on frontend</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-card p-3 border-secondary border-opacity-25">
                <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span class="fw-semibold">Inactive / Hidden</span>
                    <i class="fas fa-eye-slash"></i>
                </div>
                <div class="h3 fw-bold text-muted mt-2 mb-0">{{ number_format($stats['inactive']) }}</div>
                <div class="text-xs text-muted mt-1">Draft or on leave</div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card bg-dark border-secondary mb-4 shadow-sm">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('commando-instructors.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-secondary border-secondary text-white"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-dark border-secondary text-white" placeholder="Search by name, role, rank, bio, or specialties...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="status" class="form-select form-select-sm bg-dark border-secondary text-white">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active Only</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive Only</option>
                    </select>
                </div>
                <div class="col-6 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold">Filter</button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('commando-instructors.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Instructors Table -->
    <div class="card bg-dark border-secondary shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle admin-index-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;"># Order</th>
                        <th style="width: 250px;">Sensei / Instructor</th>
                        <th style="width: 170px;">Rank &amp; Experience</th>
                        <th>Bio &amp; Specialties</th>
                        <th style="width: 110px;">Theme</th>
                        <th style="width: 90px;">Status</th>
                        <th style="width: 140px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($instructors as $inst)
                        <tr>
                            <!-- Order -->
                            <td>
                                <span class="badge bg-secondary font-monospace">{{ $inst->order_no ?? 0 }}</span>
                            </td>

                            <!-- Instructor Info with Avatar -->
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $inst->image_url }}" alt="{{ $inst->name }}" class="rounded-circle object-fit-cover border border-secondary" style="width: 44px; height: 44px;">
                                    <div>
                                        <div class="fw-bold text-white fs-6">{{ $inst->name }}</div>
                                        <div class="badge theme-pill-{{ $inst->color_theme ?: 'blue' }} mt-0.5" style="font-size: 10px;">
                                            {{ $inst->role_title }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Rank & Experience -->
                            <td>
                                <div class="fw-bold text-white small">{{ $inst->rank_badge ?: 'Certified Dan' }}</div>
                                <div class="badge bg-dark border border-secondary text-warning mt-1" style="font-size: 10px;">
                                    {{ $inst->experience ?: 'Veteran Sensei' }}
                                </div>
                            </td>

                            <!-- Bio & Specialties -->
                            <td>
                                <div class="text-muted small mb-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $inst->bio }}
                                </div>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($inst->specialties_list as $spec)
                                        <span class="badge bg-secondary text-white font-normal" style="font-size: 9px;">{{ $spec }}</span>
                                    @endforeach
                                </div>
                            </td>

                            <!-- Theme Pill -->
                            <td>
                                <span class="badge theme-pill-{{ $inst->color_theme ?: 'blue' }} text-capitalize px-2 py-1">
                                    {{ $inst->color_theme ?: 'Blue' }}
                                </span>
                            </td>

                            <!-- Status Toggle -->
                            <td>
                                <form action="{{ route('commando-instructors.toggle-status', $inst->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent" title="Click to toggle status">
                                        @if($inst->status)
                                            <span class="badge bg-success" style="cursor: pointer;">Active</span>
                                        @else
                                            <span class="badge bg-secondary" style="cursor: pointer;">Inactive</span>
                                        @endif
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('commando-instructors.edit', $inst->id) }}" class="btn btn-warning btn-sm" title="Edit Instructor">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('commando-instructors.destroy', $inst->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this Master Instructor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="mb-2 fs-3">🥋</div>
                                <div class="fw-semibold">No Master Instructors found.</div>
                                <p class="small text-muted mb-3">Add instructors to render them in the Lineage &amp; Mastery section.</p>
                                <a href="{{ route('commando-instructors.create') }}" class="btn btn-warning btn-sm fw-bold">
                                    <i class="fas fa-plus-circle me-1"></i> Add Instructor
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($instructors->hasPages())
            <div class="card-footer bg-dark border-secondary py-3">
                {{ $instructors->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
