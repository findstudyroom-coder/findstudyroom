@extends('admin.master')

@section('content')
<div class="content-header">
    <h1>Sub-Admin Calling Report</h1>
</div>

<div class="table-responsive" style="background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Sub-Admin Name</th>
                <th>Total Calls Made</th>
                <th>Interested</th>
                <th>Converted</th>
                <th>Call Back Later</th>
                <th>Not Interested</th>
                <th>No Response</th>
                <th>Last Call Made At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $row)
            <tr>
                <td>{{ $row->admin ? $row->admin->name : 'Unknown' }}</td>
                <td><strong>{{ $row->total_calls }}</strong></td>
                <td><span class="badge bg-primary">{{ $row->interested_calls }}</span></td>
                <td><span class="badge bg-success">{{ $row->converted_calls }}</span></td>
                <td><span class="badge bg-warning text-dark">{{ $row->callback_calls }}</span></td>
                <td><span class="badge bg-danger">{{ $row->not_interested_calls }}</span></td>
                <td><span class="badge bg-secondary">{{ $row->no_response_calls }}</span></td>
                <td>{{ $row->last_call_at ? \Carbon\Carbon::parse($row->last_call_at)->format('d M, Y h:i A') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No call reports found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
