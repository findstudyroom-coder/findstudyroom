@extends('admin.master')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color:#6366f1;">👨‍🎓 Students</h4>
            <p class="text-muted mb-0 small">Manage all library students</p>
        </div>
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
            <form method="GET" action="{{ route('students.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-white small mb-1">🔍 Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm bg-dark text-white border-secondary"
                           placeholder="Name, phone...">
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
                
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary flex-fill">Filter</button>
                    <a href="{{ route('students.index') }}" class="btn btn-sm btn-secondary flex-fill">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Students Table --}}
    <div class="card shadow border-0 rounded-4 bg-dark text-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Library</th>
                            <th>Seat (Active)</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $s)
                        <tr>
                            <td class="ps-3 text-muted small">{{ $s->id }}</td>
                            <td>
                                @if($s->photo_url)
                                    <img src="{{ $s->photo_url }}" alt="Photo"
                                         class="rounded-circle border border-secondary"
                                         style="width:38px;height:38px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle border border-secondary d-flex align-items-center justify-content-center bg-secondary" style="width:38px;height:38px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $s->name }}</td>
                            <td>{{ $s->phone }}</td>
                            <td class="small text-info">{{ $s->library->name ?? '—' }}</td>
                            <td>
                                @if($s->activeMembership && $s->activeMembership->seat)
                                    <span class="badge bg-secondary">Seat {{ $s->activeMembership->seat->seat_number }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="small">
                                {{ $s->activeMembership && $s->activeMembership->plan ? $s->activeMembership->plan->name : '—' }}
                            </td>
                            <td>
                                @if($s->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <form action="{{ route('students.destroy', $s->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete student {{ addslashes($s->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete">🗑</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                No students found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($students->hasPages())
            <div class="d-flex justify-content-center py-3">
                {{ $students->links() }}
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
