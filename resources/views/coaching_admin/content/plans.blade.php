@extends('coaching_admin.layout')

@section('title', 'Membership Plans')

@section('content')
<div class="pb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fas fa-tags text-teal-400 me-2"></i> Membership Plans &amp; Pricing</h4>
            <p class="text-secondary small mb-0">Active packages displayed to students on {{ $coaching->coaching_name }}'s portal.</p>
        </div>
        <a href="{{ route('commando-plans.create', ['coaching_id' => $coaching->id]) }}" class="btn btn-teal btn-sm px-3">
            <i class="fas fa-plus me-1.5"></i> Add Plan
        </a>
    </div>

    <div class="row g-4">
        @forelse($plans as $p)
        <div class="col-md-6 col-xl-4">
            <div class="card-custom p-4 h-100 d-flex flex-column justify-content-between {{ $p->is_featured ? 'border-teal-500' : '' }}">
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold text-white mb-0">{{ $p->title }}</h5>
                        @if($p->is_featured)
                            <span class="badge bg-teal-500 text-white small">Featured</span>
                        @endif
                    </div>
                    <div class="h3 fw-bold text-teal-400 mb-2">₹{{ number_format($p->price) }} <small class="text-secondary fs-6 fw-normal">/ {{ $p->duration ?? 'Month' }}</small></div>
                    <p class="text-secondary small mb-3">{{ $p->description }}</p>

                    @if(!empty($p->features) && is_array($p->features))
                    <ul class="list-unstyled small d-flex flex-column gap-1.5 mb-3">
                        @foreach($p->features as $feat)
                        <li class="text-slate-300"><i class="fas fa-check text-teal-400 me-2"></i> {{ $feat }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <div class="pt-3 border-top border-white/10 d-flex justify-content-between align-items-center">
                    <span class="badge {{ $p->status ? 'bg-success' : 'bg-secondary' }}">{{ $p->status ? 'Active' : 'Draft' }}</span>
                    <a href="{{ route('commando-plans.edit', $p->id) }}" class="btn btn-outline-light btn-sm">Edit Plan</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card-custom p-5 text-center text-muted">
                <i class="fas fa-tag fs-2 mb-2 d-block opacity-40"></i>
                No membership plans configured yet.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
