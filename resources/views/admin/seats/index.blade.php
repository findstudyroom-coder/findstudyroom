@extends('admin.master')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1" style="color:#6366f1;">🗺 Seat Map — {{ $library->name }}</h4>
            <p class="text-muted small mb-0">📍 {{ $library->location }}</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('members.create') }}?library_id={{ $library->id }}" class="btn btn-primary btn-sm">➕ Add Member</a>
            <a href="{{ route('members.index') }}?library_id={{ $library->id }}" class="btn btn-outline-info btn-sm">👥 View Members</a>
            <a href="{{ route('libraries.index') }}" class="btn btn-outline-secondary btn-sm">← Libraries</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Stats Cards --}}
        <div class="col-md-4">
            <div class="card border-0 rounded-4 text-white shadow"
                 style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <div class="card-body p-4 text-center">
                    <div style="font-size:2.5rem;font-weight:900;">{{ $totalSeats }}</div>
                    <div class="small opacity-75">Total Seats</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 rounded-4 text-white shadow"
                 style="background:linear-gradient(135deg,#10b981,#059669);">
                <div class="card-body p-4 text-center">
                    <div style="font-size:2.5rem;font-weight:900;">{{ $availableSeats }}</div>
                    <div class="small opacity-75">Available</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 rounded-4 text-white shadow"
                 style="background:linear-gradient(135deg,#ef4444,#dc2626);">
                <div class="card-body p-4 text-center">
                    <div style="font-size:2.5rem;font-weight:900;">{{ $occupiedSeats }}</div>
                    <div class="small opacity-75">Occupied</div>
                </div>
            </div>
        </div>

        {{-- Generate Seats Form --}}
        <div class="col-12">
            <div class="card shadow border-0 rounded-4 bg-dark text-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">⚙️ Generate / Configure Seats</h6>
                    <form action="{{ route('seats.generate', $library->id) }}" method="POST"
                          class="row g-2 align-items-end">
                        @csrf
                        <div class="col-md-3">
                            <label class="form-label small">Total Seats</label>
                            <input type="number" name="total_seats" class="form-control bg-dark text-white border-secondary"
                                   value="{{ $totalSeats ?: 100 }}" min="1" max="200" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Default Seat Type</label>
                            <select name="seat_type" class="form-select bg-dark text-white border-secondary">
                                <option value="both">Both (Half + Full Day)</option>
                                <option value="full_day">Full Day Only</option>
                                <option value="half_day">Half Day Only</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success w-100">
                                ⚡ Generate Seats
                            </button>
                        </div>
                        <div class="col-md-3">
                            <form action="{{ route('seats.destroyAll', $library->id) }}" method="POST"
                                  onsubmit="return confirm('Remove all available seats? Occupied seats will be kept.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    🗑 Clear Available
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Legend --}}
        <div class="col-12">
            <div class="d-flex gap-3 flex-wrap align-items-center">
                <span class="fw-semibold text-white small">Legend:</span>
                <span class="d-flex align-items-center gap-1">
                    <span style="width:20px;height:20px;border-radius:4px;background:#10b981;display:inline-block;"></span>
                    <span class="text-white small">Available</span>
                </span>
                <span class="d-flex align-items-center gap-1">
                    <span style="width:20px;height:20px;border-radius:4px;background:#ef4444;display:inline-block;"></span>
                    <span class="text-white small">Occupied</span>
                </span>
                <span class="d-flex align-items-center gap-1">
                    <span style="width:20px;height:20px;border-radius:4px;background:#f59e0b;display:inline-block;"></span>
                    <span class="text-white small">Half Day</span>
                </span>
                <span class="d-flex align-items-center gap-1">
                    <span style="width:20px;height:20px;border-radius:4px;background:#6366f1;display:inline-block;"></span>
                    <span class="text-white small">Full Day</span>
                </span>
            </div>
        </div>

        {{-- Seat Grid --}}
        <div class="col-12">
            <div class="card shadow border-0 rounded-4 bg-dark text-white">
                <div class="card-header rounded-top-4"
                     style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                    <h6 class="mb-0 text-white">🪑 Seat Layout</h6>
                </div>
                <div class="card-body p-4">

                    @if($seats->isEmpty())
                        <div class="text-center py-5">
                            <div style="font-size:4rem;">🪑</div>
                            <p class="text-muted mt-3">No seats configured yet.</p>
                            <p class="text-muted small">Use the "Generate Seats" form above to create seats.</p>
                        </div>
                    @else
                        {{-- Filter Tabs --}}
                        <div class="mb-3 d-flex gap-2 flex-wrap">
                            <button class="btn btn-sm btn-outline-light active" onclick="filterSeats('all', this)">All</button>
                            <button class="btn btn-sm btn-outline-success"      onclick="filterSeats('available', this)">✅ Available</button>
                            <button class="btn btn-sm btn-outline-danger"       onclick="filterSeats('occupied', this)">🔴 Occupied</button>
                        </div>

                        <div id="seatGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(72px,1fr));gap:10px;">
                            @foreach($seats as $seat)
                                @php
                                    $isOccupied = $seat->status === 'occupied';
                                    $member = $seat->activeMembers->first();

                                    if ($isOccupied) {
                                        $bg = '#ef4444';
                                    } elseif ($seat->seat_type === 'half_day') {
                                        $bg = '#f59e0b';
                                    } elseif ($seat->seat_type === 'full_day') {
                                        $bg = '#6366f1';
                                    } else {
                                        $bg = '#10b981';
                                    }
                                @endphp
                                <div class="seat-cell"
                                     data-status="{{ $seat->status }}"
                                     title="{{ $isOccupied && $member ? 'Occupied by: '.$member->name.' | '.$member->shift_label : 'Available ('.$seat->seat_type.')' }}"
                                     style="
                                        background:{{ $bg }};
                                        border-radius:8px;
                                        padding:10px 6px;
                                        text-align:center;
                                        cursor:{{ $isOccupied ? 'pointer' : 'default' }};
                                        position:relative;
                                        transition:transform 0.15s;
                                     "
                                     onmouseover="this.style.transform='scale(1.1)'"
                                     onmouseout="this.style.transform='scale(1)'"
                                     @if($isOccupied && $member)
                                     onclick="window.location='{{ route('members.show', $member->id) }}'"
                                     @endif
                                     >
                                    <div style="font-size:1.2rem;">🪑</div>
                                    <div style="font-size:0.7rem;font-weight:700;color:#fff;line-height:1.2;">
                                        {{ $seat->seat_number }}
                                    </div>
                                    @if($isOccupied && $member)
                                    <div style="font-size:0.55rem;color:rgba(255,255,255,0.85);overflow:hidden;white-space:nowrap;text-overflow:ellipsis;max-width:66px;">
                                        {{ Str::limit($member->name, 8) }}
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
function filterSeats(filter, btn) {
    // Update active button
    document.querySelectorAll('#seatGrid').forEach(() => {});
    document.querySelectorAll('.btn-sm').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('.seat-cell').forEach(cell => {
        if (filter === 'all') {
            cell.style.display = 'block';
        } else {
            cell.style.display = cell.dataset.status === filter ? 'block' : 'none';
        }
    });
}
</script>
@endsection
