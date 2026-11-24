@extends('admin.master')

@section('content')

<style>
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
}

.stat-card {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    backdrop-filter: blur(12px);
    border-radius: 16px;
    padding: 20px;
    color: #fff;
    transition: 0.3s;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

.stat-title {
    font-size: 14px;
    color: #9ca3af;
    margin-bottom: 8px;
}

.stat-value {
    font-size: 28px;
    font-weight: bold;
}

.stat-icon {
    position: absolute;
    right: 15px;
    top: 15px;
    font-size: 22px;
    opacity: 0.3;
}
</style>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white fw-bold">Dashboard</h2>
    </div>

    <!-- CARDS -->
    <div class="dashboard-cards">

        <div class="stat-card">
            <div class="stat-icon">📚</div>
            <div class="stat-title">Total Libraries</div>
            <div class="stat-value">{{ $totalLibraries }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-title">Verified Libraries</div>
            <div class="stat-value">{{ $verifiedLibraries }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🎓</div>
            <div class="stat-title">Total Students</div>
            <div class="stat-value">{{ $totalStudents }}</div>
        </div>

    </div>

    <!-- CALL RECORDS -->
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card bg-dark text-white" style="border: 1px solid rgba(255,255,255,0.1); border-radius: 16px;">
                <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 pt-2 pb-2">
                        Today's Call Records
                        <span class="badge bg-primary ms-2">{{ $callsToday->count() }} Calls</span>
                    </h5>
                    @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'Sub Admin']))
                        <a href="{{ route('call-center.dashboard') }}" class="btn btn-sm btn-outline-light">Go to Call Center</a>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-striped table-hover mb-0" style="border-radius: 0 0 16px 16px; overflow: hidden;">
                            <thead>
                                <tr>
                                    <th class="ps-4">Time</th>
                                    <th>Library Name</th>
                                    <th>Status</th>
                                    <th>Remark</th>
                                    <th>Next Follow-up</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($callsToday as $call)
                                <tr>
                                    <td class="ps-4">{{ \Carbon\Carbon::parse($call->called_at)->format('h:i A') }}</td>
                                    <td>{{ $call->library ? $call->library->name : 'Unknown' }}</td>
                                    <td>
                                        @if($call->status == 'Converted')
                                            <span class="badge bg-success">{{ $call->status }}</span>
                                        @elseif($call->status == 'Interested')
                                            <span class="badge bg-info">{{ $call->status }}</span>
                                        @elseif($call->status == 'Not Interested')
                                            <span class="badge bg-danger">{{ $call->status }}</span>
                                        @elseif($call->status == 'Call Back Later' || $call->status == 'No Response')
                                            <span class="badge bg-warning text-dark">{{ $call->status }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $call->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $call->remark }}</td>
                                    <td>
                                        @if($call->next_followup_date)
                                            <span class="text-warning">{{ \Carbon\Carbon::parse($call->next_followup_date)->format('d M, Y') }}</span>
                                            {{ $call->next_followup_time ? date('h:i A', strtotime($call->next_followup_time)) : '' }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No calls made today.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS -->
    <div class="row mt-5">
        <div class="col-md-6 mb-4">
            <div class="card bg-dark text-white" style="border: 1px solid rgba(255,255,255,0.1); border-radius: 16px;">
                <div class="card-header border-bottom-0">
                    <h5 class="mb-0 pt-2 pb-2">Monthly Registrations ({{ date('Y') }})</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card bg-dark text-white" style="border: 1px solid rgba(255,255,255,0.1); border-radius: 16px;">
                <div class="card-header border-bottom-0">
                    <h5 class="mb-0 pt-2 pb-2">Yearly Registrations</h5>
                </div>
                <div class="card-body">
                    <canvas id="yearlyChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        
        // Prepare Monthly Data
        const monthlyStudentsData = @json($monthlyStudents ?? []);
        const monthlyLibrariesData = @json($monthlyLibraries ?? []);
        
        const monthlyLabels = [];
        const monthlyStudentCounts = [];
        const monthlyLibraryCounts = [];

        for (let i = 1; i <= 12; i++) {
            monthlyLabels.push(monthNames[i-1]);
            monthlyStudentCounts.push(monthlyStudentsData[i] || 0);
            monthlyLibraryCounts.push(monthlyLibrariesData[i] || 0);
        }

        const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [
                    {
                        label: 'Students',
                        data: monthlyStudentCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Libraries',
                        data: monthlyLibraryCounts,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#9ca3af' },
                        grid: { color: 'rgba(255,255,255,0.1)' }
                    },
                    x: {
                        ticks: { color: '#9ca3af' },
                        grid: { color: 'rgba(255,255,255,0.1)' }
                    }
                },
                plugins: {
                    legend: { labels: { color: '#fff' } }
                }
            }
        });

        // Prepare Yearly Data
        const yearlyStudentsData = @json($yearlyStudents ?? []);
        const yearlyLibrariesData = @json($yearlyLibraries ?? []);
        
        // Merge years from both datasets
        let years = new Set([
            ...Object.keys(yearlyStudentsData),
            ...Object.keys(yearlyLibrariesData)
        ]);
        years = Array.from(years).sort();

        if (years.length === 0) {
            years = [new Date().getFullYear().toString()];
        }

        const yearlyStudentCounts = years.map(y => yearlyStudentsData[y] || 0);
        const yearlyLibraryCounts = years.map(y => yearlyLibrariesData[y] || 0);

        const ctxYearly = document.getElementById('yearlyChart').getContext('2d');
        new Chart(ctxYearly, {
            type: 'line',
            data: {
                labels: years,
                datasets: [
                    {
                        label: 'Students',
                        data: yearlyStudentCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'Libraries',
                        data: yearlyLibraryCounts,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#9ca3af', precision: 0 },
                        grid: { color: 'rgba(255,255,255,0.1)' }
                    },
                    x: {
                        ticks: { color: '#9ca3af' },
                        grid: { color: 'rgba(255,255,255,0.1)' }
                    }
                },
                plugins: {
                    legend: { labels: { color: '#fff' } }
                }
            }
        });
    });
</script>

@endsection