@extends('admin.master')

@section('content')
<style>
    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .card {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        text-align: center;
        border-left: 5px solid #007bff;
    }
    .card h3 {
        margin: 0;
        font-size: 1.2rem;
        color: #555;
    }
    .card p {
        margin: 10px 0 0;
        font-size: 2rem;
        font-weight: bold;
        color: #333;
    }
    .card.pending { border-left-color: #ffc107; }
    .card.called { border-left-color: #17a2b8; }
    .card.follow-up { border-left-color: #fd7e14; }
    .card.interested { border-left-color: #28a745; }
    .card.not-interested { border-left-color: #dc3545; }
    .card.converted { border-left-color: #20c997; }
    .card.no-response { border-left-color: #6c757d; }

    .summary-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .summary-section h2 { margin-top: 0; margin-bottom: 20px; }
    .summary-cards {
        display: flex;
        justify-content: space-around;
        gap: 15px;
    }
    .summary-card {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        flex: 1;
        text-align: center;
        border: 1px solid #ddd;
    }
    .summary-card h4 { margin: 0 0 5px; font-size: 1rem; color: #666; }
    .summary-card p { margin: 0; font-size: 1.5rem; font-weight: bold; color: #000; }
</style>

<div class="content-header">
    <h1>Call Center Dashboard</h1>
</div>

<div class="summary-section">
    <h2>Today's Calling Summary</h2>
    <div class="summary-cards">
        <div class="summary-card">
            <h4>Total Calls</h4>
            <p>{{ $todayTotalCalls }}</p>
        </div>
        <div class="summary-card">
            <h4>Completed/Converted</h4>
            <p>{{ $todayCompleted }}</p>
        </div>
        <div class="summary-card">
            <h4>Follow-ups Scheduled</h4>
            <p>{{ $todayFollowUps }}</p>
        </div>
</div>

<div class="summary-section mt-4">
    <h2>Today's Call Records</h2>
    <div class="table-responsive" style="background:#fff; border-radius:10px; padding: 15px;">
        <table class="table table-bordered table-striped text-dark">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Library Name</th>
                    <th>Status</th>
                    <th>Remark</th>
                    <th>Next Follow-up</th>
                </tr>
            </thead>
            <tbody>
                @forelse($callsToday as $call)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($call->called_at)->format('h:i A') }}</td>
                    <td>{{ $call->library ? $call->library->name : 'Unknown' }}</td>
                    <td><span class="badge bg-secondary">{{ $call->status }}</span></td>
                    <td>{{ $call->remark }}</td>
                    <td>
                        @if($call->next_followup_date)
                            {{ \Carbon\Carbon::parse($call->next_followup_date)->format('d M, Y') }} 
                            {{ $call->next_followup_time ? date('h:i A', strtotime($call->next_followup_time)) : '' }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No calls made today.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<h2>Overall Statistics</h2>
<div class="dashboard-cards">
    <div class="card">
        <h3>Total Libraries</h3>
        <p>{{ $totalLibraries }}</p>
    </div>
    <a href="{{ route('call-center.libraries', ['lead_filter' => 'leads_or_visitors']) }}" style="text-decoration: none; color: inherit;">
        <div class="card" style="border-left-color: #dc3545; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
            <h3 style="color: #dc3545;">🔥 With Leads / Visitors</h3>
            <p style="color: #dc3545;">{{ $librariesWithTraffic ?? 0 }}</p>
            <small style="color: #888;">Active student traffic</small>
        </div>
    </a>
    <a href="{{ route('call-center.libraries', ['lead_filter' => 'has_leads']) }}" style="text-decoration: none; color: inherit;">
        <div class="card" style="border-left-color: #28a745; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
            <h3 style="color: #28a745;">🎯 With Student Leads</h3>
            <p style="color: #28a745;">{{ $librariesWithLeads ?? 0 }}</p>
            <small style="color: #888;">Seat bookings</small>
        </div>
    </a>
    <div class="card pending">
        <h3>Pending Calls</h3>
        <p>{{ $pendingCount }}</p>
    </div>
    <div class="card called">
        <h3>Called Libraries</h3>
        <p>{{ $calledCount }}</p>
    </div>
    <div class="card follow-up">
        <h3>Follow-up Required</h3>
        <p>{{ $followUpRequired }}</p>
    </div>
    <div class="card interested">
        <h3>Interested</h3>
        <p>{{ $interested }}</p>
    </div>
    <div class="card not-interested">
        <h3>Not Interested</h3>
        <p>{{ $notInterested }}</p>
    </div>
    <div class="card follow-up">
        <h3>Call Back Later</h3>
        <p>{{ $callBackLater }}</p>
    </div>
    <div class="card no-response">
        <h3>No Response</h3>
        <p>{{ $noResponse }}</p>
    </div>
    <div class="card converted">
        <h3>Converted</h3>
        <p>{{ $converted }}</p>
    </div>
</div>
@endsection
