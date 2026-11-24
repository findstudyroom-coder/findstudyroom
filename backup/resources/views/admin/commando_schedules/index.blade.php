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
        white-space: normal !important; 
        word-break: break-word; 
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
    .slot-card {
        background: #0f172a;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        padding: 10px 12px;
        transition: border-color 0.2s ease;
    }
    .slot-card:hover {
        border-color: rgba(255, 255, 255, 0.2);
    }
    .day-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 0.5px;
        background: rgba(249, 115, 22, 0.15);
        color: #fb923c;
        border: 1px solid rgba(249, 115, 22, 0.35);
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
            <a class="nav-link active" href="{{ route('commando-schedules.index') }}">
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
                <span class="fs-4">🗓️</span>
                <h4 class="fw-bold mb-0 text-white">The Movement — Weekly Schedule Matrix</h4>
            </div>
            <p class="text-muted small mb-0 mt-1">Configure weekly training windows (Dawn Routine, Afternoon Cadets, Combat Prime) rendered on The Movement page.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('the-movement') }}#schedule" target="_blank" class="btn btn-outline-light btn-sm px-3 rounded-3 d-flex align-items-center gap-2">
                <i class="fas fa-external-link-alt text-warning"></i>
                <span>View Public Schedule</span>
            </a>
            <a href="{{ route('commando-schedules.create') }}" class="btn btn-warning btn-sm px-3 fw-bold rounded-3 text-dark d-flex align-items-center gap-2 shadow-sm">
                <i class="fas fa-plus-circle"></i>
                <span>Add Schedule Day</span>
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
                    <span class="fw-semibold">Total Days Scheduled</span>
                    <i class="fas fa-calendar-week text-primary"></i>
                </div>
                <div class="h3 fw-bold text-white mt-2 mb-0">{{ number_format($stats['total']) }}</div>
                <div class="text-xs text-muted mt-1">Configured timetable days</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-card p-3 border-success border-opacity-25">
                <div class="d-flex justify-content-between align-items-center text-success small">
                    <span class="fw-semibold">Active &amp; Visible</span>
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="h3 fw-bold text-success mt-2 mb-0">{{ number_format($stats['active']) }}</div>
                <div class="text-xs text-muted mt-1">Rendered on matrix table</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-card p-3 border-secondary border-opacity-25">
                <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span class="fw-semibold">Inactive / Hidden</span>
                    <i class="fas fa-eye-slash"></i>
                </div>
                <div class="h3 fw-bold text-muted mt-2 mb-0">{{ number_format($stats['inactive']) }}</div>
                <div class="text-xs text-muted mt-1">Off-season or rest days</div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card bg-dark border-secondary mb-4 shadow-sm">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('commando-schedules.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-secondary border-secondary text-white"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-dark border-secondary text-white" placeholder="Search by day, coaching, session title, sensei...">
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

                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-warning btn-sm fw-bold flex-grow-1">Filter</button>
                    @if(request()->hasAny(['search', 'status', 'coaching_name', 'coaching_id']))
                        <a href="{{ route('commando-schedules.index') }}" class="btn btn-secondary btn-sm" title="Clear Filters">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Schedules Table -->
    <div class="card bg-dark border-secondary shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle admin-index-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 75px;" class="text-center">Order</th>
                        <th style="width: 155px;">Day &amp; Coaching</th>
                        <th style="width: 250px;">Dawn Routine (06:30 - 08:30)</th>
                        <th style="width: 250px;">Afternoon Cadets (16:30 - 18:00)</th>
                        <th style="width: 250px;">Combat Prime (19:00 - 21:00)</th>
                        <th style="width: 100px;" class="text-center">Status</th>
                        <th style="width: 120px;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                        <tr>
                            <!-- Order -->
                            <td class="text-center">
                                <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary border-opacity-50 font-monospace px-2 py-1">
                                    #{{ $schedule->order_no ?? $loop->iteration }}
                                </span>
                            </td>

                            <!-- Day & Coaching -->
                            <td>
                                <div class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 mb-1.5" style="font-size: 10px;">
                                    <i class="fas fa-graduation-cap me-1"></i> {{ $schedule->coaching_name ?: 'The Movement' }}
                                </div>
                                <div>
                                    <div class="day-pill">
                                        <i class="far fa-calendar-alt opacity-75"></i> {{ $schedule->day }}
                                    </div>
                                </div>
                            </td>

                            <!-- Dawn Routine -->
                            <td>
                                @if($schedule->dawn_title)
                                    <div class="slot-card border-primary border-opacity-25">
                                        <div class="d-flex justify-content-between align-items-start gap-1">
                                            <div class="fw-bold text-white small">{{ $schedule->dawn_title }}</div>
                                            <span class="badge bg-primary bg-opacity-25 text-info border border-primary border-opacity-50 font-monospace" style="font-size: 11px;">
                                                {{ $schedule->dawn_time }}
                                            </span>
                                        </div>
                                        @if($schedule->dawn_subtitle)
                                            <div class="text-muted small mt-1" style="font-size: 11px;">
                                                <i class="fas fa-map-pin me-1 text-primary"></i> {{ $schedule->dawn_subtitle }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted small italic">— Rest / Open Mat —</span>
                                @endif
                            </td>

                            <!-- Afternoon Cadets -->
                            <td>
                                @if($schedule->cadet_title)
                                    <div class="slot-card border-info border-opacity-25">
                                        <div class="d-flex justify-content-between align-items-start gap-1">
                                            <div class="fw-bold text-white small">{{ $schedule->cadet_title }}</div>
                                            <span class="badge font-monospace" style="font-size: 11px; color: #2dd4bf; border: 1px solid rgba(45, 212, 191, 0.4); background-color: rgba(45, 212, 191, 0.15);">
                                                {{ $schedule->cadet_time }}
                                            </span>
                                        </div>
                                        @if($schedule->cadet_subtitle)
                                            <div class="text-muted small mt-1" style="font-size: 11px;">
                                                <i class="fas fa-map-pin me-1 text-info"></i> {{ $schedule->cadet_subtitle }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted small italic">— Rest / Open Mat —</span>
                                @endif
                            </td>

                            <!-- Combat Prime -->
                            <td>
                                @if($schedule->combat_title)
                                    <div class="slot-card border-danger border-opacity-25">
                                        <div class="d-flex justify-content-between align-items-start gap-1">
                                            <div class="fw-bold text-white small">{{ $schedule->combat_title }}</div>
                                            <span class="badge font-monospace" style="font-size: 11px; color: #fb7185; border: 1px solid rgba(251, 113, 133, 0.4); background-color: rgba(251, 113, 133, 0.15);">
                                                {{ $schedule->combat_time }}
                                            </span>
                                        </div>
                                        @if($schedule->combat_subtitle)
                                            <div class="text-muted small mt-1" style="font-size: 11px;">
                                                <i class="fas fa-map-pin me-1 text-danger"></i> {{ $schedule->combat_subtitle }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted small italic">— Rest / Open Mat —</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="text-center">
                                <form action="{{ route('commando-schedules.toggle-status', $schedule->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm p-0 border-0" title="Click to toggle status">
                                        @if($schedule->status)
                                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-2 py-1">Active</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-25 text-muted border border-secondary border-opacity-50 px-2 py-1">Inactive</span>
                                        @endif
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="text-center">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('commando-schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm" title="Edit Schedule">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('commando-schedules.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this schedule day ({{ $schedule->day }})?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Schedule">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="mb-2 fs-3">🗓️</div>
                                <div class="fw-semibold">No schedule days found.</div>
                                <p class="small text-muted mb-3">Add your first schedule day to render the weekly training timetable on The Movement page.</p>
                                <a href="{{ route('commando-schedules.create') }}" class="btn btn-warning btn-sm fw-bold">
                                    <i class="fas fa-plus-circle me-1"></i> Add Schedule Day
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schedules->hasPages())
            <div class="card-footer bg-dark border-secondary py-3">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
