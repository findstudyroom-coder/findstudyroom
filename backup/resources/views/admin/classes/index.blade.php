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
    <h2>Classes List</h2>

    <a href="{{ route('classes.create') }}" class="btn btn-primary mb-3">Add Class</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-dark table-hover align-middle admin-index-table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Action</th>
        </tr>

        @foreach($classes as $class)
        <tr>
            <td>{{ $class->id }}</td>
            <td>{{ $class->name }}</td>
            <td>
                <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-warning btn-sm">Edit</a>

                <form action="{{ route('classes.destroy', $class->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection