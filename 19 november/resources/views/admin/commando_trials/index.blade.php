@extends('admin.master')

@section('content')
<style>
    .custom-pagination .pagination {
        gap: 6px;
    }
    .custom-pagination .page-link {
        background-color: #1f2937;
        color: #e5e7eb;
        border: 1px solid #374151;
        border-radius: 8px;
        padding: 6px 12px;
        transition: 0.3s;
    }
    .custom-pagination .page-link:hover {
        background-color: #f97316;
        color: #fff;
    }
    .custom-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #f97316, #ea580c);
        border: none;
        color: #fff;
    }
    .custom-pagination .page-item.disabled .page-link {
        background-color: #111827;
        color: #6b7280;
    }
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
    .discipline-badge {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 4px 10px;
        border-radius: 9999px;
    }
    .status-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 9999px;
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
            <a class="nav-link active" href="{{ route('commando-trials.index') }}">
                <i class="fas fa-user-check text-info me-1"></i> Trial Bookings &amp; Inquiries
            </a>
        </li>
    </ul>

    <!-- Page Header & Title -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="fs-4">📋</span>
                <h4 class="fw-bold mb-0 text-white">The Movement — Free Trial Bookings</h4>
            </div>
            <p class="text-muted small mb-0 mt-1">Manage all disciples, free trial registrations, discipline selections, and scheduling slots.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('commando-trials.export', request()->all()) }}" class="btn btn-outline-light btn-sm px-3 rounded-3 d-flex align-items-center gap-2">
                <i class="fas fa-file-csv text-warning"></i>
                <span>Export CSV</span>
            </a>
            <a href="{{ route('the-movement') }}#trial" target="_blank" class="btn btn-warning btn-sm px-3 fw-bold rounded-3 text-dark d-flex align-items-center gap-2">
                <i class="fas fa-external-link-alt"></i>
                <span>View Public Form</span>
            </a>
        </div>
    </div>

    <!-- Flash Notification -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm bg-success text-white mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- 5 KPI Summary Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span class="fw-semibold">Total Registrations</span>
                    <i class="fas fa-users text-primary"></i>
                </div>
                <div class="h3 fw-bold text-white mt-2 mb-0">{{ number_format($stats['total']) }}</div>
                <div class="text-xs text-muted mt-1">All time trials</div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card p-3 border-warning border-opacity-25">
                <div class="d-flex justify-content-between align-items-center text-warning small">
                    <span class="fw-semibold">Pending Review</span>
                    <i class="fas fa-clock"></i>
                </div>
                <div class="h3 fw-bold text-warning mt-2 mb-0">{{ number_format($stats['pending']) }}</div>
                <div class="text-xs text-muted mt-1">Awaiting confirmation</div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card p-3 border-info border-opacity-25">
                <div class="d-flex justify-content-between align-items-center text-info small">
                    <span class="fw-semibold">Confirmed</span>
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="h3 fw-bold text-info mt-2 mb-0">{{ number_format($stats['confirmed']) }}</div>
                <div class="text-xs text-muted mt-1">WhatsApp sent</div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card p-3 border-success border-opacity-25">
                <div class="d-flex justify-content-between align-items-center text-success small">
                    <span class="fw-semibold">Attended</span>
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="h3 fw-bold text-success mt-2 mb-0">{{ number_format($stats['attended']) }}</div>
                <div class="text-xs text-muted mt-1">Completed trial</div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card p-3 border-danger border-opacity-25">
                <div class="d-flex justify-content-between align-items-center text-danger small">
                    <span class="fw-semibold">Cancelled</span>
                    <i class="fas fa-ban"></i>
                </div>
                <div class="h3 fw-bold text-danger mt-2 mb-0">{{ number_format($stats['cancelled']) }}</div>
                <div class="text-xs text-muted mt-1">No show / Cancelled</div>
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="card bg-dark border-secondary mb-4 shadow-sm">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('commando-trials.index') }}" class="row g-2 align-items-center">
                <!-- Search -->
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-secondary border-secondary text-white"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-dark border-secondary text-white" placeholder="Search by name, phone, discipline...">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select form-select-sm bg-dark border-secondary text-white">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Confirmed" {{ request('status') === 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="Attended" {{ request('status') === 'Attended' ? 'selected' : '' }}>Attended</option>
                        <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Discipline Filter -->
                <div class="col-6 col-md-2">
                    <select name="discipline" class="form-select form-select-sm bg-dark border-secondary text-white">
                        <option value="">All Disciplines</option>
                        @foreach($disciplines as $disc)
                            <option value="{{ $disc }}" {{ request('discipline') === $disc ? 'selected' : '' }}>{{ $disc }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range -->
                <div class="col-6 col-md-2">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm bg-dark border-secondary text-white" placeholder="From Date">
                </div>
                <div class="col-6 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold">Filter</button>
                    @if(request()->hasAny(['search', 'status', 'discipline', 'date_from', 'date_to']))
                        <a href="{{ route('commando-trials.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear Filters"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card bg-dark border-secondary shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle admin-index-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 200px;">Student Details</th>
                        <th style="width: 180px;">Discipline</th>
                        <th style="width: 140px;">Slot / Schedule</th>
                        <th style="width: 110px;">Trial Date</th>
                        <th style="width: 130px;">Status</th>
                        <th style="width: 140px;">Quick WhatsApp</th>
                        <th style="width: 160px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandoTrials as $key => $item)
                        <tr>
                            <td>{{ $commandoTrials->firstItem() + $key }}</td>

                            <!-- Student Info -->
                            <td>
                                <div class="fw-bold text-white">{{ $item->name }}</div>
                                <div class="small text-muted font-monospace mt-0.5">
                                    <i class="fas fa-phone-alt me-1 text-primary"></i> {{ $item->phone }}
                                </div>
                            </td>

                            <!-- Discipline -->
                            <td>
                                <span class="badge bg-secondary font-monospace">
                                    {{ $item->discipline ?? 'General Trial' }}
                                </span>
                            </td>

                            <!-- Slot -->
                            <td>
                                <div class="small text-white fw-semibold">
                                    {{ $item->slot ?? 'Dawn (06:30 AM)' }}
                                </div>
                            </td>

                            <!-- Date -->
                            <td>
                                <span class="small text-white font-monospace">
                                    {{ $item->date ? $item->date->format('d M, Y') : 'Immediate' }}
                                </span>
                            </td>

                            <!-- Status Badge -->
                            <td>
                                @if($item->status === 'Confirmed')
                                    <span class="badge bg-info text-dark">Confirmed</span>
                                @elseif($item->status === 'Attended')
                                    <span class="badge bg-success">Attended</span>
                                @elseif($item->status === 'Cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>

                            <!-- WhatsApp Direct CTA -->
                            <td>
                                @php
                                    $waMsg = urlencode("Hello {$item->name}, greetings from The Movement Commando Classes! Your free trial session for {$item->discipline} ({$item->slot}) has been received. Please let us know if this time suits you.");
                                @endphp
                                <a href="https://wa.me/{{ $item->whatsapp_phone }}?text={{ $waMsg }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>Chat</span>
                                </a>
                            </td>

                            <!-- Action Dropdown & Modal Trigger -->
                            <td class="text-end">
                                <div class="dropdown d-inline">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Status
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                                        <li>
                                            <form action="{{ route('commando-trials.updateStatus', $item->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="Confirmed">
                                                <button type="submit" class="dropdown-item text-info"><i class="fas fa-check me-2"></i> Confirm Slot</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('commando-trials.updateStatus', $item->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="Attended">
                                                <button type="submit" class="dropdown-item text-success"><i class="fas fa-check-double me-2"></i> Mark Attended</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('commando-trials.updateStatus', $item->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="Pending">
                                                <button type="submit" class="dropdown-item text-warning"><i class="fas fa-clock me-2"></i> Mark Pending</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('commando-trials.updateStatus', $item->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="Cancelled">
                                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-times me-2"></i> Cancel</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>

                                <form action="{{ route('commando-trials.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this trial booking?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div class="mb-2 fs-3">🥋</div>
                                <div class="fw-semibold">No trial booking records found.</div>
                                <p class="small text-muted mb-0">When users submit the trial form on The Movement page, entries will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($commandoTrials->hasPages())
            <div class="card-footer bg-dark border-secondary py-3">
                {{ $commandoTrials->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
