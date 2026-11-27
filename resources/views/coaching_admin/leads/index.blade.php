@extends('coaching_admin.layout')

@section('title', 'Leads Management')

@section('content')
<div class="pb-4">

    <!-- Top Action Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fas fa-user-friends text-teal-400 me-2"></i> Prospective Student Leads</h4>
            <p class="text-secondary small mb-0">Manage incoming inquiries, update student interaction statuses, and view conversion history for {{ $coaching->coaching_name }}.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('coaching.leads.export', request()->all()) }}" class="btn btn-outline-light btn-sm d-flex align-items-center gap-2">
                <i class="fas fa-file-csv text-warning"></i>
                <span>Export CSV</span>
            </a>
            <button class="btn btn-teal btn-sm d-flex align-items-center gap-2 px-3" data-bs-toggle="modal" data-bs-target="#newLeadModal">
                <i class="fas fa-plus"></i>
                <span>Add Offline Lead</span>
            </button>
        </div>
    </div>

    <!-- 5 KPI Lead Status Counters -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ route('coaching.leads.index') }}" class="card-custom p-3 text-decoration-none d-block {{ !request('status') ? 'border-primary' : '' }}">
                <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span class="fw-bold text-uppercase" style="font-size: 11px;">Total Leads</span>
                    <i class="fas fa-users text-primary"></i>
                </div>
                <div class="h3 fw-bold text-white mt-1 mb-0">{{ number_format($stats['total']) }}</div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ route('coaching.leads.index', ['status' => 'New']) }}" class="card-custom p-3 text-decoration-none d-block {{ request('status') === 'New' ? 'border-info' : '' }}" style="background: rgba(2, 132, 199, 0.08);">
                <div class="d-flex justify-content-between align-items-center text-info small">
                    <span class="fw-bold text-uppercase" style="font-size: 11px;">New</span>
                    <span class="badge bg-info text-dark rounded-pill" style="font-size: 10px;">Unread</span>
                </div>
                <div class="h3 fw-bold text-info mt-1 mb-0">{{ number_format($stats['new']) }}</div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ route('coaching.leads.index', ['status' => 'Contacted']) }}" class="card-custom p-3 text-decoration-none d-block {{ request('status') === 'Contacted' ? 'border-primary' : '' }}">
                <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span class="fw-bold text-uppercase" style="font-size: 11px;">Contacted</span>
                    <i class="fas fa-phone-alt text-primary"></i>
                </div>
                <div class="h3 fw-bold text-white mt-1 mb-0">{{ number_format($stats['contacted']) }}</div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ route('coaching.leads.index', ['status' => 'Interested']) }}" class="card-custom p-3 text-decoration-none d-block {{ request('status') === 'Interested' ? 'border-warning' : '' }}" style="background: rgba(245, 158, 11, 0.08);">
                <div class="d-flex justify-content-between align-items-center text-warning small">
                    <span class="fw-bold text-uppercase" style="font-size: 11px;">Interested</span>
                    <i class="fas fa-star text-warning"></i>
                </div>
                <div class="h3 fw-bold text-warning mt-1 mb-0">{{ number_format($stats['interested']) }}</div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ route('coaching.leads.index', ['status' => 'Converted']) }}" class="card-custom p-3 text-decoration-none d-block {{ request('status') === 'Converted' ? 'border-success' : '' }}" style="background: rgba(16, 185, 129, 0.08);">
                <div class="d-flex justify-content-between align-items-center text-success small">
                    <span class="fw-bold text-uppercase" style="font-size: 11px;">Converted</span>
                    <i class="fas fa-check-circle text-success"></i>
                </div>
                <div class="h3 fw-bold text-success mt-1 mb-0">{{ number_format($stats['converted']) }}</div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ route('coaching.leads.index', ['status' => 'Closed']) }}" class="card-custom p-3 text-decoration-none d-block {{ request('status') === 'Closed' ? 'border-secondary' : '' }}">
                <div class="d-flex justify-content-between align-items-center text-secondary small">
                    <span class="fw-bold text-uppercase" style="font-size: 11px;">Closed</span>
                    <i class="fas fa-archive"></i>
                </div>
                <div class="h3 fw-bold text-secondary mt-1 mb-0">{{ number_format($stats['closed']) }}</div>
            </a>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="card-custom p-3 mb-4">
        <form method="GET" action="{{ route('coaching.leads.index') }}" class="row g-2 align-items-center">
            <!-- Search -->
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control form-control-dark" placeholder="Name, phone, email, discipline..." value="{{ request('search') }}">
                </div>
            </div>

            <!-- Status Filter -->
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm form-select-dark">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $k => $v)
                        <option value="{{ $k }}" {{ request('status') === $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Course Filter -->
            <div class="col-md-2">
                <select name="course" class="form-select form-select-sm form-select-dark">
                    <option value="">All Courses</option>
                    @foreach($courses as $c)
                        <option value="{{ $c }}" {{ request('course') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control form-control-sm form-control-dark" placeholder="From Date" value="{{ request('date_from') }}">
            </div>

            <!-- Date To -->
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control form-control-sm form-control-dark" placeholder="To Date" value="{{ request('date_to') }}">
            </div>

            <!-- Actions -->
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-teal btn-sm w-100" title="Apply Filter">
                    <i class="fas fa-filter"></i>
                </button>
                @if(request()->anyFilled(['search', 'status', 'course', 'date_from', 'date_to']))
                <a href="{{ route('coaching.leads.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear Filters">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Leads Table Card -->
    <div class="card-custom overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark-custom">
                <thead>
                    <tr>
                        <th style="width: 60px;">#ID</th>
                        <th>Student Name &amp; Contact</th>
                        <th>Course / Discipline</th>
                        <th>Slot / Batch</th>
                        <th>Enquiry Date</th>
                        <th style="width: 160px;">Lead Status</th>
                        <th>Source &amp; Notes</th>
                        <th class="text-end" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                    <tr>
                        <td>
                            <span class="text-secondary font-monospace" style="font-size: 11px;">#{{ $lead->id }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-white">{{ $lead->student_name }}</div>
                            <div class="text-secondary small mt-0.5" style="font-size: 11px;">
                                <i class="fas fa-phone-alt me-1 text-teal-400"></i> {{ $lead->phone }}
                                @if($lead->email)
                                    &bull; <i class="fas fa-envelope ms-1 me-1 text-muted"></i> {{ $lead->email }}
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-dark border border-secondary text-white py-1 px-2" style="font-size: 11px;">
                                {{ $lead->course_interested ?: 'General Enquiry' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-secondary small">{{ $lead->slot ?: 'Standard' }}</span>
                        </td>
                        <td>
                            <div class="text-white" style="font-size: 12px;">{{ optional($lead->enquiry_date)->format('d M, Y') ?: optional($lead->created_at)->format('d M, Y') }}</div>
                            <div class="text-muted" style="font-size: 10px;">{{ optional($lead->created_at)->diffForHumans() }}</div>
                        </td>
                        <td>
                            <!-- Interactive Status Select -->
                            <form action="{{ route('coaching.leads.updateStatus', $lead->id) }}" method="POST" class="d-inline">
                                @csrf
                                <select name="status" class="form-select form-select-sm form-select-dark py-1 px-2" style="font-size: 11px;" onchange="this.form.submit()">
                                    <option value="New" {{ $lead->status === 'New' ? 'selected' : '' }}>🔵 New</option>
                                    <option value="Contacted" {{ $lead->status === 'Contacted' ? 'selected' : '' }}>📞 Contacted</option>
                                    <option value="Interested" {{ $lead->status === 'Interested' ? 'selected' : '' }}>⭐ Interested</option>
                                    <option value="Converted" {{ $lead->status === 'Converted' ? 'selected' : '' }}>✅ Converted</option>
                                    <option value="Closed" {{ $lead->status === 'Closed' ? 'selected' : '' }}>❌ Closed</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <div class="text-teal-400 fw-bold" style="font-size: 10px; text-transform: uppercase;">{{ $lead->source }}</div>
                            <div class="text-secondary text-truncate" style="font-size: 11px; max-width: 170px;" title="{{ $lead->notes }}">
                                {{ $lead->notes ?: 'No remarks' }}
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1.5 align-items-center">
                                <!-- WhatsApp button -->
                                <a href="https://wa.me/{{ $lead->whatsapp_phone }}?text={{ urlencode('Hello ' . $lead->student_name . ', thank you for your interest in ' . $coaching->coaching_name . '! We would be glad to help you join our classes. When can we connect?') }}" target="_blank" class="btn btn-sm btn-outline-success p-1 px-2" title="WhatsApp Message">
                                    <i class="fab fa-whatsapp"></i>
                                </a>

                                <!-- Call button -->
                                <a href="tel:{{ $lead->phone }}" class="btn btn-sm btn-outline-info p-1 px-2" title="Direct Phone Call">
                                    <i class="fas fa-phone-alt"></i>
                                </a>

                                <!-- Edit / View details modal -->
                                <button type="button" class="btn btn-sm btn-outline-light p-1 px-2" data-bs-toggle="modal" data-bs-target="#editLeadModal{{ $lead->id }}" title="Edit Details &amp; Notes">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Delete -->
                                <form action="{{ route('coaching.leads.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- EDIT LEAD MODAL -->
                    <div class="modal fade" id="editLeadModal{{ $lead->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border border-secondary text-white" style="background: #131d32;">
                                <div class="modal-header border-bottom border-white/10">
                                    <h5 class="modal-title fw-bold"><i class="fas fa-user-edit text-teal-400 me-2"></i> Lead Details &bull; #{{ $lead->id }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('coaching.leads.update', $lead->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <label class="form-label small text-secondary">Student Name</label>
                                            <input type="text" name="student_name" class="form-control form-control-dark" value="{{ $lead->student_name }}" required>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small text-secondary">Phone Number</label>
                                                <input type="text" name="phone" class="form-control form-control-dark" value="{{ $lead->phone }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small text-secondary">Email</label>
                                                <input type="email" name="email" class="form-control form-control-dark" value="{{ $lead->email }}">
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small text-secondary">Course / Discipline</label>
                                                <input type="text" name="course_interested" class="form-control form-control-dark" value="{{ $lead->course_interested }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small text-secondary">Slot / Batch</label>
                                                <input type="text" name="slot" class="form-control form-control-dark" value="{{ $lead->slot }}">
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small text-secondary">Enquiry Date</label>
                                                <input type="date" name="enquiry_date" class="form-control form-control-dark" value="{{ optional($lead->enquiry_date)->format('Y-m-d') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small text-secondary">Current Status</label>
                                                <select name="status" class="form-select form-select-dark">
                                                    @foreach($statuses as $stKey => $stVal)
                                                        <option value="{{ $stKey }}" {{ $lead->status === $stKey ? 'selected' : '' }}>{{ $stVal }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label small text-secondary">Interaction Notes &amp; History</label>
                                            <textarea name="notes" class="form-control form-control-dark" rows="4">{{ $lead->notes }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top border-white/10">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-teal btn-sm px-3">Update Lead</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-search fs-1 mb-3 opacity-30 d-block"></i>
                            <h6 class="fw-bold text-white">No student leads matched your filter</h6>
                            <p class="small text-secondary mb-3">Clear filters or log an inquiry from prospective students.</p>
                            <button class="btn btn-teal btn-sm" data-bs-toggle="modal" data-bs-target="#newLeadModal">
                                <i class="fas fa-plus me-1"></i> Add Offline Lead
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leads->hasPages())
        <div class="p-3 border-top border-white/10 d-flex justify-content-between align-items-center">
            <span class="text-muted small" style="font-size: 12px;">Showing {{ $leads->firstItem() }} to {{ $leads->lastItem() }} of {{ $leads->total() }} leads</span>
            <div>{{ $leads->links('pagination::bootstrap-5') }}</div>
        </div>
        @endif
    </div>

</div>

<!-- CREATE OFFLINE LEAD MODAL -->
<div class="modal fade" id="newLeadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border border-secondary text-white" style="background: #131d32;">
            <div class="modal-header border-bottom border-white/10">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus text-teal-400 me-2"></i> Log New Student Lead</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('coaching.leads.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Student Full Name *</label>
                        <input type="text" name="student_name" class="form-control form-control-dark" placeholder="e.g. Amit Verma" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Phone Number *</label>
                            <input type="text" name="phone" class="form-control form-control-dark" placeholder="10-digit number" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Email Address</label>
                            <input type="email" name="email" class="form-control form-control-dark" placeholder="student@example.com">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Course Interested</label>
                            <input type="text" name="course_interested" class="form-control form-control-dark" placeholder="e.g. Karate / Judo / Yoga">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Preferred Slot / Batch</label>
                            <input type="text" name="slot" class="form-control form-control-dark" placeholder="e.g. Dawn (06:30 AM)">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Enquiry Date</label>
                            <input type="date" name="enquiry_date" class="form-control form-control-dark" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Status</label>
                            <select name="status" class="form-select form-select-dark">
                                <option value="New" selected>New</option>
                                <option value="Contacted">Contacted</option>
                                <option value="Interested">Interested</option>
                                <option value="Converted">Converted</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Inquiry Source</label>
                        <select name="source" class="form-select form-select-dark">
                            <option value="Walk-in" selected>Walk-in / In-person Visit</option>
                            <option value="Direct Call">Direct Phone Call</option>
                            <option value="WhatsApp Inquiry">WhatsApp Inquiry</option>
                            <option value="Social Media">Instagram / Facebook</option>
                            <option value="Friend Referral">Friend Referral</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small text-secondary">Initial Notes</label>
                        <textarea name="notes" class="form-control form-control-dark" rows="3" placeholder="Notes, questions asked, goals..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-white/10">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-teal btn-sm px-3">Save Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
