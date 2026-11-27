@extends('coaching_admin.layout')

@section('title', 'Studio Gallery')

@section('content')
<div class="pb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fas fa-images text-teal-400 me-2"></i> Studio Gallery Showcase</h4>
            <p class="text-secondary small mb-0">High-resolution visual showcase photos for {{ $coaching->coaching_name }}.</p>
        </div>
        <a href="{{ route('commando-galleries.create', ['coaching_id' => $coaching->id]) }}" class="btn btn-teal btn-sm px-3">
            <i class="fas fa-plus me-1.5"></i> Add Gallery Item
        </a>
    </div>

    <div class="row g-3">
        @forelse($galleries as $gal)
        <div class="col-6 col-md-4 col-xl-3">
            <div class="card-custom overflow-hidden h-100">
                <div class="ratio ratio-4x3 position-relative">
                    <img src="{{ $gal->image_url }}" class="object-fit-cover w-100 h-100" alt="{{ $gal->title }}" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1555597673-b21d5c935865?auto=format&fit=crop&w=400&q=80';">
                    <span class="position-absolute top-0 end-0 m-2 badge {{ $gal->status ? 'bg-success' : 'bg-secondary' }}">
                        {{ $gal->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="p-3">
                    <h6 class="fw-bold text-white mb-1 text-truncate">{{ $gal->title }}</h6>
                    <p class="text-secondary small mb-2">{{ $gal->category ?? 'Training' }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Order: #{{ $gal->order_no }}</small>
                        <a href="{{ route('commando-galleries.edit', $gal->id) }}" class="btn btn-outline-light btn-sm py-0 px-2" style="font-size: 11px;">Edit</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card-custom p-5 text-center text-muted">
                <i class="fas fa-images fs-2 mb-2 d-block opacity-40"></i>
                No gallery showcase items added yet.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
