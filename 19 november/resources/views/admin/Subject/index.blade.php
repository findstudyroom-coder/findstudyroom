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
    <h2>Subjects List</h2>

    <a href="{{ route('Subject.create') }}" class="btn btn-primary mb-3">Add Subject</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-dark table-hover align-middle admin-index-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th width="150">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($Subjects as $key => $Subject)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $Subject->name }}</td>
                <td>
                    <a href="{{ route('Subject.edit', $Subject->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('Subject.delete', $Subject->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this subject?')" class="btn btn-danger btn-sm">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection