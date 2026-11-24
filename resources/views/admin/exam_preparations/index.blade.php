{{-- resources/views/admin/exam_preparations/index.blade.php --}}

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
        <h2>Exam Preparations List</h2>

        <a href="{{ route('exam_preparations.create') }}" class="btn btn-success">
            Add Exam Preparation
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
                <th>Title</th>
                <th>Status</th>
                <th width="200">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($examPreparations as $examPreparation)
                <tr>
                    <td>{{ $examPreparation->id }}</td>
                    <td>{{ $examPreparation->title }}</td>
                    <td>
                        {{ $examPreparation->is_active ? 'Active' : 'Inactive' }}
                    </td>
                    <td>
                        <a 
                            href="{{ route('exam_preparations.edit', $examPreparation->id) }}"
                            class="btn btn-primary btn-sm"
                        >
                            Edit
                        </a>

                        <a 
                            href="{{ route('exam_preparations.delete', $examPreparation->id) }}"
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
                        No Records Found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection