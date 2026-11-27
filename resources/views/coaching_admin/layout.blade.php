<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Coaching Admin Portal') | {{ $currentCoaching->coaching_name ?? 'Findstudyroom' }}</title>
    
    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --bg-main: #090d16;
            --bg-sidebar: #0f172a;
            --bg-card: #131d32;
            --bg-card-hover: #17243e;
            --border-color: rgba(255, 255, 255, 0.08);
            --accent-teal: #0d9488;
            --accent-teal-light: #14b8a6;
            --accent-orange: #f97316;
            --accent-amber: #f59e0b;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-primary);
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* SIDEBAR STYLES */
        .coaching-sidebar {
            width: 270px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            z-index: 1050;
            transition: all 0.3s ease;
        }

        .coaching-brand {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid rgba(13, 148, 136, 0.5);
            background: #fff;
        }

        .brand-name {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .brand-sub {
            font-size: 11px;
            color: var(--accent-teal-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            margin: 0;
        }

        .nav-section-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            font-weight: 800;
            padding: 16px 22px 6px;
        }

        .coaching-nav {
            list-style: none;
            padding: 10px 14px;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .coaching-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-secondary);
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 3px;
        }

        .coaching-nav .nav-link i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            color: #64748b;
            transition: color 0.2s ease;
        }

        .coaching-nav .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        .coaching-nav .nav-link:hover i {
            color: var(--accent-teal-light);
        }

        .coaching-nav .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, rgba(13, 148, 136, 0.25), rgba(13, 148, 136, 0.1));
            border-left: 3px solid var(--accent-teal-light);
        }

        .coaching-nav .nav-link.active i {
            color: var(--accent-teal-light);
        }

        /* MAIN CONTENT AREA */
        .coaching-main {
            margin-left: 270px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        /* TOPBAR STYLES */
        .coaching-topbar {
            height: 70px;
            background: var(--bg-sidebar);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .badge-portal {
            background: rgba(13, 148, 136, 0.15);
            color: var(--accent-teal-light);
            border: 1px solid rgba(13, 148, 136, 0.3);
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* CONTENT CARDS */
        .card-custom {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.25s ease;
        }

        .card-custom:hover {
            border-color: rgba(255, 255, 255, 0.15);
        }

        .card-custom-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* TABLES */
        .table-dark-custom {
            color: var(--text-primary);
            margin: 0;
        }

        .table-dark-custom thead th {
            background: #0f172a;
            color: #94a3b8;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-weight: 700;
            border-bottom: 1px solid var(--border-color);
            padding: 14px 16px;
            vertical-align: middle;
        }

        .table-dark-custom tbody td {
            background: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 14px 16px;
            vertical-align: middle;
            color: #cbd5e1;
            font-size: 13px;
        }

        .table-dark-custom tbody tr:hover td {
            background: rgba(255, 255, 255, 0.03);
            color: #fff;
        }

        /* BUTTONS */
        .btn-teal {
            background: linear-gradient(135deg, #0d9488, #0f766e);
            color: #fff;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.2s;
        }

        .btn-teal:hover {
            background: linear-gradient(135deg, #14b8a6, #0d9488);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
        }

        .btn-orange {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.2s;
        }

        .btn-orange:hover {
            background: linear-gradient(135deg, #fb923c, #f97316);
            color: #fff;
            transform: translateY(-1px);
        }

        /* FORM CONTROLS */
        .form-control-dark, .form-select-dark {
            background-color: #0f172a !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #f8fafc !important;
            border-radius: 10px;
            padding: 9px 14px;
            font-size: 13px;
        }

        .form-control-dark:focus, .form-select-dark:focus {
            border-color: var(--accent-teal-light) !important;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.2) !important;
            background-color: #131d32 !important;
        }

        /* STAT BADGES */
        .badge-status-new { background: #0284c7; color: #fff; }
        .badge-status-contacted { background: #3b82f6; color: #fff; }
        .badge-status-interested { background: #eab308; color: #000; font-weight: 700; }
        .badge-status-converted { background: #10b981; color: #fff; font-weight: 700; }
        .badge-status-closed { background: #64748b; color: #fff; }

        @media (max-width: 991px) {
            .coaching-sidebar {
                transform: translateX(-100%);
            }
            .coaching-sidebar.show {
                transform: translateX(0);
            }
            .coaching-main {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="coaching-sidebar" id="coachingSidebar">
        <!-- Brand Header -->
        <div class="coaching-brand">
            <img src="{{ $currentCoaching->logo_url ?? 'https://ui-avatars.com/api/?name=Coaching&background=0d9488&color=fff' }}" alt="Logo" class="brand-logo">
            <div class="overflow-hidden">
                <h5 class="brand-name text-truncate" title="{{ $currentCoaching->coaching_name ?? 'Coaching Institute' }}">
                    {{ $currentCoaching->coaching_name ?? 'Coaching Admin' }}
                </h5>
                <p class="brand-sub">Institute Portal</p>
            </div>
        </div>

        @if(!empty($isSuperAdminPreview))
        <div class="px-3 pt-2">
            <div class="alert alert-warning py-1.5 px-2.5 mb-0 text-xs rounded-2 d-flex align-items-center justify-content-between" style="font-size: 11px;">
                <span><i class="fas fa-eye me-1"></i> Super Admin Mode</span>
                <a href="{{ route('dashboard') }}" class="btn btn-dark btn-sm py-0 px-1.5" style="font-size: 10px;">Back</a>
            </div>
        </div>
        @endif

        <!-- Navigation Links -->
        <ul class="coaching-nav">
            <li class="nav-section-title">CORE OVERVIEW</li>
            <li>
                <a href="{{ route('coaching.dashboard') }}" class="nav-link {{ request()->routeIs('coaching.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard &amp; Stats</span>
                </a>
            </li>

            <li class="nav-section-title">CRM &amp; PROSPECTS</li>
            <li>
                <a href="{{ route('coaching.leads.index') }}" class="nav-link {{ request()->routeIs('coaching.leads.*') ? 'active' : '' }}">
                    <i class="fas fa-user-friends"></i>
                    <span>Lead Management</span>
                    @php
                        $leadCount = \App\Models\CoachingLead::forCoaching($currentCoaching->id)->where('status', 'New')->count();
                    @endphp
                    @if($leadCount > 0)
                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 10px;">{{ $leadCount }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-section-title">INSTITUTE PROFILE</li>
            <li>
                <a href="{{ route('coaching.profile') }}" class="nav-link {{ request()->routeIs('coaching.profile*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>Profile Management</span>
                </a>
            </li>

            <li class="nav-section-title">CURRICULUM &amp; MEDIA</li>
            <li>
                <a href="{{ route('coaching.classes.index') }}" class="nav-link {{ request()->routeIs('coaching.classes.*') ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Courses &amp; Disciplines</span>
                </a>
            </li>
            <li>
                <a href="{{ route('coaching.instructors.index') }}" class="nav-link {{ request()->routeIs('coaching.instructors.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Master Instructors</span>
                </a>
            </li>
            <li>
                <a href="{{ route('coaching.schedules.index') }}" class="nav-link {{ request()->routeIs('coaching.schedules.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Batch Schedules</span>
                </a>
            </li>
            <li>
                <a href="{{ route('coaching.plans.index') }}" class="nav-link {{ request()->routeIs('coaching.plans.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Fee &amp; Member Plans</span>
                </a>
            </li>
            <li>
                <a href="{{ route('coaching.gallery.index') }}" class="nav-link {{ request()->routeIs('coaching.gallery.*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i>
                    <span>Studio Gallery</span>
                </a>
            </li>

            <li class="nav-section-title">PUBLIC PRESENCE</li>
            <li>
                <a href="{{ route('coaching.show', $currentCoaching->slug ?? $currentCoaching->id) }}" target="_blank" class="nav-link">
                    <i class="fas fa-external-link-alt text-warning"></i>
                    <span>View Public Page</span>
                </a>
            </li>
        </ul>

        <!-- Bottom User Footer -->
        <div class="p-3 border-top border-white/10 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
                <div class="w-8 h-8 rounded-circle bg-teal-500/20 text-teal-400 d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px;">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-white text-xs fw-bold text-truncate" style="font-size: 12px;">{{ auth()->user()->name }}</div>
                    <div class="text-muted text-xs text-truncate" style="font-size: 11px;">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <a href="{{ route('coaching.logout') }}" class="btn btn-outline-danger btn-sm p-1.5 rounded-2" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="coaching-main">
        <!-- TOPBAR -->
        <header class="coaching-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-light btn-sm d-lg-none" onclick="document.getElementById('coachingSidebar').classList.toggle('show')">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="badge-portal">
                    <span class="w-2 h-2 rounded-circle bg-success d-inline-block animate-pulse"></span>
                    <span>{{ $currentCoaching->coaching_name }}</span>
                    <span class="text-muted opacity-60">|</span>
                    <span class="text-white">{{ $currentCoaching->city ?? 'Institute' }}</span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('coaching.show', $currentCoaching->slug ?? $currentCoaching->id) }}" target="_blank" class="btn btn-sm btn-outline-light d-none d-sm-flex align-items-center gap-1.5 rounded-pill px-3 py-1.5" style="font-size: 12px;">
                    <i class="fas fa-eye text-warning"></i>
                    <span>Live Public Profile</span>
                </a>

                <div class="dropdown">
                    <button class="btn btn-dark btn-sm d-flex align-items-center gap-2 dropdown-toggle py-1.5 px-3 rounded-pill" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle text-teal-400"></i>
                        <span class="d-none d-md-inline text-white small fw-bold">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-lg border-secondary">
                        <li><h6 class="dropdown-header text-uppercase text-muted" style="font-size: 10px;">{{ $currentCoaching->coaching_name }}</h6></li>
                        <li><a class="dropdown-item small" href="{{ route('coaching.profile') }}"><i class="fas fa-edit me-2"></i> Edit Profile</a></li>
                        <li><a class="dropdown-item small" href="{{ route('coaching.leads.index') }}"><i class="fas fa-users me-2"></i> Leads Management</a></li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li><a class="dropdown-item small text-danger" href="{{ route('coaching.logout') }}"><i class="fas fa-sign-out-alt me-2"></i> Log Out</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- FLASH MESSAGES -->
        <div class="container-fluid px-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm bg-success text-white py-2.5 px-3 mb-3 d-flex align-items-center" role="alert">
                    <i class="fas fa-check-circle me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm bg-danger text-white py-2.5 px-3 mb-3 d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- MAIN BODY CONTENT -->
        <main class="container-fluid px-4 py-3 flex-grow-1">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="py-3 px-4 text-center text-muted border-top border-white/5 small" style="font-size: 12px;">
            &copy; {{ date('Y') }} {{ $currentCoaching->coaching_name }} &bull; Powered by <strong class="text-white">Findstudyroom Coaching Management Engine</strong>
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
