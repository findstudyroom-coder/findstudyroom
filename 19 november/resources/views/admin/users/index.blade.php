@extends('admin.master')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color:#6366f1;">👥 Users</h4>
            <p class="text-muted mb-0 small">Manage all users/applications</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Users Table --}}
    <div class="card shadow border-0 rounded-4 bg-dark text-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                           
                            <th>Type</th>
                         
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $user)
                        <tr>
                            <td class="ps-3 text-muted small">{{ $user->id }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                           
                            <td>
                                <span class="badge bg-info text-dark">{{ $user->role ?? 'User' }}</span>
                            </td>
                            
                            <td class="text-end pe-3">
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete user {{ addslashes($user->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete">🗑</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                No users found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection
