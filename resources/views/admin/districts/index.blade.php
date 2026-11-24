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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>District List</h2>

        <a href="{{ route('districts.create') }}" class="btn btn-success">
            Add District
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
                <th>State Name</th>
                <th>District Name</th>
                <th width="220">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($districts as $district)
                <tr>
                    <td>{{ $district->id }}</td>
                    <td>{{ $district->state->name ?? '-' }}</td>
                    <td>{{ $district->name }}</td>
                    <td>
                        <a href="{{ route('districts.edit', $district->id) }}"
                           class="btn btn-primary btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('districts.destroy', $district->id) }}"
                              method="POST"
                              style="display:inline-block;">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this district?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">
                        No districts found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection