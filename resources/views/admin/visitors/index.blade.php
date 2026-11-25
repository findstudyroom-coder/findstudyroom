@extends('admin.master')

@section('content')
<style>
/* Modern Glassmorphic Styles for Visitor Report */
.report-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 22px;
}

.report-title h2 {
    font-size: 26px;
    font-weight: 800;
    color: #f8fafc;
    margin: 0;
    letter-spacing: -0.5px;
}

.report-title p {
    color: #94a3b8;
    margin: 4px 0 0 0;
    font-size: 14px;
}

/* Live Pulse Indicator */
.live-indicator {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(16, 185, 129, 0.12);
    border: 1px solid rgba(16, 185, 129, 0.35);
    color: #10b981;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.pulse-dot {
    width: 9px;
    height: 9px;
    background: #10b981;
    border-radius: 50%;
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    animation: pulse 1.6s infinite;
}

@keyframes pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

/* Leaderboard Cards: Top Visited Libraries */
.leaderboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 16px;
    margin-bottom: 25px;
}

.leader-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.75), rgba(15, 23, 42, 0.9));
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(16px);
    border-radius: 18px;
    padding: 18px 20px;
    position: relative;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.leader-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.35);
}

.leader-card.rank-1 { border-color: rgba(245, 158, 11, 0.4); }
.leader-card.rank-2 { border-color: rgba(148, 163, 184, 0.4); }
.leader-card.rank-3 { border-color: rgba(180, 83, 9, 0.4); }

.rank-badge {
    position: absolute;
    top: 14px;
    right: 14px;
    font-size: 18px;
}

.leader-title {
    font-size: 16px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 3px;
    padding-right: 35px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.leader-location {
    font-size: 12px;
    color: #94a3b8;
    margin-bottom: 14px;
}

.leader-stats-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(0, 0, 0, 0.25);
    border-radius: 12px;
    padding: 10px 14px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.leader-stat-item {
    text-align: center;
}

.leader-stat-num {
    font-size: 18px;
    font-weight: 800;
    color: #38bdf8;
    line-height: 1.1;
}

.leader-stat-label {
    font-size: 10.5px;
    text-transform: uppercase;
    color: #64748b;
    font-weight: 600;
}

/* Filter Panel */
.filter-card {
    background: rgba(30, 41, 59, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(16px);
    border-radius: 18px;
    padding: 22px;
    margin-bottom: 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
}

.quick-filter-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 18px;
}

.chip-btn {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #cbd5e1;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.chip-btn:hover {
    background: rgba(56, 189, 248, 0.15);
    border-color: #38bdf8;
    color: #38bdf8;
    transform: translateY(-1px);
}

.chip-btn.active {
    background: linear-gradient(135deg, #0ea5e9, #38bdf8);
    border-color: #38bdf8;
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(14, 165, 233, 0.4);
}

.filter-input {
    background: rgba(15, 23, 42, 0.8) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #f1f5f9 !important;
    border-radius: 10px !important;
    padding: 9px 14px !important;
    font-size: 13px;
}

.filter-input:focus {
    border-color: #38bdf8 !important;
    box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2) !important;
    outline: none;
}

.filter-input option {
    background: #0f172a;
    color: #f1f5f9;
}

/* Metric Cards */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 25px;
}

.metric-card {
    background: rgba(30, 41, 59, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border-radius: 16px;
    padding: 18px 20px;
    position: relative;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.metric-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3);
}

.metric-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
}

.metric-card.card-blue::after { background: #38bdf8; }
.metric-card.card-purple::after { background: #a855f7; }
.metric-card.card-green::after { background: #10b981; }
.metric-card.card-amber::after { background: #f59e0b; }
.metric-card.card-pink::after { background: #ec4899; }
.metric-card.card-indigo::after { background: #6366f1; }

.metric-icon {
    font-size: 24px;
    margin-bottom: 8px;
}

.metric-label {
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #94a3b8;
    font-weight: 600;
}

.metric-value {
    font-size: 28px;
    font-weight: 800;
    color: #ffffff;
    margin-top: 4px;
}

.metric-sub {
    font-size: 12px;
    color: #64748b;
    margin-top: 4px;
}

/* Navigation Tabs */
.report-tabs {
    display: flex;
    gap: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 20px;
    overflow-x: auto;
    flex-wrap: nowrap;
    scrollbar-width: thin;
    -webkit-overflow-scrolling: touch;
}

.report-tab-btn {
    background: transparent;
    border: none;
    padding: 12px 20px;
    color: #94a3b8;
    font-weight: 600;
    font-size: 14px;
    border-bottom: 3px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    white-space: nowrap;
}

.report-tab-btn:hover {
    color: #38bdf8;
}

.report-tab-btn.active {
    color: #38bdf8;
    border-bottom-color: #38bdf8;
    background: rgba(56, 189, 248, 0.05);
}

/* Custom Total Visits & Unique Badges */
.badge-visits {
    background: rgba(56, 189, 248, 0.2) !important;
    color: #38bdf8 !important;
    border: 1px solid rgba(56, 189, 248, 0.5) !important;
    font-size: 14.5px !important;
    font-weight: 700 !important;
    padding: 5px 14px !important;
    border-radius: 8px !important;
    display: inline-block !important;
    letter-spacing: 0.3px;
    box-shadow: 0 0 10px rgba(56, 189, 248, 0.1);
    line-height: 1.2;
}

.badge-unique {
    background: rgba(139, 92, 246, 0.2) !important;
    color: #c084fc !important;
    border: 1px solid rgba(139, 92, 246, 0.5) !important;
    font-size: 14.5px !important;
    font-weight: 700 !important;
    padding: 5px 14px !important;
    border-radius: 8px !important;
    display: inline-block !important;
    letter-spacing: 0.3px;
    box-shadow: 0 0 10px rgba(139, 92, 246, 0.1);
    line-height: 1.2;
}

/* Glass Table Container */
.data-table-container {
    background: #0f172a !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    backdrop-filter: blur(12px);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    margin-bottom: 30px;
}

.custom-table {
    width: 100%;
    margin-bottom: 0;
    --bs-table-bg: #0f172a !important;
    --bs-table-striped-bg: #141f36 !important;
    --bs-table-hover-bg: #1e293b !important;
    --bs-table-color: #f1f5f9 !important;
    --bs-table-border-color: rgba(255, 255, 255, 0.08) !important;
    color: #f1f5f9 !important;
    background-color: #0f172a !important;
}

.custom-table > :not(caption) > * > * {
    background-color: #0f172a !important;
    box-shadow: none !important;
    color: #f1f5f9 !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
}

.custom-table thead th {
    background: #090e1a !important;
    color: #94a3b8 !important;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 15px 18px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
}

.custom-table tbody tr {
    background: #0f172a !important;
    transition: background 0.15s ease;
}

.custom-table tbody tr:hover,
.custom-table tbody tr:hover > * {
    background: #1e293b !important;
}

.custom-table tbody td {
    padding: 14px 18px;
    vertical-align: middle;
    font-size: 13.5px;
    color: #f1f5f9 !important;
}

/* Explicit High-Contrast Library Name Link */
.library-name-link {
    color: #38bdf8 !important; /* Bright Sky Blue */
    font-size: 15px !important;
    font-weight: 700 !important;
    line-height: 1.35 !important;
    display: inline-block !important;
    text-decoration: none !important;
    transition: color 0.15s ease, text-decoration 0.15s ease;
}

.library-name-link:hover {
    color: #7dd3fc !important;
    text-decoration: underline !important;
}

/* Dark pagination styling */
.pagination .page-link {
    background-color: #0f172a;
    border-color: rgba(255, 255, 255, 0.12);
    color: #94a3b8;
}
.pagination .page-item.active .page-link {
    background-color: #0ea5e9;
    border-color: #38bdf8;
    color: #fff;
}
.pagination .page-link:hover {
    background-color: #1e293b;
    color: #38bdf8;
}

/* Badges */
.badge-custom {
    padding: 5px 10px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.badge-view { background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
.badge-call { background: rgba(34, 197, 94, 0.15); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); }
.badge-book { background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); }
.badge-lead { background: rgba(168, 85, 247, 0.15); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.3); }

/* Quick Action Buttons */
.action-btn-circle {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-decoration: none;
    transition: 0.2s;
    font-size: 13px;
    border: none;
    cursor: pointer;
}

.action-btn-circle:hover {
    transform: scale(1.1);
    color: #fff;
}

.btn-call { background: #16a34a; }
.btn-wa { background: #25d366; }
.btn-view-lib { background: #0284c7; }

/* Chart Card */
.chart-card {
    background: rgba(30, 41, 59, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border-radius: 16px;
    padding: 22px;
    margin-bottom: 25px;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.chart-title {
    font-size: 16px;
    font-weight: 700;
    color: #f1f5f9;
}
</style>

<div class="container-fluid py-3">

    <!-- 1. HEADER SECTION -->
    <div class="report-header">
        <div class="report-title">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                @if(isset($selectedLibrary) && $selectedLibrary)
                    <h2>📊 {{ $selectedLibrary->name }} — Visitors & Interest Report</h2>
                @else
                    <h2>📊 Dynamic Library Visitors & Interest Report</h2>
                @endif
                <span class="live-indicator">
                    <span class="pulse-dot"></span> Real-Time Live Tracking
                </span>
            </div>
            @if(isset($selectedLibrary) && $selectedLibrary)
                <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                    <span class="badge px-3 py-1 fs-6" style="background: rgba(56, 189, 248, 0.18) !important; color: #38bdf8 !important; border: 1px solid rgba(56, 189, 248, 0.45) !important;">
                        <i class="fas fa-building me-1"></i> {{ $selectedLibrary->name }}
                    </span>
                    <span class="text-secondary small">
                        <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $selectedLibrary->district->name ?? 'N/A' }}, {{ $selectedLibrary->state->name ?? '' }}
                    </span>
                    <a href="{{ route('library-visitors.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 ms-2">
                        <i class="fas fa-times me-1"></i> Show All Libraries
                    </a>
                </div>
            @else
                <p>Kis library par kitne logo ne visit kiya hai, kis user ne click kiya, aur kitne users interested hain.</p>
            @endif
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Live Auto Refresh Toggle -->
            <button type="button" id="refreshBtn" onclick="manualRefresh()" class="btn btn-sm btn-outline-light rounded-pill px-3 py-2">
                <i class="fas fa-sync-alt" id="refreshIcon"></i> <span id="refreshBtnText">Refresh Data</span>
            </button>

            <!-- Export Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-info dropdown-toggle rounded-pill px-3 py-2" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-file-export me-1"></i> Export Report
                </button>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow">
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('library-visitors.export', array_merge(request()->all(), ['export_type' => 'summary'])) }}">
                            <i class="fas fa-table me-2 text-info"></i> Export Library-wise Summary (CSV)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('library-visitors.export', array_merge(request()->all(), ['export_type' => 'visitors'])) }}">
                            <i class="fas fa-mouse-pointer me-2 text-warning"></i> Export Visitor Clicks Log (CSV)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('library-visitors.export', array_merge(request()->all(), ['export_type' => 'interested'])) }}">
                            <i class="fas fa-user-check me-2 text-success"></i> Export Interested Users / Leads (CSV)
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- 2. TOP VISITED LIBRARIES HIGHLIGHT (LEADERBOARD) -->
    @if(!isset($selectedLibrary) && isset($topVisitedLibraries) && $topVisitedLibraries->count() > 0)
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-secondary small fw-bold text-uppercase">
                <i class="fas fa-trophy text-warning me-1"></i> Most Visited Libraries (Sabse Zyada Visitors Wali Libraries):
            </span>
        </div>
        <div class="leaderboard-grid">
            @foreach($topVisitedLibraries as $rank => $topLib)
            <div class="leader-card rank-{{ $rank + 1 }}">
                <span class="rank-badge">
                    @if($rank == 0) 🥇
                    @elseif($rank == 1) 🥈
                    @elseif($rank == 2) 🥉
                    @else 🏅
                    @endif
                </span>
                <div class="leader-title" title="{{ $topLib->name }}">{{ $topLib->name }}</div>
                <div class="leader-location">
                    <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $topLib->district->name ?? 'N/A' }}, {{ $topLib->state->name ?? '' }}
                </div>
                <div class="leader-stats-row">
                    <div class="leader-stat-item">
                        <div class="leader-stat-num">{{ number_format($topLib->total_clicks) }}</div>
                        <div class="leader-stat-label">Total Visits</div>
                    </div>
                    <div class="leader-stat-item">
                        <div class="leader-stat-num" style="color: #a855f7;">{{ number_format($topLib->unique_visitors) }}</div>
                        <div class="leader-stat-label">Unique People</div>
                    </div>
                    <div class="leader-stat-item">
                        <div class="leader-stat-num text-success">⭐ {{ $topLib->total_bookings }}</div>
                        <div class="leader-stat-label">Interested</div>
                    </div>
                </div>
                <div class="mt-3 text-end">
                    <button type="button" onclick="openLibraryModal({{ $topLib->id }}, '{{ addslashes($topLib->name) }}')" class="btn btn-sm btn-outline-info rounded-pill px-3 py-1">
                        <i class="fas fa-eye me-1"></i> View All Visitors
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- 3. FILTER & DATE SELECTION PANEL -->
    <div class="filter-card">
        <form action="{{ route('library-visitors.index') }}" method="GET" id="visitorFilterForm">
            <input type="hidden" name="tab" id="activeTabInput" value="{{ $activeTab }}">
            <input type="hidden" name="date_filter" id="dateFilterInput" value="{{ $filterType }}">

            <!-- Quick Date Chips -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                <span class="text-secondary small fw-bold text-uppercase">
                    <i class="fas fa-calendar-alt me-1 text-info"></i> Date Filter (Tarikh Ke Mutabik Filter):
                </span>
                <span class="badge bg-dark border border-secondary text-info px-3 py-1">
                    Selected Range: {{ $startDate->format('d M, Y') }} — {{ $endDate->format('d M, Y') }}
                </span>
            </div>

            <div class="quick-filter-chips">
                <a href="javascript:void(0);" onclick="applyPreset('today')" class="chip-btn {{ $filterType === 'today' ? 'active' : '' }}">
                    Today (Aaj)
                </a>
                <a href="javascript:void(0);" onclick="applyPreset('yesterday')" class="chip-btn {{ $filterType === 'yesterday' ? 'active' : '' }}">
                    Yesterday (Kal)
                </a>
                <a href="javascript:void(0);" onclick="applyPreset('this_week')" class="chip-btn {{ $filterType === 'this_week' ? 'active' : '' }}">
                    This Week (Is Hafte)
                </a>
                <a href="javascript:void(0);" onclick="applyPreset('this_month')" class="chip-btn {{ $filterType === 'this_month' ? 'active' : '' }}">
                    This Month (Is Mahine)
                </a>
                <a href="javascript:void(0);" onclick="applyPreset('last_30_days')" class="chip-btn {{ $filterType === 'last_30_days' ? 'active' : '' }}">
                    Last 30 Days (Pichle 30 Din)
                </a>
                <a href="javascript:void(0);" onclick="applyPreset('last_month')" class="chip-btn {{ $filterType === 'last_month' ? 'active' : '' }}">
                    Last Month (Pichla Mahina)
                </a>
                <a href="javascript:void(0);" onclick="applyPreset('all_time')" class="chip-btn {{ $filterType === 'all_time' ? 'active' : '' }}">
                    All Time (Sabhi)
                </a>
                <a href="javascript:void(0);" onclick="toggleCustomDates()" class="chip-btn {{ $filterType === 'custom' ? 'active' : '' }}">
                    <i class="fas fa-sliders-h"></i> Custom Date Range
                </a>
            </div>

            <!-- Detailed Filters Row -->
            <div class="row g-3 align-items-end pt-2 border-top border-secondary border-opacity-25">
                <!-- Custom Date Range -->
                <div class="col-md-3 col-sm-6 custom-date-col" style="{{ $filterType === 'custom' ? '' : 'display:none;' }}">
                    <label class="form-label text-secondary small mb-1">From Date (Shuru Tarikh)</label>
                    <input type="date" name="start_date" id="startDatePicker" value="{{ $startDate->format('Y-m-d') }}" class="form-control filter-input">
                </div>
                <div class="col-md-3 col-sm-6 custom-date-col" style="{{ $filterType === 'custom' ? '' : 'display:none;' }}">
                    <label class="form-label text-secondary small mb-1">To Date (Aakhri Tarikh)</label>
                    <input type="date" name="end_date" id="endDatePicker" value="{{ $endDate->format('Y-m-d') }}" class="form-control filter-input">
                </div>

                <!-- Library Dropdown Filter -->
                <div class="col-md-3 col-sm-6">
                    <label class="form-label text-secondary small mb-1">Filter by Specific Library</label>
                    <select name="library_id" class="form-select filter-input">
                        <option value="">All Libraries (Sabhi Libraries)</option>
                        @foreach($allLibraries as $lib)
                            <option value="{{ $lib->id }}" {{ $selectedLibraryId == $lib->id ? 'selected' : '' }}>
                                {{ $lib->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Type Filter -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label text-secondary small mb-1">Action Type</label>
                    <select name="action_type" class="form-select filter-input">
                        <option value="all" {{ $actionFilter === 'all' ? 'selected' : '' }}>All Actions (Sabhi)</option>
                        <option value="view" {{ $actionFilter === 'view' ? 'selected' : '' }}>👁️ Page Views</option>
                        <option value="call_click" {{ $actionFilter === 'call_click' ? 'selected' : '' }}>📞 Call Clicks</option>
                        <option value="book_click" {{ $actionFilter === 'book_click' ? 'selected' : '' }}>📅 Book Clicks</option>
                        <option value="booking_submitted" {{ $actionFilter === 'booking_submitted' ? 'selected' : '' }}>✅ Bookings Submitted</option>
                    </select>
                </div>

                <!-- Search Input -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label text-secondary small mb-1">Search Keyword</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Library, user, phone, IP..." class="form-control filter-input">
                </div>

                <!-- Submit / Reset Buttons -->
                <div class="col-md-2 col-sm-6 d-flex gap-2">
                    <button type="submit" class="btn btn-info rounded-3 px-3 py-2 flex-grow-1 text-white fw-bold">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('library-visitors.index') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2" title="Reset Filters">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- 4. TOP STAT CARDS -->
    <div class="metrics-grid">
        <!-- Total Visits / Clicks -->
        <div class="metric-card card-blue">
            <div class="metric-icon">🖱️</div>
            <div class="metric-label">Total Visits / Clicks</div>
            <div class="metric-value" id="stat-total-clicks">{{ number_format($totalClicks) }}</div>
            <div class="metric-sub">{{ number_format($pageViewsCount) }} page views</div>
        </div>

        <!-- Unique Visitors (Alag-Alag Log) -->
        <div class="metric-card card-purple">
            <div class="metric-icon">👥</div>
            <div class="metric-label">Unique People (Visitors)</div>
            <div class="metric-value" id="stat-unique-visitors">{{ number_format($uniqueVisitors) }}</div>
            <div class="metric-sub">Distinct individual visitors</div>
        </div>

        <!-- Today's Visits (Live) -->
        <div class="metric-card card-indigo">
            <div class="metric-icon">📅</div>
            <div class="metric-label">Today's Visits (Aaj)</div>
            <div class="metric-value text-info" id="stat-today-clicks">{{ number_format($todayClicks) }}</div>
            <div class="metric-sub">Live visits today</div>
        </div>

        <!-- Interested Users (Leads) -->
        <div class="metric-card card-green">
            <div class="metric-icon">⭐</div>
            <div class="metric-label">Interested Users (Bookings)</div>
            <div class="metric-value text-success" id="stat-total-interested">{{ number_format($totalInterested) }}</div>
            <div class="metric-sub">Form inquiries submitted</div>
        </div>

        <!-- Conversion Rate -->
        <div class="metric-card card-amber">
            <div class="metric-icon">📈</div>
            <div class="metric-label">Interest Conversion</div>
            <div class="metric-value text-warning" id="stat-conversion-rate">{{ $conversionRate }}%</div>
            <div class="metric-sub">Visitors converted to leads</div>
        </div>

        <!-- Call Inquiries -->
        <div class="metric-card card-pink">
            <div class="metric-icon">📞</div>
            <div class="metric-label">Call Button Clicks</div>
            <div class="metric-value text-pink">{{ number_format($callClicksCount) }}</div>
            <div class="metric-sub">Direct phone call clicks</div>
        </div>
    </div>

    <!-- 5. VISUAL TREND & DEVICE CHART -->
    <div class="row g-3 mb-4">
        <!-- Trend Chart -->
        <div class="col-lg-8">
            <div class="chart-card h-100">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fas fa-chart-area me-2 text-info"></i> Visitor Clicks vs Interested Users Trend Over Time
                    </div>
                    <span class="badge bg-dark text-secondary border border-secondary px-2 py-1">
                        {{ count($chartData['labels']) }} timeline points
                    </span>
                </div>
                <div style="height: 250px; position: relative;">
                    <canvas id="visitorTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Device & Action Breakdown -->
        <div class="col-lg-4">
            <div class="chart-card h-100">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fas fa-mobile-alt me-2 text-warning"></i> Device Breakdown
                    </div>
                </div>
                <div style="height: 180px; position: relative;">
                    <canvas id="deviceChart"></canvas>
                </div>
                <div class="d-flex justify-content-around text-center mt-3 pt-2 border-top border-secondary border-opacity-25 small">
                    <div>
                        <span class="text-secondary d-block">Mobile</span>
                        <span class="fw-bold text-info">{{ $deviceBreakdown['Mobile'] ?? 0 }}</span>
                    </div>
                    <div>
                        <span class="text-secondary d-block">Desktop</span>
                        <span class="fw-bold text-success">{{ $deviceBreakdown['Desktop'] ?? 0 }}</span>
                    </div>
                    <div>
                        <span class="text-secondary d-block">Tablet</span>
                        <span class="fw-bold text-warning">{{ $deviceBreakdown['Tablet'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. TABS NAVIGATION -->
    <div class="report-tabs">
        <button type="button" onclick="switchTab('summary')" class="report-tab-btn {{ $activeTab === 'summary' ? 'active' : '' }}" title="Library-wise Visitor Count">
            @if(isset($selectedLibrary) && $selectedLibrary)
                <i class="fas fa-building text-info"></i> {{ $selectedLibrary->name }} Overview
            @else
                <i class="fas fa-building text-info"></i> Library Summary ({{ $librarySummary->total() }})
            @endif
        </button>
        <button type="button" onclick="switchTab('visitors')" class="report-tab-btn {{ $activeTab === 'visitors' ? 'active' : '' }}" title="Unique Visitors Log (Alag-Alag IP Visitors)">
            <i class="fas fa-mouse-pointer text-purple" style="color: #c084fc;"></i> Visitors Log ({{ $visitorLogs->total() }})
        </button>
        <button type="button" onclick="switchTab('interested')" class="report-tab-btn {{ $activeTab === 'interested' ? 'active' : '' }}" title="Interested Users & Leads">
            <i class="fas fa-user-check text-success"></i> Interested Leads ({{ $interestedUsers->total() }})
        </button>
    </div>

    <!-- 7. TAB CONTENTS -->

    <!-- TAB 1: LIBRARY-WISE SUMMARY TABLE -->
    <div id="tab-summary" style="{{ $activeTab === 'summary' ? '' : 'display:none;' }}">
        <div class="data-table-container">
            <div class="table-responsive">
                <table class="table table-dark custom-table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Library Details</th>
                            <th>Location</th>
                            <th class="text-center">Total Visits</th>
                            <th class="text-center">Unique People</th>
                            <th class="text-center">Today's Visits</th>
                            <th class="text-center">Interested Users (Leads)</th>
                            <th class="text-center">Interest Rate</th>
                            <th>Last Visit</th>
                            <th class="text-center">Detailed Visitors</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($librarySummary as $key => $lib)
                        <tr>
                            <td class="text-secondary">{{ $librarySummary->firstItem() + $key }}</td>
                            <td>
                                <a href="{{ $lib->slug ? url('/library/' . $lib->slug) : '#' }}" target="_blank" class="library-name-link" title="Open {{ $lib->name }} on website">
                                    {{ $lib->name }}
                                </a>
                                @if($lib->is_verified)
                                    <span class="badge bg-success bg-opacity-25 text-success small py-0 px-1 border border-success border-opacity-50">Verified Partner</span>
                                @endif
                                <span class="badge bg-dark text-secondary border border-secondary border-opacity-50 small py-0 px-1">ID: #{{ $lib->id }}</span>
                            </td>
                            <td>
                                <span class="text-secondary">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                    {{ $lib->district->name ?? 'N/A' }}, {{ $lib->state->name ?? '' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge-visits" title="Total Visits: {{ number_format($lib->total_clicks) }}">
                                    {{ number_format($lib->total_clicks) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge-unique" title="Unique People: {{ number_format($lib->unique_visitors) }}">
                                    {{ number_format($lib->unique_visitors) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($lib->today_clicks > 0)
                                    <span class="badge bg-warning bg-opacity-25 text-warning fw-bold px-2 py-1">
                                        ⚡ {{ $lib->today_clicks }} today
                                    </span>
                                @else
                                    <span class="text-secondary small">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($lib->total_bookings > 0)
                                    <span class="badge bg-success bg-opacity-25 text-success fs-6 fw-bold px-3 py-1 border border-success border-opacity-50">
                                        ⭐ {{ number_format($lib->total_bookings) }} Leads
                                    </span>
                                @else
                                    <span class="text-secondary small">0 Leads</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $rate = $lib->interest_rate;
                                    $rateColor = $rate >= 20 ? 'text-success' : ($rate >= 5 ? 'text-warning' : 'text-secondary');
                                @endphp
                                <span class="fw-bold {{ $rateColor }}">{{ $rate }}%</span>
                            </td>
                            <td class="text-secondary small">
                                {{ $lib->last_visited_at ? \Carbon\Carbon::parse($lib->last_visited_at)->diffForHumans() : '-' }}
                            </td>
                            <td class="text-center">
                                <button type="button" onclick="openLibraryModal({{ $lib->id }}, '{{ addslashes($lib->name) }}')" 
                                        class="btn btn-sm btn-info text-white rounded-pill px-3 py-1 shadow-sm" title="View all visitors for this library">
                                    <i class="fas fa-users me-1"></i> Visitors Log
                                </button>
                                @if($lib->slug)
                                <a href="{{ url('/library/' . $lib->slug) }}" target="_blank" class="action-btn-circle btn-secondary ms-1" title="Open Library on Website">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-secondary">
                                <i class="fas fa-search fa-2x mb-3 text-secondary d-block"></i>
                                No libraries found matching the selected visitor date filter.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($librarySummary->hasPages())
            <div class="p-3 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                <span class="small text-secondary">
                    Showing {{ $librarySummary->firstItem() }} to {{ $librarySummary->lastItem() }} of {{ $librarySummary->total() }} libraries
                </span>
                <div>
                    {{ $librarySummary->appends(request()->all())->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- TAB 2: DETAILED VISITOR CLICKS LOG -->
    <div id="tab-visitors" style="{{ $activeTab === 'visitors' ? '' : 'display:none;' }}">
        <div class="data-table-container">
            <div class="table-responsive">
                <table class="table table-dark custom-table align-middle">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Library</th>
                            <th>Visitor Details</th>
                            <th>Action</th>
                            <th>Device & Browser</th>
                            <th>IP Address (Unique)</th>
                            <th>Referer / Source</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visitorLogs as $log)
                        <tr>
                            <td class="text-secondary small">
                                <div>{{ $log->created_at->format('d M, Y') }}</div>
                                <div class="text-info fw-bold">{{ $log->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <a href="{{ ($log->library && $log->library->slug) ? url('/library/' . $log->library->slug) : '#' }}" target="_blank" class="library-name-link" title="{{ $log->library->name ?? 'Unknown Library' }}">
                                    {{ $log->library->name ?? 'Unknown Library' }}
                                </a>
                                <span class="d-block text-secondary small">
                                    {{ $log->library->district->name ?? '' }}, {{ $log->library->state->name ?? '' }}
                                </span>
                            </td>
                            <td>
                                @if($log->user)
                                    <div>
                                        <span class="badge bg-primary bg-opacity-25 text-info border border-info border-opacity-50">
                                            <i class="fas fa-user-check me-1"></i> Registered User
                                        </span>
                                        <strong class="d-block text-light mt-1" style="color: #f8fafc !important;">{{ $log->user->name }}</strong>
                                        <span class="small text-secondary">{{ $log->user->phone ?? $log->user->email }}</span>
                                    </div>
                                @else
                                    <div>
                                        <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary border-opacity-50">
                                            <i class="fas fa-user-secret me-1"></i> Guest Visitor
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($log->action_type === 'view')
                                    <span class="badge-custom badge-view">
                                        <i class="fas fa-eye"></i> Page View
                                    </span>
                                @elseif($log->action_type === 'call_click')
                                    <span class="badge-custom badge-call">
                                        <i class="fas fa-phone-alt"></i> Call Owner
                                    </span>
                                @elseif(in_array($log->action_type, ['book_click', 'membership_click']))
                                    <span class="badge-custom badge-book">
                                        <i class="fas fa-calendar-check"></i> Book Form Opened
                                    </span>
                                @elseif($log->action_type === 'booking_submitted')
                                    <span class="badge-custom badge-lead">
                                        <i class="fas fa-check-circle"></i> Booking Sent (Lead)
                                    </span>
                                @else
                                    <span class="badge-custom badge-view">
                                        {{ ucfirst($log->action_type) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    @if(strtolower($log->device) === 'mobile')
                                        <i class="fas fa-mobile-alt text-info me-1"></i> Mobile
                                    @elseif(strtolower($log->device) === 'tablet')
                                        <i class="fas fa-tablet-alt text-warning me-1"></i> Tablet
                                    @else
                                        <i class="fas fa-desktop text-success me-1"></i> Desktop
                                    @endif
                                </div>
                                <span class="text-secondary small">{{ $log->browser ?? 'Browser' }}</span>
                            </td>
                            <td class="text-secondary small font-monospace">
                                <span class="text-white fw-bold">{{ $log->ip_address ?? '-' }}</span>
                                @if(($log->visit_frequency ?? 1) > 1)
                                    <span class="badge small ms-1" style="background: rgba(56, 189, 248, 0.18) !important; color: #38bdf8 !important; border: 1px solid rgba(56, 189, 248, 0.45) !important;" title="This IP visited {{ $log->visit_frequency }} times">
                                        {{ $log->visit_frequency }} visits
                                    </span>
                                @endif
                            </td>
                            <td class="text-secondary small" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <span title="{{ $log->referer }}">
                                    {{ $log->referer ? parse_url($log->referer, PHP_URL_PATH) ?: $log->referer : 'Direct / Unknown' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="fas fa-mouse-pointer fa-2x mb-3 text-secondary d-block"></i>
                                No visitor click logs found for this filter.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($visitorLogs->hasPages())
            <div class="p-3 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                <span class="small text-secondary">
                    Showing {{ $visitorLogs->firstItem() }} to {{ $visitorLogs->lastItem() }} of {{ $visitorLogs->total() }} unique visitors
                </span>
                <div>
                    {{ $visitorLogs->appends(request()->all())->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- TAB 3: INTERESTED USERS & LEADS -->
    <div id="tab-interested" style="{{ $activeTab === 'interested' ? '' : 'display:none;' }}">
        <div class="data-table-container">
            <div class="table-responsive">
                <table class="table table-dark custom-table align-middle">
                    <thead>
                        <tr>
                            <th>Inquiry Time</th>
                            <th>Library</th>
                            <th>Interested User Name</th>
                            <th>Phone Number</th>
                            <th>Preferred Visit Date</th>
                            <th>Days Ago</th>
                            <th class="text-center">Quick Connect</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interestedUsers as $booking)
                        <tr>
                            <td class="text-secondary small">
                                <div>{{ $booking->created_at->format('d M, Y') }}</div>
                                <div class="text-info fw-bold">{{ $booking->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <a href="{{ ($booking->library && $booking->library->slug) ? url('/library/' . $booking->library->slug) : '#' }}" target="_blank" class="library-name-link" title="{{ $booking->library->name ?? 'Unknown Library' }}">
                                    {{ $booking->library->name ?? 'Unknown Library' }}
                                </a>
                                <span class="d-block text-secondary small">
                                    {{ $booking->library->district->name ?? '' }}, {{ $booking->library->state->name ?? '' }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-light fs-6" style="color: #f8fafc !important;">{{ $booking->name }}</strong>
                                <span class="badge bg-success bg-opacity-20 text-success ms-1 small">Interested Lead</span>
                            </td>
                            <td>
                                <strong class="text-info font-monospace fs-6">{{ $booking->phone }}</strong>
                            </td>
                            <td>
                                @if($booking->date)
                                    <span class="badge bg-dark border border-secondary text-light px-2 py-1">
                                        📅 {{ date('d M, Y', strtotime($booking->date)) }}
                                    </span>
                                @else
                                    <span class="text-secondary">-</span>
                                @endif
                            </td>
                            <td class="text-secondary small">
                                {{ $booking->created_at->diffForHumans() }}
                            </td>
                            <td class="text-center">
                                <a href="tel:{{ $booking->phone }}" class="action-btn-circle btn-call me-1" title="Call User Directly">
                                    <i class="fas fa-phone-alt"></i>
                                </a>
                                <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $booking->phone) }}?text=Hello%20{{ urlencode($booking->name) }},%20regarding%20your%20inquiry%20for%20{{ urlencode($booking->library->name ?? 'Library') }}" 
                                   target="_blank" class="action-btn-circle btn-wa" title="WhatsApp Chat">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="fas fa-users-slash fa-2x mb-3 text-secondary d-block"></i>
                                No interested users / booking leads found for the selected period.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($interestedUsers->hasPages())
            <div class="p-3 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                <span class="small text-secondary">
                    Showing {{ $interestedUsers->firstItem() }} to {{ $interestedUsers->lastItem() }} of {{ $interestedUsers->total() }} leads
                </span>
                <div>
                    {{ $interestedUsers->appends(request()->all())->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>

</div>

<!-- 8. MODAL: DETAILED VISITORS FOR A SPECIFIC LIBRARY -->
<div class="modal fade" id="libraryVisitorsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content bg-dark text-white border border-secondary border-opacity-50" style="border-radius: 18px;">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <div>
                    <h5 class="modal-title fw-bold" id="modalLibName">Library Visitors Details</h5>
                    <span class="text-secondary small" id="modalLibLocation">Location</span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Summary Stats Bar -->
                <div class="row g-2 text-center mb-4 p-3 bg-secondary bg-opacity-10 rounded-3 border border-secondary border-opacity-25">
                    <div class="col-3">
                        <span class="text-secondary small d-block">Total Visits</span>
                        <strong class="fs-5 text-info" id="modalTotalViews">0</strong>
                    </div>
                    <div class="col-3">
                        <span class="text-secondary small d-block">Unique People</span>
                        <strong class="fs-5 text-purple" id="modalUniqueVisitors" style="color: #c084fc;">0</strong>
                    </div>
                    <div class="col-3">
                        <span class="text-secondary small d-block">Interested Leads</span>
                        <strong class="fs-5 text-success" id="modalTotalBookings">0</strong>
                    </div>
                    <div class="col-3">
                        <span class="text-secondary small d-block">Conversion</span>
                        <strong class="fs-5 text-warning" id="modalConversionRate">0%</strong>
                    </div>
                </div>

                <!-- Tabs within Modal: Visitors vs Leads -->
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-3 py-1 text-white bg-info" id="pills-vis-tab" data-bs-toggle="pill" data-bs-target="#pills-vis" type="button">
                            👁️ Recent Visitors (<span id="modalVisCount">0</span>)
                        </button>
                    </li>
                    <li class="nav-item ms-2" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-1 text-white bg-secondary" id="pills-lead-tab" data-bs-toggle="pill" data-bs-target="#pills-lead" type="button">
                            ⭐ Interested Leads (<span id="modalLeadCount">0</span>)
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <!-- Visitors list -->
                    <div class="tab-pane fade show active" id="pills-vis">
                        <div class="table-responsive">
                            <table class="table table-dark table-striped table-sm align-middle">
                                <thead>
                                    <tr class="text-secondary small">
                                        <th>Time</th>
                                        <th>Visitor</th>
                                        <th>Action</th>
                                        <th>Device</th>
                                        <th>Source</th>
                                    </tr>
                                </thead>
                                <tbody id="modalVisitorsTableBody">
                                    <tr><td colspan="5" class="text-center py-3 text-secondary">Loading visitors...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Leads list -->
                    <div class="tab-pane fade" id="pills-lead">
                        <div class="table-responsive">
                            <table class="table table-dark table-striped table-sm align-middle">
                                <thead>
                                    <tr class="text-secondary small">
                                        <th>Inquiry Time</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Preferred Date</th>
                                    </tr>
                                </thead>
                                <tbody id="modalLeadsTableBody">
                                    <tr><td colspan="4" class="text-center py-3 text-secondary">Loading leads...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Switch Active Tab
function switchTab(tabName) {
    document.getElementById('activeTabInput').value = tabName;
    ['summary', 'visitors', 'interested'].forEach(t => {
        const el = document.getElementById('tab-' + t);
        if (el) el.style.display = (t === tabName) ? 'block' : 'none';
    });

    document.querySelectorAll('.report-tab-btn').forEach(btn => btn.classList.remove('active'));
    event.currentTarget.classList.add('active');

    const url = new URL(window.location);
    url.searchParams.set('tab', tabName);
    window.history.replaceState({}, '', url);
}

// Quick Date Preset Handler
function applyPreset(preset) {
    document.getElementById('dateFilterInput').value = preset;
    document.querySelectorAll('.custom-date-col').forEach(el => el.style.display = 'none');
    document.getElementById('visitorFilterForm').submit();
}

function toggleCustomDates() {
    document.getElementById('dateFilterInput').value = 'custom';
    document.querySelectorAll('.custom-date-col').forEach(el => el.style.display = 'block');
    document.querySelectorAll('.chip-btn').forEach(btn => btn.classList.remove('active'));
    event.currentTarget.classList.add('active');
}

// Open Specific Library Visitors Modal (AJAX)
function openLibraryModal(libId, libName) {
    const modalEl = new bootstrap.Modal(document.getElementById('libraryVisitorsModal'));
    document.getElementById('modalLibName').innerText = libName + ' — Visitors & Leads';
    document.getElementById('modalLibLocation').innerText = 'Loading details...';
    document.getElementById('modalVisitorsTableBody').innerHTML = '<tr><td colspan="5" class="text-center py-3 text-secondary"><i class="fas fa-spinner fa-spin me-2"></i> Fetching visitor data...</td></tr>';
    document.getElementById('modalLeadsTableBody').innerHTML = '<tr><td colspan="4" class="text-center py-3 text-secondary"><i class="fas fa-spinner fa-spin me-2"></i> Fetching leads...</td></tr>';
    modalEl.show();

    fetch("{{ url('/library-visitors') }}/" + libId + "/details-ajax")
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('modalLibLocation').innerText = data.library.location;
                document.getElementById('modalTotalViews').innerText = Number(data.library.total_views).toLocaleString();
                document.getElementById('modalUniqueVisitors').innerText = Number(data.library.unique_visitors).toLocaleString();
                document.getElementById('modalTotalBookings').innerText = Number(data.library.total_bookings).toLocaleString();
                document.getElementById('modalConversionRate').innerText = data.library.conversion_rate + '%';
                document.getElementById('modalVisCount').innerText = data.visitors.length;
                document.getElementById('modalLeadCount').innerText = data.leads.length;

                // Populate Visitors Table
                let visHtml = '';
                if (data.visitors.length === 0) {
                    visHtml = '<tr><td colspan="5" class="text-center py-3 text-secondary">No visitor logs recorded for this library yet.</td></tr>';
                } else {
                    data.visitors.forEach(v => {
                        visHtml += `
                            <tr>
                                <td class="small text-secondary">${v.time}</td>
                                <td><strong class="text-white">${v.user_name}</strong><br><span class="small text-secondary">${v.user_contact}</span></td>
                                <td><span class="badge" style="background: rgba(56, 189, 248, 0.18) !important; color: #38bdf8 !important; border: 1px solid rgba(56, 189, 248, 0.45) !important;">${v.action}</span></td>
                                <td class="small">${v.device} (${v.browser})</td>
                                <td class="small text-secondary">${v.referer}</td>
                            </tr>
                        `;
                    });
                }
                document.getElementById('modalVisitorsTableBody').innerHTML = visHtml;

                // Populate Leads Table
                let leadHtml = '';
                if (data.leads.length === 0) {
                    leadHtml = '<tr><td colspan="4" class="text-center py-3 text-secondary">No booking requests submitted for this library yet.</td></tr>';
                } else {
                    data.leads.forEach(l => {
                        leadHtml += `
                            <tr>
                                <td class="small text-secondary">${l.time}</td>
                                <td><strong class="text-white">${l.name}</strong></td>
                                <td><a href="tel:${l.phone}" class="text-info text-decoration-none">📞 ${l.phone}</a></td>
                                <td><span class="badge bg-dark border border-secondary text-light">📅 ${l.visit_date}</span></td>
                            </tr>
                        `;
                    });
                }
                document.getElementById('modalLeadsTableBody').innerHTML = leadHtml;
            }
        })
        .catch(err => {
            document.getElementById('modalVisitorsTableBody').innerHTML = '<tr><td colspan="5" class="text-danger py-3 text-center">Failed to load data.</td></tr>';
        });
}

// Live Real-Time Auto-Refresh via AJAX
function fetchLiveStats() {
    fetch("{{ route('library-visitors.liveStats') }}")
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const totalClicksEl = document.getElementById('stat-total-clicks');
                const uniqueVisitorsEl = document.getElementById('stat-unique-visitors');
                const todayClicksEl = document.getElementById('stat-today-clicks');
                const totalInterestedEl = document.getElementById('stat-total-interested');

                if (totalClicksEl) totalClicksEl.innerText = Number(data.total_clicks).toLocaleString();
                if (uniqueVisitorsEl) uniqueVisitorsEl.innerText = Number(data.unique_visitors).toLocaleString();
                if (todayClicksEl) todayClicksEl.innerText = Number(data.today_clicks).toLocaleString();
                if (totalInterestedEl) totalInterestedEl.innerText = Number(data.today_interested).toLocaleString();

                const refreshBtnText = document.getElementById('refreshBtnText');
                if (refreshBtnText) {
                    refreshBtnText.innerText = 'Updated ' + data.timestamp;
                }
            }
        })
        .catch(() => {});
}

function manualRefresh() {
    const icon = document.getElementById('refreshIcon');
    if (icon) icon.classList.add('fa-spin');
    fetchLiveStats();
    setTimeout(() => {
        if (icon) icon.classList.remove('fa-spin');
    }, 800);
}

// Auto-poll live stats every 25 seconds
setInterval(fetchLiveStats, 25000);

// Render Charts with Chart.js
document.addEventListener("DOMContentLoaded", function () {
    // 1. Line Trend Chart
    const trendCtx = document.getElementById('visitorTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [
                    {
                        label: 'Library Clicks / Views',
                        data: {!! json_encode($chartData['views']) !!},
                        borderColor: '#38bdf8',
                        backgroundColor: 'rgba(56, 189, 248, 0.12)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Interested Users (Leads)',
                        data: {!! json_encode($chartData['interested']) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#94a3b8', font: { size: 12 } }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', maxRotation: 45 }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', precision: 0 }
                    }
                }
            }
        });
    }

    // 2. Device Breakdown Doughnut Chart
    const deviceCtx = document.getElementById('deviceChart');
    if (deviceCtx) {
        new Chart(deviceCtx, {
            type: 'doughnut',
            data: {
                labels: ['Mobile', 'Desktop', 'Tablet'],
                datasets: [{
                    data: [
                        {{ $deviceBreakdown['Mobile'] ?? 0 }},
                        {{ $deviceBreakdown['Desktop'] ?? 0 }},
                        {{ $deviceBreakdown['Tablet'] ?? 0 }}
                    ],
                    backgroundColor: ['#38bdf8', '#10b981', '#f59e0b'],
                    borderColor: '#1e293b',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#94a3b8', font: { size: 11 }, padding: 10 }
                    }
                },
                cutout: '70%'
            }
        });
    }
});
</script>
@endsection
