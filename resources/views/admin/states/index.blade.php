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
        <h2>States List</h2>
        <a href="{{ route('states.create') }}" class="btn btn-success">+ Add State</a>
    </div>

    <table class="table table-dark table-hover align-middle admin-index-table">
        <thead>
            <tr>
                <th>#</th>
                <th>State Name</th>
                <th width="150">Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($states as $state)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $state->name }}</td>

                <td>
                    <a href="{{ route('states.edit', $state->id) }}" class="btn btn-primary btn-sm">Edit</a>

                    <form action="{{ route('states.destroy', $state->id) }}" 
                          method="POST" 
                          style="display:inline-block;">
                        @csrf
                        @method('DELETE')

                        <button onclick="return confirm('Delete this state?')" 
                                class="btn btn-danger btn-sm">
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