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
            <a class="nav-link active" href="{{ route('coachings.admins.index') }}">
                <i class="fas fa-user-shield text-info me-1"></i> Coaching Admins (Logins)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('coachings.all-leads') }}">
                <i class="fas fa-chart-line text-success me-1"></i> All Coaching Leads &amp; Analytics
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
                <span class="fs-4">🔐</span>
                <h4 class="fw-bold mb-0 text-white">Coaching Institute Admin Accounts</h4>
            </div>
            <p class="text-muted small mb-0 mt-1">Manage unique logins, credentials, activation status, and access for each Coaching Institute.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('coachings.admins.create') }}" class="btn btn-warning btn-sm px-3 fw-bold rounded-3 text-dark d-flex align-items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                <span>Create Coaching Admin</span>
            </a>
            <a href="{{ route('coaching.login') }}" target="_blank" class="btn btn-outline-light btn-sm px-3 rounded-3 d-flex align-items-center gap-2">
                <i class="fas fa-external-link-alt text-teal-400"></i>
                <span>Coaching Login Page</span>
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

    <!-- 4 Summary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span>Total Coaching Admins</span>
                    <i class="fas fa-user-shield text-info"></i>
                </div>
                <div class="h3 fw-bold text-white mt-1 mb-0">{{ number_format($stats['total']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center text-success small">
                    <span>Active Accounts</span>
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="h3 fw-bold text-success mt-1 mb-0">{{ number_format($stats['active']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center text-danger small">
                    <span>Inactive / Suspended</span>
                    <i class="fas fa-ban"></i>
                </div>
                <div class="h3 fw-bold text-danger mt-1 mb-0">{{ number_format($stats['inactive']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center text-warning small">
                    <span>Registered Coachings</span>
                    <i class="fas fa-school"></i>
                </div>
                <div class="h3 fw-bold text-warning mt-1 mb-0">{{ number_format($stats['coachings']) }}</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card bg-dark border-secondary p-3 mb-4 rounded-3 shadow-sm">
        <form method="GET" action="{{ route('coachings.admins.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-secondary border-secondary text-white"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control bg-dark border-secondary text-white" placeholder="Search by admin name, email, mobile, coaching name..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="coaching_id" class="form-select form-select-sm bg-dark border-secondary text-white">
                    <option value="">All Coaching Institutes</option>
                    @foreach($coachings as $c)
                        <option value="{{ $c->id }}" {{ request('coaching_id') == $c->id ? 'selected' : '' }}>{{ $c->coaching_name }} ({{ $c->city ?? 'No city' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm bg-dark border-secondary text-white">
                    <option value="">All Statuses</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active Only</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive Only</option>
                </select>
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold" title="Filter"><i class="fas fa-filter"></i></button>
                @if(request()->anyFilled(['search', 'coaching_id', 'status']))
                    <a href="{{ route('coachings.admins.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>
    </div>

    <!-- Admins Table -->
    <div class="card bg-dark border-secondary rounded-3 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table admin-index-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 250px;">Coaching Institute</th>
                        <th style="width: 240px;">Admin Credentials</th>
                        <th style="width: 140px;">Contact Phone</th>
                        <th style="width: 130px;">Account Status</th>
                        <th style="width: 130px;">Created On</th>
                        <th class="text-end" style="width: 220px;">Main Admin Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($admin->coaching)
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $admin->coaching->logo_url }}" class="rounded-2" style="width: 36px; height: 36px; object-fit: cover; background: #fff;" alt="Logo">
                                <div>
                                    <div class="fw-bold text-white">{{ $admin->coaching->coaching_name }}</div>
                                    <small class="text-muted"><i class="fas fa-map-marker-alt me-1 text-warning"></i> {{ $admin->coaching->city ?? 'Location N/A' }}</small>
                                </div>
                            </div>
                            @else
                            <span class="text-danger small">No coaching assigned</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-info"><i class="fas fa-user-circle me-1"></i> {{ $admin->name }}</div>
                            <div class="text-light small mt-0.5"><i class="fas fa-envelope me-1 text-muted"></i> {{ $admin->email }}</div>
                        </td>
                        <td>
                            <span class="text-light font-monospace small">{{ $admin->mobile ?: 'N/A' }}</span>
                        </td>
                        <td>
                            <!-- Toggle Status Form -->
                            <form action="{{ route('coachings.admins.toggle-status', $admin->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm py-0.5 px-2 rounded-pill fw-bold border-0 {{ $admin->status ? 'btn-success text-white' : 'btn-danger text-white' }}" style="font-size: 11px;">
                                    <i class="fas {{ $admin->status ? 'fa-check' : 'fa-ban' }} me-1"></i>
                                    {{ $admin->status ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="text-muted small">{{ $admin->created_at ? $admin->created_at->format('d M, Y') : 'N/A' }}</div>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1 align-items-center">
                                <!-- Impersonate / Enter Dashboard -->
                                <a href="{{ route('coaching.dashboard', ['coaching_id' => $admin->coaching_id]) }}" target="_blank" class="btn btn-sm btn-outline-info p-1 px-2" title="Access this Institute's Panel">
                                    <i class="fas fa-sign-in-alt"></i> Panel
                                </a>

                                <!-- Reset Password Modal Button -->
                                <button type="button" class="btn btn-sm btn-outline-warning p-1 px-2" data-bs-toggle="modal" data-bs-target="#resetPassModal{{ $admin->id }}" title="Reset Password">
                                    <i class="fas fa-key"></i>
                                </button>

                                <!-- Edit -->
                                <a href="{{ route('coachings.admins.edit', $admin->id) }}" class="btn btn-sm btn-outline-light p-1 px-2" title="Edit Admin">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('coachings.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this coaching admin account?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- RESET PASSWORD MODAL -->
                    <div class="modal fade" id="resetPassModal{{ $admin->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content bg-dark border-secondary text-white">
                                <div class="modal-header border-secondary">
                                    <h5 class="modal-title fw-bold"><i class="fas fa-key text-warning me-2"></i> Reset Password for {{ $admin->name }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('coachings.admins.reset-password', $admin->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body text-start">
                                        <p class="small text-muted mb-3">Set a new login password for <strong>{{ $admin->email }}</strong> (Coaching: {{ $admin->coaching->coaching_name ?? 'N/A' }}).</p>
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">New Password *</label>
                                            <input type="password" name="new_password" class="form-control bg-dark border-secondary text-white" minlength="6" placeholder="Minimum 6 characters" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-secondary">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-warning btn-sm fw-bold px-3">Update Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-user-shield fs-1 mb-3 opacity-30 d-block"></i>
                            <h6 class="fw-bold text-white">No Coaching Admins found</h6>
                            <p class="small text-muted mb-3">Create dedicated login credentials for your registered coaching institutes.</p>
                            <a href="{{ route('coachings.admins.create') }}" class="btn btn-warning btn-sm text-dark fw-bold">
                                <i class="fas fa-plus-circle me-1"></i> Create First Coaching Admin
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($admins->hasPages())
        <div class="p-3 border-top border-secondary d-flex justify-content-between align-items-center">
            <span class="text-muted small">Showing {{ $admins->firstItem() }} to {{ $admins->lastItem() }} of {{ $admins->total() }} admins</span>
            <div>{{ $admins->links('pagination::bootstrap-5') }}</div>
        </div>
        @endif
    </div>

</div>
@endsection
