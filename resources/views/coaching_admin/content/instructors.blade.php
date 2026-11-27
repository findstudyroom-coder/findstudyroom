@extends('coaching_admin.layout')

@section('title', 'Master Instructors')

@section('content')
<div class="pb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fas fa-chalkboard-teacher text-teal-400 me-2"></i> Master Instructors &amp; Senseis</h4>
            <p class="text-secondary small mb-0">Certified teaching faculty for {{ $coaching->coaching_name }}.</p>
        </div>
        <a href="{{ route('commando-instructors.create', ['coaching_id' => $coaching->id]) }}" class="btn btn-teal btn-sm px-3">
            <i class="fas fa-plus me-1.5"></i> Add Sensei
        </a>
    </div>

    <div class="card-custom overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark-custom">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Instructor</th>
                        <th>Specialty / Dan Rank</th>
                        <th>Experience</th>
                        <th>Order</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($instructors as $inst)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $inst->image_url }}" class="rounded-circle border border-teal-500/30" style="width: 38px; height: 38px; object-fit: cover;" alt="{{ $inst->name }}">
                                <div>
                                    <div class="fw-bold text-white">{{ $inst->name }}</div>
                                    <small class="text-secondary">{{ $inst->title }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-dark border border-warning text-warning">{{ $inst->rank_badge ?: 'Instructor' }}</span></td>
                        <td>{{ $inst->experience ?? '10+ Years' }}</td>
                        <td>#{{ $inst->order_no }}</td>
                        <td>
                            <span class="badge {{ $inst->status ? 'bg-success' : 'bg-secondary' }}">{{ $inst->status ? 'Active' : 'Inactive' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-user-ninja fs-2 mb-2 d-block opacity-40"></i>
                            No instructors added yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
