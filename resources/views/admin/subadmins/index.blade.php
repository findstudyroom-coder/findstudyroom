@extends('admin.master')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white">🛡️ Sub Admins </h2>

        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('subadmins.create') }}" class="btn btn-success text-nowrap shadow-sm">
                <i class="fas fa-plus-circle"></i> Create Sub Admin
            </a>
        </div> 
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card table-card border-0 mt-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subadmins as $admin)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->mobile ?? '-' }}</td>
                            <td>
                                @if($admin->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end align-middle">
                                <a href="{{ route('subadmins.edit', $admin->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('subadmins.destroy', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this Sub Admin?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No Sub Admins found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
