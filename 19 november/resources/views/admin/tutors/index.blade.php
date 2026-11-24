@extends('admin.master')

@section('content')

<style>
    .tutor-table th,
    .tutor-table td {
        white-space: normal !important;
        word-break: break-word;
        vertical-align: middle;
    }

    .tutor-table .badge {
        display: inline-block;
        margin-bottom: 0.25rem;
    }

    .tutor-table td {
        max-width: 180px;
    }
</style>

<style>
    .admin-index-card {
        background: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 16px;
        padding: 24px;
        color: #e2e8f0;
    }
    .admin-index-card .alert-success {
        background: #064e3b;
        color: #d1fae5;
        border-color: #064e3b;
    }
    .admin-index-card h4 {
        color: #ffffff;
    }
    .admin-index-table {
        table-layout: fixed;
        width: 100%;
        color: #e2e8f0;
    }
    .admin-index-table th,
    .admin-index-table td {
        white-space: normal !important;
        word-break: break-word;
        vertical-align: middle;
        border-color: #334155;
    }
    .admin-index-table thead th {
        background: #0f172a;
        color: #e2e8f0;
        border-bottom: 2px solid #334155;
    }
    .admin-index-table tbody tr {
        background: #111827;
    }
    .admin-index-table td {
        background: #111827;
    }
    .admin-index-table tbody tr:hover {
        background: #17233b;
    }
    .admin-index-card .btn-primary {
        background: #0ea5e9;
        border-color: #0ea5e9;
    }
    .admin-index-card .badge {
        display: inline-block;
        margin-bottom: 0.25rem;
    }
</style>

<div class="container mt-4">

    <div class="admin-index-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>All Tutors</h4>
            <a href="{{ route('tutors.create') }}" class="btn btn-primary">
                + Add Tutor
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-dark table-bordered table-hover align-middle admin-index-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Subjects</th>
                    <th>Classes</th>
                    <th>Boards</th>
                    <th>Exam Prep</th>
                    <th>Location</th>
                    <th>Rate</th>
                    <th>Experience</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tutors as $key => $tutor)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $tutor->full_name ?? 'N/A' }}</td>
                        <td style="min-width: 160px;">
                            @if(is_array($tutor->subject) && count($tutor->subject))
                                @foreach($tutor->subject as $sub)
                                    <span class="badge bg-info text-dark mb-1">{{ $sub }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td style="min-width: 140px;">
                            @if(is_array($tutor->classes) && count($tutor->classes))
                                @foreach($tutor->classes as $class)
                                    <span class="badge bg-secondary mb-1">{{ $class }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td style="min-width: 140px;">
                            @if(is_array($tutor->boards) && count($tutor->boards))
                                @foreach($tutor->boards as $board)
                                    <span class="badge bg-success mb-1">{{ $board }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td style="min-width: 140px;">
                            @if(is_array($tutor->exam_preparation) && count($tutor->exam_preparation))
                                @foreach($tutor->exam_preparation as $exam)
                                    <span class="badge bg-warning text-dark mb-1">{{ $exam }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            {{ $tutor->location_area ?? $tutor->location ?? 'N/A' }}
                        </td>
                        <td>₹{{ $tutor->hourly_rate ?? '0' }}</td>
                        <td>{{ $tutor->experience_years ? $tutor->experience_years.' yrs' : '-' }}</td>
                        <td>
                            @if($tutor->availability)
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-secondary">Offline</span>
                            @endif
                            @if($tutor->is_verified)
                                <span class="badge bg-primary">Verified</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('tutors.edit', $tutor->id) }}" class="btn btn-sm btn-warning mb-1">Edit</a>
                            <form action="{{ route('tutors.destroy', $tutor->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this tutor?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">No tutors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection