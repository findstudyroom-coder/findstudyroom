@extends('admin.master')

@section('content')


    <!-- HEADER -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white">📚 Libraries </h2>

    <div class="d-flex align-items-center gap-3">
        <!-- SEARCH FORM -->
        <form action="{{ route('libraries.index') }}" method="GET" class="m-0">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-dark border-secondary text-white-50"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Search library..." value="{{ request('search') }}">
                
                <select name="status" class="form-select bg-dark text-white border-secondary" style="max-width: 140px;">
                    <option value="">All Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                
                <select name="is_verified" class="form-select bg-dark text-white border-secondary" style="max-width: 140px;">
                    <option value="">Verification</option>
                    <option value="1" {{ request('is_verified') == '1' ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ request('is_verified') == '0' ? 'selected' : '' }}>Unverified</option>
                </select>

                <select name="state_id" id="state" class="form-select bg-dark text-white border-secondary" style="max-width: 140px;">
                    <option value="">All States</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                    @endforeach
                </select>

                <select name="city_id" id="district" class="form-select bg-dark text-white border-secondary" style="max-width: 140px;">
                    <option value="">All Districts</option>
                    @if(isset($districts))
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ request('city_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                        @endforeach
                    @endif
                </select>

                <button type="submit" class="btn btn-primary px-3"><i class="fas fa-filter"></i> Filter</button>
                
                @if(request('search') || request('status') || request('is_verified') != '' || request('state_id') || request('city_id'))
                    <a href="{{ route('libraries.index') }}" class="btn btn-danger px-3" title="Clear Filters">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- ADD BUTTON -->
        @can('library.create')
        <a href="{{ route('libraries.create') }}" class="btn btn-success text-nowrap shadow-sm">
            <i class="fas fa-plus-circle"></i> Add Library
        </a>
        @endcan
    </div> 
</div>
    <!-- CARD -->
  <div class="card table-card border-0 mt-3">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-dark table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>S.No</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>State</th>
                         <th>District</th>
                        <th>Price</th>
                        <th>Phone</th>
                        <th>Call Status</th>
                        <th>Visitors & Leads</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($libraries as $lib)
                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>
    @if(!empty($lib->images) && is_array($lib->images) && count($lib->images) > 0 && !empty($lib->images[0]))
        <img src="{{ asset('storage/app/public/'.$lib->images[0]) }}" 
             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($lib->name) }}&background=random&color=fff';"
             style="height:50px; width:50px; object-fit:cover; border-radius:6px;">
    @else
        <img src="https://ui-avatars.com/api/?name={{ urlencode($lib->name) }}&background=random&color=fff" 
             style="height:50px; width:50px; object-fit:cover; border-radius:6px;" alt="Default Image">
    @endif
</td>

                        <td class="fw-bold">
                            <a href="{{ route('library-visitors.index', ['library_id' => $lib->id]) }}" class="text-white text-decoration-none hover-primary" title="View visitors report for {{ $lib->name }}">
                                {{ $lib->name }}
                            </a>
                        </td>

                       <td>
    {{ $lib->state->name ?? '-' }}</td>
   <td> {{ $lib->district->name ?? '-' }}
</td>

                        <td class="text-success fw-semibold">₹{{ $lib->price }}</td>
                        <td class="text-success fw-semibold">
                            {{ $lib->phone }}
                            @php
                            $whatsappNum = $lib->whatsapp_no ?? $lib->phone ?? '';
                            $whatsappNum = preg_replace('/[^0-9]/', '', $whatsappNum);
                            if(strlen($whatsappNum) == 10) {
                                $whatsappNum = '91' . $whatsappNum;
                            }
                            $waMessage = "नमस्ते सर/मैडम,\n\nमैं *FindStudyRoom* टीम से बात कर रहा हूँ।\n\nहम आपके *Library का Verified Premium Profile* हमारी वेबसाइट पर बना रहे हैं, ताकि आपके आसपास के छात्र आसानी से आपकी लाइब्रेरी खोज सकें।\n\nइसके लिए हमें आपकी लाइब्रेरी की कुछ जानकारी चाहिए:\n\n• Library Name\n• Full Address / Location\n• Contact Number\n• Photos (Library, Seats, Reception आदि)\n• Fees & Available Plans\n• Timings\n• Facilities (Wi-Fi, AC, CCTV, Parking, RO Water, Power Backup आदि)\n\nआपकी जानकारी मिलने के बाद हम आपकी *Premium Library Profile* तैयार करेंगे, जिससे छात्रों को आपकी लाइब्रेरी की पूरी जानकारी मिलेगी और आपकी ऑनलाइन विज़िबिलिटी बढ़ेगी।\n\nयदि आपको कोई सहायता चाहिए, तो कृपया हमें बताइए।\nधन्यवाद!\n\n*FindStudyRoom Team*\nhttps://findstudyroom.com/";
                            $waLink = "https://wa.me/" . $whatsappNum . "?text=" . urlencode($waMessage);
                            @endphp
                            @if($whatsappNum)
                            <a href="{{ $waLink }}" target="_blank" class="text-success ms-2" title="Send WhatsApp Message">
                                <i class="fab fa-whatsapp fa-lg"></i>
                            </a>
                            @endif
                        </td>

                        <td>
                            @if($lib->latestCallLog)
                                <span class="badge bg-info text-dark">{{ $lib->latestCallLog->status }}</span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('library-visitors.index', ['library_id' => $lib->id]) }}" class="text-decoration-none" title="View visitors report for {{ $lib->name }}">
                                <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50 px-2 py-1" title="Unique IP Views: {{ $lib->views_count ?? 0 }}">
                                    <i class="fas fa-eye me-1"></i> {{ $lib->views_count ?? 0 }}
                                </span>
                                @if(($lib->bookings_count ?? 0) > 0)
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-2 py-1 ms-1" title="Interested Leads">
                                        ⭐ {{ $lib->bookings_count }}
                                    </span>
                                @endif
                            </a>
                        </td>

                        <td>
                            @if($lib->status == 'open')
                                <span class="badge bg-success">Open</span>
                            @else
                                <span class="badge bg-danger">Closed</span>
                            @endif
                        </td>

                        <td class="text-end align-middle">

                            @if($lib->phone)
                            <a href="tel:{{ preg_replace('/[^0-9]/', '', $lib->phone) }}" class="btn btn-sm btn-success me-1" title="Call">
                                <i class="fas fa-phone-alt"></i>
                            </a>
                            @endif

                            <a href="{{ route('seats.index', $lib->id) }}" class="btn btn-sm btn-info me-1" title="Seat Map">
                                🗺
                            </a>

                            @can('library.call.create')
                            <button class="btn btn-sm btn-primary me-1 btn-add-remark" 
                                    data-id="{{ $lib->id }}" 
                                    data-name="{{ $lib->name }}"
                                    title="Add Remark">
                                <i class="fas fa-headset"></i> Remark
                            </button>
                            @endcan

                            @can('library.call.history')
                            <button class="btn btn-sm btn-secondary me-1 btn-view-history text-white" 
                                    data-id="{{ $lib->id }}" 
                                    title="View History">
                                <i class="fas fa-history"></i>
                            </button>
                            @endcan

                            <a href="{{ route('members.index') }}?library_id={{ $lib->id }}" class="btn btn-sm btn-outline-light me-1" title="Members">
                                👥
                            </a>

                            @can('update', $lib)
                            <a href="{{ route('libraries.edit',$lib->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan

                            @can('delete', $lib)
                            <form action="{{ route('libraries.destroy',$lib->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this library?');" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endcan

                        </td>

                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

    </div>

</div>



<!-- Add Remark Modal -->
<div class="modal fade" id="addRemarkModal" tabindex="-1" aria-labelledby="addRemarkModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-dark">
      <form id="addRemarkForm" method="POST" action="{{ route('call-center.log') }}">
        @csrf
        <input type="hidden" name="library_id" id="modal_library_id">
        <div class="modal-header">
          <h5 class="modal-title" id="addRemarkModalLabel">Add Call Remark: <span id="modal_library_name"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-start">
            <div class="mb-3">
                <label>Status <span class="text-danger">*</span></label>
                <select name="status" id="call_status" class="form-select" required>
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
                <label>Remark <span class="text-danger">*</span></label>
                <textarea name="remark" class="form-control" rows="3" required placeholder="Enter call details..."></textarea>
            </div>

            <div id="follow_up_fields" style="display: none;">
                <div class="mb-3">
                    <label>Next Follow-up Date <span class="text-danger" id="followup_req_mark">*</span></label>
                    <input type="date" name="next_followup_date" id="next_followup_date" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Next Follow-up Time</label>
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
    <div class="modal-content text-dark">
      <div class="modal-header">
        <h5 class="modal-title" id="viewHistoryModalLabel">Call History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-start" id="history_content">
        Loading...
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stateSelect = document.getElementById('state');
    if (stateSelect) {
        stateSelect.addEventListener('change', function () {
            let stateId = this.value;
            let districtDropdown = document.getElementById('district');
            
            districtDropdown.innerHTML = '<option value="">All Districts</option>';
            
            if (stateId) {
                fetch('/get-districts/' + stateId)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(d => {
                            districtDropdown.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                        });
                    });
            }
        });
    }

    // Show/hide follow up fields based on status
    const statusSelect = document.getElementById('call_status');
    const followUpFields = document.getElementById('follow_up_fields');
    const followupReqMark = document.getElementById('followup_req_mark');
    const followupDateInput = document.getElementById('next_followup_date');

    if(statusSelect) {
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
    }

    // Add Remark Button
    document.querySelectorAll('.btn-add-remark').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('modal_library_id').value = this.dataset.id;
            document.getElementById('modal_library_name').textContent = this.dataset.name;
            var myModal = new bootstrap.Modal(document.getElementById('addRemarkModal'));
            myModal.show();
        });
    });

    // View History Button
    document.querySelectorAll('.btn-view-history').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
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
                    
                    let html = '<table class="table table-sm text-start"><thead><tr><th>Date</th><th>Called By</th><th>Status</th><th>Remark</th><th>Follow-up</th></tr></thead><tbody>';
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