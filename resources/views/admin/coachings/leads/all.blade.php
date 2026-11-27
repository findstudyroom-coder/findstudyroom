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
    .nav-tabs-dark { border-bottom: 2px solid #1f2937; }
    .nav-tabs-dark .nav-link { color: #9ca3af; border: none; padding: 10px 20px; font-weight: 600; border-radius: 8px 8px 0 0; transition: all 0.2s; }
    .nav-tabs-dark .nav-link:hover { color: #f97316; }
    .nav-tabs-dark .nav-link.active { color: #fff; background: #1f2937; border-bottom: 3px solid #f97316; }
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
            <a class="nav-link" href="{{ route('coachings.admins.index') }}">
                <i class="fas fa-user-shield text-info me-1"></i> Coaching Admins (Logins)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('coachings.all-leads') }}">
                <i class="fas fa-chart-line text-success me-1"></i> Overall Coaching Leads &amp; Analytics
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('commando-classes.index') }}">
                <i class="fas fa-fist-raised text-danger me-1"></i> Disciplines
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('commando-instructors.index') }}">
                <i class="fas fa-user-ninja text-warning me-1"></i> Instructors
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('commando-trials.index') }}">
                <i class="fas fa-user-check text-info me-1"></i> Trial Bookings
            </a>
        </li>
    </ul>

    <!-- Header & Action -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="fs-4">📈</span>
                <h4 class="fw-bold mb-0 text-white">Centralized Coaching Lead Analytics &amp; All Inquiries</h4>
            </div>
            <p class="text-muted small mb-0 mt-1">Global oversight of all prospective student inquiries across every Coaching Institute.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('coachings.all-leads.export', request()->all()) }}" class="btn btn-outline-light btn-sm px-3 rounded-3 d-flex align-items-center gap-2">
                <i class="fas fa-file-csv text-warning"></i>
                <span>Export Global CSV</span>
            </a>
        </div>
    </div>

    <!-- 6 Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span class="text-uppercase" style="font-size: 11px;">Total Leads</span>
                    <i class="fas fa-users text-primary"></i>
                </div>
                <div class="h3 fw-bold text-white mt-1 mb-0">{{ number_format($stats['total']) }}</div>
                <div class="text-muted" style="font-size: 10px;">Across all {{ $stats['coachings'] }} coachings</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="stat-card p-3 border-info border-opacity-25" style="background: rgba(2, 132, 199, 0.08);">
                <div class="d-flex justify-content-between align-items-center text-info small">
                    <span class="text-uppercase" style="font-size: 11px;">New Leads</span>
                    <i class="fas fa-bell"></i>
                </div>
                <div class="h3 fw-bold text-info mt-1 mb-0">{{ number_format($stats['new']) }}</div>
                <div class="text-info opacity-75" style="font-size: 10px;">Pending first contact</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span class="text-uppercase" style="font-size: 11px;">Contacted</span>
                    <i class="fas fa-phone-alt text-primary"></i>
                </div>
                <div class="h3 fw-bold text-white mt-1 mb-0">{{ number_format($stats['contacted']) }}</div>
                <div class="text-muted" style="font-size: 10px;">In discussion</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="stat-card p-3 border-warning border-opacity-25" style="background: rgba(245, 158, 11, 0.08);">
                <div class="d-flex justify-content-between align-items-center text-warning small">
                    <span class="text-uppercase" style="font-size: 11px;">Interested</span>
                    <i class="fas fa-star"></i>
                </div>
                <div class="h3 fw-bold text-warning mt-1 mb-0">{{ number_format($stats['interested']) }}</div>
                <div class="text-warning opacity-75" style="font-size: 10px;">Trial attending</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="stat-card p-3 border-success border-opacity-25" style="background: rgba(16, 185, 129, 0.08);">
                <div class="d-flex justify-content-between align-items-center text-success small">
                    <span class="text-uppercase" style="font-size: 11px;">Converted</span>
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="h3 fw-bold text-success mt-1 mb-0">{{ number_format($stats['converted']) }}</div>
                <div class="text-success opacity-75" style="font-size: 10px;">Enrolled students</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center text-secondary small">
                    <span class="text-uppercase" style="font-size: 11px;">Closed</span>
                    <i class="fas fa-archive"></i>
                </div>
                <div class="h3 fw-bold text-secondary mt-1 mb-0">{{ number_format($stats['closed']) }}</div>
                <div class="text-muted" style="font-size: 10px;">Lost / Archived</div>
            </div>
        </div>
    </div>

    <!-- Comparative Lead Analytics Chart -->
    <div class="card bg-dark border-secondary p-4 mb-4 rounded-3 shadow-sm">
        <h5 class="fw-bold text-white mb-1"><i class="fas fa-chart-bar text-warning me-2"></i> Comparative Inquiries by Coaching Institute</h5>
        <p class="text-muted small mb-3">Overall volume distribution of student leads generated per coaching.</p>
        <div style="height: 250px; position: relative;">
            <canvas id="coachingLeadsBarChart"></canvas>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="card bg-dark border-secondary p-3 mb-4 rounded-3 shadow-sm">
        <form method="GET" action="{{ route('coachings.all-leads') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-secondary border-secondary text-white"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control bg-dark border-secondary text-white" placeholder="Student name, phone, course, coaching..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="coaching_id" class="form-select form-select-sm bg-dark border-secondary text-white">
                    <option value="">All Coaching Institutes</option>
                    @foreach($coachings as $c)
                        <option value="{{ $c->id }}" {{ request('coaching_id') == $c->id ? 'selected' : '' }}>{{ $c->coaching_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm bg-dark border-secondary text-white">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $sk => $sv)
                        <option value="{{ $sk }}" {{ request('status') === $sk ? 'selected' : '' }}>{{ $sv }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <input type="date" name="date_from" class="form-control form-control-sm bg-dark border-secondary text-white" placeholder="From" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-1">
                <input type="date" name="date_to" class="form-control form-control-sm bg-dark border-secondary text-white" placeholder="To" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold" title="Filter"><i class="fas fa-filter me-1"></i> Filter</button>
                @if(request()->anyFilled(['search', 'coaching_id', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('coachings.all-leads') }}" class="btn btn-outline-secondary btn-sm" title="Clear"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>
    </div>

    <!-- Leads Table -->
    <div class="card bg-dark border-secondary rounded-3 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table admin-index-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#ID</th>
                        <th style="width: 220px;">Coaching Institute</th>
                        <th style="width: 220px;">Student Information</th>
                        <th style="width: 150px;">Course / Slot</th>
                        <th style="width: 120px;">Enquiry Date</th>
                        <th style="width: 130px;">Lead Status</th>
                        <th>Notes / Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                    <tr>
                        <td><span class="text-muted small">#{{ $lead->id }}</span></td>
                        <td>
                            @if($lead->coaching)
                            <div class="fw-bold text-warning">{{ $lead->coaching->coaching_name }}</div>
                            <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i> {{ $lead->coaching->city ?? 'Location N/A' }}</small>
                            @else
                            <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-white">{{ $lead->student_name }}</div>
                            <div class="text-light small mt-0.5"><i class="fas fa-phone-alt me-1 text-teal-400"></i> {{ $lead->phone }}</div>
                            @if($lead->email)
                            <div class="text-muted small" style="font-size: 11px;"><i class="fas fa-envelope me-1"></i> {{ $lead->email }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary text-white">{{ $lead->course_interested ?: 'General' }}</span>
                            @if($lead->slot)
                            <div class="text-muted small mt-1" style="font-size: 11px;">{{ $lead->slot }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="text-white small">{{ optional($lead->enquiry_date)->format('d M, Y') ?: optional($lead->created_at)->format('d M, Y') }}</div>
                            <div class="text-muted" style="font-size: 10px;">{{ optional($lead->created_at)->diffForHumans() }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $lead->status_badge_class }} py-1 px-2.5 rounded-pill" style="font-size: 11px;">
                                {{ $lead->status }}
                            </span>
                        </td>
                        <td>
                            <div class="text-light small text-truncate" style="max-width: 250px;" title="{{ $lead->notes }}">
                                {{ $lead->notes ?: 'No notes' }}
                            </div>
                            <div class="text-muted" style="font-size: 10px;">Source: {{ $lead->source }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fs-1 mb-3 opacity-30 d-block"></i>
                            <h6 class="fw-bold text-white">No Coaching Leads Found</h6>
                            <p class="small text-muted mb-0">Student leads from online forms and coaching admins will appear here.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leads->hasPages())
        <div class="p-3 border-top border-secondary d-flex justify-content-between align-items-center">
            <span class="text-muted small">Showing {{ $leads->firstItem() }} to {{ $leads->lastItem() }} of {{ $leads->total() }} leads</span>
            <div>{{ $leads->links('pagination::bootstrap-5') }}</div>
        </div>
        @endif
    </div>

</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const coachingLabels = @json($coachingChartData->pluck('coaching_name'));
    const coachingValues = @json($coachingChartData->pluck('leads_count'));

    const ctx = document.getElementById('coachingLeadsBarChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: coachingLabels,
            datasets: [{
                label: 'Total Student Inquiries',
                data: coachingValues,
                backgroundColor: 'rgba(245, 158, 11, 0.75)',
                borderColor: '#f59e0b',
                borderWidth: 1,
                borderRadius: 8,
                hoverBackgroundColor: 'rgba(245, 158, 11, 0.95)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#fff',
                    bodyColor: '#f59e0b',
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1,
                    padding: 10
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#94a3b8', font: { size: 11 }, precision: 0 }
                }
            }
        }
    });
</script>
@endsection
