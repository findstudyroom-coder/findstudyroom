@extends('admin.master')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color:#6366f1;">👥 Members</h4>
            <p class="text-muted mb-0 small">Manage all library members</p>
        </div>
        <a href="{{ route('members.create') }}" class="btn btn-primary px-4 shadow-sm">
            ➕ Add Member
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4" style="background:#1e1e2e;">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('members.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-white small mb-1">🔍 Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm bg-dark text-white border-secondary"
                           placeholder="Name, mobile, location...">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-white small mb-1">🏛 Library</label>
                    <select name="library_id" class="form-select form-select-sm bg-dark text-white border-secondary">
                        <option value="">All Libraries</option>
                        @foreach($libraries as $lib)
                            <option value="{{ $lib->id }}" {{ request('library_id') == $lib->id ? 'selected' : '' }}>
                                {{ $lib->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-white small mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm bg-dark text-white border-secondary">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>✅ Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>⏸ Inactive</option>
                        <option value="expired"  {{ request('status') === 'expired'  ? 'selected' : '' }}>❌ Expired</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary flex-fill">Filter</button>
                    <a href="{{ route('members.index') }}" class="btn btn-sm btn-secondary flex-fill">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Members Table --}}
    <div class="card shadow border-0 rounded-4 bg-dark text-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Library</th>
                            <th>Seat</th>
                            <th>Shift</th>
                            <th>Plan</th>
                            <th>Expiry</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $m)
                        <tr>
                            <td class="ps-3 text-muted small">{{ $m->id }}</td>
                            <td>
                                <img src="{{ $m->photo_url }}" alt="Photo"
                                     class="rounded-circle border border-secondary"
                                     style="width:38px;height:38px;object-fit:cover;">
                            </td>
                            <td class="fw-semibold">{{ $m->name }}</td>
                            <td>{{ $m->mobile }}</td>
                            <td class="small text-info">{{ $m->library->name ?? '—' }}</td>
                            <td>
                                @if($m->seat)
                                    <span class="badge bg-secondary">Seat {{ $m->seat->seat_number }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="small">
                                @php
                                    $shiftColors = [
                                        'half_day_morning' => 'warning',
                                        'half_day_evening' => 'info',
                                        'full_day'         => 'success',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $shiftColors[$m->shift_type] ?? 'secondary' }} text-dark">
                                    {{ ['half_day_morning'=>'AM','half_day_evening'=>'PM','full_day'=>'Full'][$m->shift_type] ?? $m->shift_type }}
                                </span>
                            </td>
                            <td class="small">{{ $m->plan->name ?? '—' }}</td>
                            <td class="small">
                                @if($m->end_date)
                                    @if($m->isExpired())
                                        <span class="text-danger">{{ $m->end_date->format('d M Y') }}</span>
                                    @else
                                        <span class="text-success">{{ $m->end_date->format('d M Y') }}</span>
                                        <br><small class="text-muted">{{ $m->daysRemaining() }}d left</small>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($m->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($m->status === 'expired')
                                    <span class="badge bg-danger">Expired</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('members.show', $m->id) }}" class="btn btn-sm btn-outline-info me-1" title="View">👁</a>
                                <a href="{{ route('members.edit', $m->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">✏️</a>
                                <form action="{{ route('members.destroy', $m->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete member {{ addslashes($m->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete">🗑</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-5">
                                No members found. <a href="{{ route('members.create') }}" class="text-primary">Add the first member →</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($members->hasPages())
            <div class="d-flex justify-content-center py-3">
                {{ $members->links() }}
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
