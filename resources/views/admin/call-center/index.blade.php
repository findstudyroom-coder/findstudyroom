@extends('admin.master')

@section('content')
<div class="content-header">
    <h1>Library Calls</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mb-4" style="background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
    <form method="GET" action="{{ route('call-center.libraries') }}" class="row g-3">
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted mb-1">Search Library</label>
            <input type="text" name="search" class="form-control" placeholder="Search by name, mobile..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted mb-1">Call Status</label>
            <select name="status" class="form-control">
                <option value="">All Statuses</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending (Not Called)</option>
                <option value="Called" {{ request('status') == 'Called' ? 'selected' : '' }}>Called</option>
                <option value="No Response" {{ request('status') == 'No Response' ? 'selected' : '' }}>No Response</option>
                <option value="Busy" {{ request('status') == 'Busy' ? 'selected' : '' }}>Busy</option>
                <option value="Call Back Later" {{ request('status') == 'Call Back Later' ? 'selected' : '' }}>Call Back Later</option>
                <option value="Interested" {{ request('status') == 'Interested' ? 'selected' : '' }}>Interested</option>
                <option value="Not Interested" {{ request('status') == 'Not Interested' ? 'selected' : '' }}>Not Interested</option>
                <option value="Converted" {{ request('status') == 'Converted' ? 'selected' : '' }}>Converted</option>
                <option value="Wrong Number" {{ request('status') == 'Wrong Number' ? 'selected' : '' }}>Wrong Number</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-danger mb-1"><i class="fas fa-fire me-1"></i> Traffic & Leads Filter</label>
            <select name="lead_filter" class="form-control border-danger" style="font-weight: 500;">
                <option value="">All Libraries (Any Traffic)</option>
                <option value="leads_or_visitors" {{ request('lead_filter') == 'leads_or_visitors' ? 'selected' : '' }}>🔥 With Leads OR Visitors (Active Traffic)</option>
                <option value="has_leads" {{ request('lead_filter') == 'has_leads' ? 'selected' : '' }}>🎯 With Student Leads / Bookings</option>
                <option value="has_visitors" {{ request('lead_filter') == 'has_visitors' ? 'selected' : '' }}>👁️ With Visitors / Page Views</option>
                <option value="recent_activity" {{ request('lead_filter') == 'recent_activity' ? 'selected' : '' }}>⚡ Active in Last 7 Days</option>
                <option value="no_traffic" {{ request('lead_filter') == 'no_traffic' ? 'selected' : '' }}>⚪ No Traffic Yet</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted mb-1">Follow-up Date</label>
            <input type="date" name="follow_up_date" class="form-control" value="{{ request('follow_up_date') }}">
        </div>
        <div class="col-md-2 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary flex-fill">Filter</button>
            <a href="{{ route('call-center.libraries') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Quick Filter Badges -->
<div class="d-flex flex-wrap gap-2 align-items-center mb-3">
    <span class="text-muted fw-bold small me-2"><i class="fas fa-filter me-1"></i> Quick Filters:</span>
    <a href="{{ route('call-center.libraries', array_merge(request()->except('lead_filter', 'page'), ['lead_filter' => 'leads_or_visitors'])) }}" 
       class="btn btn-sm {{ request('lead_filter') == 'leads_or_visitors' ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill px-3">
        🔥 With Leads / Visitors ({{ $withTrafficCount ?? 0 }})
    </a>
    <a href="{{ route('call-center.libraries', array_merge(request()->except('lead_filter', 'page'), ['lead_filter' => 'has_leads'])) }}" 
       class="btn btn-sm {{ request('lead_filter') == 'has_leads' ? 'btn-success' : 'btn-outline-success' }} rounded-pill px-3">
        🎯 Has Leads ({{ $withLeadsCount ?? 0 }})
    </a>
    <a href="{{ route('call-center.libraries', array_merge(request()->except('lead_filter', 'page'), ['lead_filter' => 'has_visitors'])) }}" 
       class="btn btn-sm {{ request('lead_filter') == 'has_visitors' ? 'btn-info text-white' : 'btn-outline-info' }} rounded-pill px-3">
        👁️ Has Visitors ({{ $withVisitorsCount ?? 0 }})
    </a>
    <a href="{{ route('call-center.libraries', array_merge(request()->except('status', 'page'), ['status' => 'Pending'])) }}" 
       class="btn btn-sm {{ request('status') == 'Pending' ? 'btn-warning text-dark' : 'btn-outline-warning text-dark' }} rounded-pill px-3">
        ⏳ Pending Calls
    </a>
    @if(request('lead_filter') || request('status') || request('search') || request('follow_up_date'))
    <a href="{{ route('call-center.libraries') }}" class="btn btn-sm btn-link text-secondary text-decoration-none">
        <i class="fas fa-times me-1"></i> Clear all filters
    </a>
    @endif
</div>

<div class="table-responsive" style="background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>Library Name</th>
                <th>Contact Person</th>
                <th>Phone Number</th>
                <th>Location</th>
                <th>Leads / Visitors</th>
                <th>Last Call Status</th>
                <th>Next Follow-up</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($libraries as $library)
            <tr>
                <td>
                    <div class="fw-bold">{{ $library->name }}</div>
                    @if(($library->total_leads ?? 0) > 0)
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle mt-1" style="font-size: 11px;">
                            🔥 High Interest
                        </span>
                    @endif
                </td>
                <td>{{ $library->contact_person_name ?? 'N/A' }}</td>
                <td>
                    <a href="tel:{{ $library->phone }}" class="text-dark fw-bold text-decoration-none">
                        {{ $library->phone }}
                    </a>
                </td>
                <td>{{ $library->district->name ?? '' }}, {{ $library->state->name ?? '' }}</td>
                <td>
                    <div class="d-flex flex-column gap-1">
                        @if(($library->total_leads ?? 0) > 0)
                            <a href="{{ route('library-visitors.index', ['library_id' => $library->id, 'tab' => 'bookings']) }}" 
                               target="_blank" 
                               class="badge bg-danger text-white text-decoration-none" 
                               title="Click to view student leads in Visitors Module">
                                🎯 {{ $library->total_leads }} {{ \Illuminate\Support\Str::plural('Lead', $library->total_leads) }}
                            </a>
                        @endif
                        @if(($library->total_views ?? 0) > 0)
                            <a href="{{ route('library-visitors.index', ['library_id' => $library->id, 'tab' => 'visitors']) }}" 
                               target="_blank" 
                               class="badge bg-primary text-white text-decoration-none" 
                               title="Click to view visitor analytics in Visitors Module">
                                👁️ {{ $library->total_views }} {{ \Illuminate\Support\Str::plural('View', $library->total_views) }}
                            </a>
                        @endif
                        @if(($library->total_leads ?? 0) == 0 && ($library->total_views ?? 0) == 0)
                            <span class="text-muted small">0 activity</span>
                        @endif
                    </div>
                </td>
                <td>
                    @if($library->latestCallLog)
                        <span class="badge bg-info text-dark">{{ $library->latestCallLog->status }}</span>
                        <br><small>{{ \Carbon\Carbon::parse($library->latestCallLog->called_at)->format('d M, Y') }}</small>
                    @else
                        <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                </td>
                <td>
                    @if($library->latestCallLog && $library->latestCallLog->next_followup_date)
                        {{ \Carbon\Carbon::parse($library->latestCallLog->next_followup_date)->format('d M, Y') }}<br>
                        <small>{{ $library->latestCallLog->next_followup_time ?? '' }}</small>
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="tel:{{ $library->phone }}" class="btn btn-sm btn-success" title="Call">📞</a>
                    
                    @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'Sub Admin']))
                    <button class="btn btn-sm btn-primary btn-add-remark" 
                            data-id="{{ $library->id }}" 
                            data-name="{{ $library->name }}"
                            title="Add Remark">
                        <i class="fas fa-edit"></i> Remark
                    </button>
                    @endif

                    @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'Sub Admin']))
                    <button class="btn btn-sm btn-info btn-view-history text-white" 
                            data-id="{{ $library->id }}" 
                            title="View History">
                        <i class="fas fa-history"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No libraries found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $libraries->links() }}
    </div>
</div>

<!-- Add Remark Modal -->
<div class="modal fade" id="addRemarkModal" tabindex="-1" aria-labelledby="addRemarkModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addRemarkForm" method="POST" action="{{ route('call-center.log') }}">
        @csrf
        <input type="hidden" name="library_id" id="modal_library_id">
        <div class="modal-header">
          <h5 class="modal-title" id="addRemarkModalLabel">Add Call Remark: <span id="modal_library_name"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-dark">
            <div class="mb-3">
                <label class="fw-bold">Status <span class="text-danger">*</span></label>
                <select name="status" id="call_status" class="form-control" required>
                    <option value="">Select Status</option>
                    <option value="Called">Called</option>
                    <option value="No Response">No Response</option>
                    <option value="Busy">Busy</option>
                    <option value="Call Back Later">Call Back Later</option>
                    <option value="Interested">Interested</option>
                    <option value="Not Interested">Not Interested</option>
                    <option value="Converted">Converted</option>
                    <option value="Wrong Number">Wrong Number</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="fw-bold">Remark <span class="text-danger">*</span></label>
                <textarea name="remark" class="form-control" rows="3" required placeholder="Enter call details..."></textarea>
            </div>

            <div id="follow_up_fields" style="display: none;">
                <div class="mb-3">
                    <label class="fw-bold">Next Follow-up Date <span class="text-danger" id="followup_req_mark">*</span></label>
                    <input type="date" name="next_followup_date" id="next_followup_date" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Next Follow-up Time</label>
                    <input type="time" name="next_followup_time" class="form-control">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Remark</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View History Modal -->
<div class="modal fade" id="viewHistoryModal" tabindex="-1" aria-labelledby="viewHistoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewHistoryModalLabel">Call History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="history_content">
        Loading...
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide follow up fields based on status
    const statusSelect = document.getElementById('call_status');
    const followUpFields = document.getElementById('follow_up_fields');
    const followupReqMark = document.getElementById('followup_req_mark');
    const followupDateInput = document.getElementById('next_followup_date');

    statusSelect.addEventListener('change', function() {
        if (['Call Back Later', 'No Response', 'Interested'].includes(this.value)) {
            followUpFields.style.display = 'block';
            if(['Call Back Later', 'No Response'].includes(this.value)) {
                followupReqMark.style.display = 'inline';
                followupDateInput.required = true;
            } else {
                followupReqMark.style.display = 'none';
                followupDateInput.required = false;
            }
        } else {
            followUpFields.style.display = 'none';
            followupDateInput.required = false;
        }
    });

    // Add Remark Button
    document.querySelectorAll('.btn-add-remark').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('modal_library_id').value = this.dataset.id;
            document.getElementById('modal_library_name').textContent = this.dataset.name;
            var myModal = new bootstrap.Modal(document.getElementById('addRemarkModal'));
            myModal.show();
        });
    });

    // View History Button
    document.querySelectorAll('.btn-view-history').forEach(button => {
        button.addEventListener('click', function() {
            const libraryId = this.dataset.id;
            const historyContent = document.getElementById('history_content');
            historyContent.innerHTML = 'Loading...';
            
            var myModal = new bootstrap.Modal(document.getElementById('viewHistoryModal'));
            myModal.show();

            fetch(`/call-center/history/${libraryId}`)
                .then(response => response.json())
                .then(data => {
                    if(data.history.length === 0) {
                        historyContent.innerHTML = '<p>No call history found for this library.</p>';
                        return;
                    }
                    
                    let html = '<table class="table table-sm"><thead><tr><th>Date</th><th>Called By</th><th>Status</th><th>Remark</th><th>Follow-up</th></tr></thead><tbody>';
                    data.history.forEach(log => {
                        html += `<tr>
                            <td>${new Date(log.called_at).toLocaleString()}</td>
                            <td>${log.admin ? log.admin.name : 'Unknown'}</td>
                            <td><span class="badge bg-secondary">${log.status}</span></td>
                            <td>${log.remark}</td>
                            <td>${log.next_followup_date ? log.next_followup_date : '-'} ${log.next_followup_time ? log.next_followup_time : ''}</td>
                        </tr>`;
                    });
                    html += '</tbody></table>';
                    historyContent.innerHTML = html;
                })
                .catch(error => {
                    historyContent.innerHTML = '<p class="text-danger">Error loading history.</p>';
                });
        });
    });
});
</script>
@endsection
