@extends('coaching_admin.layout')

@section('title', 'Courses & Disciplines')

@section('content')
<div class="pb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fas fa-graduation-cap text-teal-400 me-2"></i> Disciplines &amp; Classes</h4>
            <p class="text-secondary small mb-0">Active curriculum offerings for {{ $coaching->coaching_name }}.</p>
        </div>
        <a href="{{ route('commando-classes.create', ['coaching_id' => $coaching->id]) }}" class="btn btn-teal btn-sm px-3">
            <i class="fas fa-plus me-1.5"></i> Add New Discipline
        </a>
    </div>

    <div class="card-custom overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark-custom">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Discipline / Class Name</th>
                        <th>Difficulty Level</th>
                        <th>Duration</th>
                        <th>Order</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $c)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-bold text-white">{{ $c->title }}</div>
                            <small class="text-secondary">{{ \Illuminate\Support\Str::limit($c->description, 70) }}</small>
                        </td>
                        <td><span class="badge bg-dark border border-secondary text-info">{{ $c->level ?? 'All Levels' }}</span></td>
                        <td>{{ $c->duration ?? '60 mins' }}</td>
                        <td>#{{ $c->order_no }}</td>
                        <td>
                            <span class="badge {{ $c->status ? 'bg-success' : 'bg-secondary' }}">{{ $c->status ? 'Active' : 'Inactive' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-fist-raised fs-2 mb-2 d-block opacity-40"></i>
                            No disciplines added yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
