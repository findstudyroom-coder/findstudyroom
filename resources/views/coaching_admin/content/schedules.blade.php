@extends('coaching_admin.layout')

@section('title', 'Batch Schedules')

@section('content')
<div class="pb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fas fa-calendar-alt text-teal-400 me-2"></i> Weekly Batch Schedules</h4>
            <p class="text-secondary small mb-0">Timing slots and day-wise training schedule for {{ $coaching->coaching_name }}.</p>
        </div>
        <a href="{{ route('commando-schedules.create', ['coaching_id' => $coaching->id]) }}" class="btn btn-teal btn-sm px-3">
            <i class="fas fa-plus me-1.5"></i> Add Slot
        </a>
    </div>

    <div class="card-custom overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark-custom">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Day / Day Range</th>
                        <th>Discipline / Class</th>
                        <th>Time Window</th>
                        <th>Instructor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-dark border border-info text-info">{{ $s->day }}</span></td>
                        <td class="fw-bold text-white">{{ $s->discipline }}</td>
                        <td><i class="far fa-clock me-1 text-teal-400"></i> {{ $s->time_slot }}</td>
                        <td>{{ $s->instructor ?? 'Sensei' }}</td>
                        <td>
                            <span class="badge {{ $s->status ? 'bg-success' : 'bg-secondary' }}">{{ $s->status ? 'Active' : 'Inactive' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-calendar-times fs-2 mb-2 d-block opacity-40"></i>
                            No schedules defined yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
