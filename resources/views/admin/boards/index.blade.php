{{-- resources/views/admin/boards/index.blade.php --}}

@extends('admin.master')

@section('content')
<style>
    .admin-index-table { table-layout: fixed; width: 100%; color: #e2e8f0; }
    .admin-index-table th, .admin-index-table td { white-space: normal !important; word-break: break-word; vertical-align: middle; border-color: #334155; }
    .admin-index-table tbody tr { background: #111827; }
    .admin-index-table tbody tr:hover { background: #17233b; }
    .admin-index-table thead th { background: #0f172a; color: #e2e8f0; }
</style>
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h2>Boards List</h2>
        <a href="{{ route('boards.create') }}" class="btn btn-success">
            Add Board
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-dark table-hover align-middle admin-index-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Board Name</th>
                <th>Status</th>
                <th width="200">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($boards as $board)
                <tr>
                    <td>{{ $board->id }}</td>
                    <td>{{ $board->board_name }}</td>
                    <td>
                        {{ $board->is_active ? 'Active' : 'Inactive' }}
                    </td>
                    <td>
                        <a 
                            href="{{ route('boards.edit', $board->id) }}" 
                            class="btn btn-primary btn-sm"
                        >
                            Edit
                        </a>

                        <a 
                            href="{{ route('boards.delete', $board->id) }}" 
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure?')"
                        >
                            Delete
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">
                        No Boards Found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection