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
        table-layout: fixed;
        width: 100%;
        --bs-table-bg: #111827 !important;
        --bs-table-striped-bg: #111827 !important;
        --bs-table-hover-bg: #1e293b !important;
        --bs-table-color: #e2e8f0 !important;
        color: #e2e8f0 !important;
        border-color: #334155 !important;
        margin-bottom: 0;
    }
    .admin-index-table th, .admin-index-table td {
        background-color: transparent !important;
        white-space: normal !important;
        word-break: break-word;
        vertical-align: middle;
        border-color: #334155 !important;
        color: #e2e8f0 !important;
        padding: 14px 16px;
    }
    .admin-index-table tbody tr {
        background-color: #111827 !important;
        transition: background-color 0.2s ease;
    }
    .admin-index-table tbody tr:hover {
        background-color: #1e293b !important;
    }
    .admin-index-table thead th {
        background-color: #0f172a !important;
        color: #94a3b8 !important;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.75px;
        font-weight: 700;
        border-bottom: 2px solid #334155 !important;
        padding-top: 14px;
        padding-bottom: 14px;
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
    .theme-badge-slate { background: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3); }
    .theme-badge-amber { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
    .theme-badge-blue  { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
    .coaching-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 6px;
        background: rgba(148, 163, 184, 0.12);
        border: 1px solid rgba(148, 163, 184, 0.25);
        color: #f1f5f9;
        font-size: 12px;
        font-weight: 600;
    }
    .status-badge-active {
        background-color: #16a34a !important;
        color: #ffffff !important;
        border: 1px solid #22c55e !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        letter-spacing: 0.5px !important;
        padding: 6px 14px !important;
        border-radius: 9999px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        box-shadow: 0 2px 5px rgba(22, 163, 74, 0.4) !important;
        cursor: pointer !important;
        user-select: none !important;
    }
    .status-badge-active * {
        color: #ffffff !important;
    }
    .status-badge-active:hover {
        background-color: #15803d !important;
        transform: scale(1.04);
    }
    .status-badge-inactive {
        background-color: #475569 !important;
        color: #ffffff !important;
        border: 1px solid #64748b !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        letter-spacing: 0.5px !important;
        padding: 6px 14px !important;
        border-radius: 9999px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.25) !important;
        cursor: pointer !important;
        user-select: none !important;
    }
    .status-badge-inactive * {
        color: #ffffff !important;
    }
    .status-badge-inactive:hover {
        background-color: #334155 !important;
        transform: scale(1.04);
    }
</style>

<div class="container-fluid py-4">

    <!-- Top Navigation Tabs -->
    <ul class="nav nav-tabs nav-tabs-dark mb-4">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('coachings.index') }}">
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
            <a class="nav-link active" href="{{ route('commando-plans.index') }}">
                <i class="fas fa-tags text-warning me-1"></i> Membership Plans
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('commando-galleries.index') }}">
                <i class="fas fa-images text-warning me-1"></i> Studio Gallery
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('commando-trials.index') }}">
                <i class="fas fa-user-check text-info me-1"></i> Trial Bookings
            </a>
        </li>
    </ul>

    <!-- Header & Action Buttons -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="fs-4">🏷️</span>
                <h4 class="fw-bold mb-0 text-white">Membership Plans &amp; Pricing</h4>
            </div>
            <p class="text-muted small mb-0 mt-1">Configure subscription packages, featured badges, pricing tiers, and perks per coaching.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('the-movement') }}#pricing" target="_blank" class="btn btn-outline-light btn-sm px-3 rounded-3 d-flex align-items-center gap-2">
                <i class="fas fa-external-link-alt text-warning"></i>
                <span>View Public Page</span>
            </a>
            <a href="{{ route('commando-plans.create') }}" class="btn btn-warning btn-sm px-3 fw-bold rounded-3 text-dark d-flex align-items-center gap-2 shadow-sm">
                <i class="fas fa-plus-circle"></i>
                <span>Add New Plan</span>
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
        <div class="col-6 col-md-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span class="fw-semibold">Total Plans</span>
                    <i class="fas fa-layer-group text-primary"></i>
                </div>
                <div class="h3 fw-bold text-white mt-2 mb-0">{{ number_format($stats['total']) }}</div>
                <div class="text-xs text-muted mt-1">Configured tiers</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card p-3 border-success border-opacity-25">
                <div class="d-flex justify-content-between align-items-center text-success small">
                    <span class="fw-semibold">Active &amp; Visible</span>
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="h3 fw-bold text-success mt-2 mb-0">{{ number_format($stats['active']) }}</div>
                <div class="text-xs text-muted mt-1">Live on website</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card p-3 border-warning border-opacity-25">
                <div class="d-flex justify-content-between align-items-center text-warning small">
                    <span class="fw-semibold">Featured Plans</span>
                    <i class="fas fa-star"></i>
                </div>
                <div class="h3 fw-bold text-warning mt-2 mb-0">{{ number_format($stats['featured']) }}</div>
                <div class="text-xs text-muted mt-1">Highlighted cards</div>
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
    </div>

    <!-- Search & Filter Bar -->
    <div class="card bg-dark border-secondary mb-4 shadow-sm">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('commando-plans.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-secondary border-secondary text-white"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-dark border-secondary text-white" placeholder="Search by plan title, price, tag...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="coaching_id" class="form-select form-select-sm bg-dark border-secondary text-white">
                        <option value="">All Coachings</option>
                        @foreach($coachings as $c)
                            <option value="{{ $c->id }}" {{ (string)request('coaching_id') === (string)$c->id ? 'selected' : '' }}>{{ $c->coaching_name }}</option>
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
                <div class="col-6 col-md-2">
                    <select name="is_featured" class="form-select form-select-sm bg-dark border-secondary text-white">
                        <option value="">All Types</option>
                        <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>Featured Only</option>
                        <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>Standard Only</option>
                    </select>
                </div>
                <div class="col-6 col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold">Filter</button>
                    @if(request()->hasAny(['search', 'status', 'coaching_id', 'is_featured']))
                        <a href="{{ route('commando-plans.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Plans Table -->
    <div class="card bg-dark border-secondary shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle admin-index-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 170px;">Coaching</th>
                        <th style="min-width: 220px;">Plan Title &amp; Badge</th>
                        <th style="width: 150px;">Price / Billing</th>
                        <th style="min-width: 220px;">Features &amp; Theme</th>
                        <th style="width: 110px; text-align: center;">Status</th>
                        <th style="width: 110px; text-align: end;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $plan)
                        <tr>
                            <td>
                                <span class="text-white-50 fw-bold font-monospace" style="font-size: 13px;">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <div class="coaching-pill">
                                    <i class="fas fa-graduation-cap text-warning"></i>
                                    <span>{{ $plan->coaching->coaching_name ?? $plan->coaching_name ?? 'The Movement' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="fw-bold text-white fs-6">{{ $plan->title }}</div>
                                    @if($plan->is_featured)
                                        <span class="badge bg-warning text-dark px-2 py-0 fw-bold" style="font-size: 10px;">
                                            <i class="fas fa-star me-1"></i>{{ $plan->featured_badge ?: 'POPULAR' }}
                                        </span>
                                    @endif
                                </div>
                                @if($plan->tag)
                                    <div class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 mt-1" style="font-size: 10px;">
                                        {{ $plan->tag }}
                                    </div>
                                @endif
                                @if($plan->subtitle)
                                    <div class="text-xs text-muted mt-1 text-truncate" style="max-width: 240px;">{{ $plan->subtitle }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-warning fs-5">{{ $plan->price }}</div>
                                <div class="text-xs text-muted">{{ $plan->billing_period ?: '/month' }}</div>
                            </td>
                            <td>
                                @php
                                    $features = $plan->features_list;
                                @endphp
                                <div class="small text-light mb-1">
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    <strong>{{ count($features) }}</strong> perks included
                                </div>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach(array_slice($features, 0, 3) as $f)
                                        <span class="badge bg-dark border border-secondary text-muted text-xs px-2 py-0.5" style="font-size: 11px;">
                                            {{ Str::limit($f, 28) }}
                                        </span>
                                    @endforeach
                                    @if(count($features) > 3)
                                        <span class="badge bg-dark border border-secondary text-secondary text-xs px-2 py-0.5" style="font-size: 11px;">
                                            +{{ count($features) - 3 }} more
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    <span class="badge theme-badge-{{ $plan->color_theme ?: 'slate' }} px-2 py-0" style="font-size: 10px;">
                                        Theme: {{ ucfirst($plan->color_theme ?: 'slate') }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('commando-plans.toggle-status', $plan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="border-0 bg-transparent p-0" title="Click to toggle status" style="cursor: pointer;">
                                        @if($plan->status)
                                            <span class="status-badge-active">
                                                <i class="fas fa-check-circle" style="font-size: 11px;"></i> Active
                                            </span>
                                        @else
                                            <span class="status-badge-inactive">
                                                <i class="fas fa-times-circle" style="font-size: 11px;"></i> Inactive
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('commando-plans.edit', $plan->id) }}" class="btn btn-outline-warning" title="Edit Plan">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="Delete Plan" onclick="confirmDeletePlan('{{ $plan->id }}', '{{ addslashes($plan->title) }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $plan->id }}" action="{{ route('commando-plans.destroy', $plan->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-tags fs-1 d-block mb-3 opacity-25"></i>
                                    <h5>No Membership Plans Found</h5>
                                    <p class="small mb-3">No plans match the search query or coaching filter.</p>
                                    <a href="{{ route('commando-plans.create') }}" class="btn btn-warning btn-sm fw-bold">
                                        <i class="fas fa-plus-circle me-1"></i> Add First Plan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($plans->hasPages())
            <div class="card-footer bg-dark border-secondary py-3">
                <div class="d-flex justify-content-center">
                    {{ $plans->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function confirmDeletePlan(id, title) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Plan?',
            html: `Are you sure you want to permanently remove <strong>${title}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    } else {
        if (confirm(`Are you sure you want to delete ${title}?`)) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
}
</script>
@endsection
