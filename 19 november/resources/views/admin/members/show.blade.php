@extends('admin.master')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color:#6366f1;">👤 Member Card</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('members.edit', $member->id) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Member Card --}}
        <div class="col-lg-4">
            <div class="card shadow border-0 rounded-4 text-white text-center overflow-hidden"
                 style="background:linear-gradient(160deg,#1e1e2e 0%,#2d2d44 100%);">

                {{-- Banner --}}
                <div style="height:80px;background:linear-gradient(135deg,#6366f1,#8b5cf6);"></div>

                {{-- Avatar --}}
                <div style="margin-top:-50px;">
                    <img src="{{ $member->photo_url }}" alt="{{ $member->name }}"
                         class="rounded-circle border border-4 shadow"
                         style="width:100px;height:100px;object-fit:cover;border-color:#6366f1!important;">
                </div>

                <div class="card-body pt-2 pb-4">
                    <h5 class="fw-bold mb-1">{{ $member->name }}</h5>
                    <p class="text-muted small mb-2">📱 {{ $member->mobile }}</p>

                    @if($member->status === 'active')
                        <span class="badge bg-success fs-6 px-3 py-2">✅ Active</span>
                    @elseif($member->status === 'expired')
                        <span class="badge bg-danger fs-6 px-3 py-2">❌ Expired</span>
                    @else
                        <span class="badge bg-secondary fs-6 px-3 py-2">⏸ Inactive</span>
                    @endif

                    <hr class="border-secondary my-3">

                    <div class="text-start px-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">🏛 Library</span>
                            <span class="small fw-semibold">{{ $member->library->name ?? '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">📍 Location</span>
                            <span class="small">{{ $member->location ?? '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">🪑 Seat</span>
                            <span class="small">
                                @if($member->seat)
                                    <span class="badge bg-indigo" style="background:#6366f1;">Seat {{ $member->seat->seat_number }}</span>
                                @else —
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">⏰ Shift</span>
                            <span class="small badge bg-info text-dark">{{ $member->shift_label }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">📋 Plan</span>
                            <span class="small">{{ $member->plan->name ?? '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">📅 Start</span>
                            <span class="small">{{ $member->start_date?->format('d M Y') ?? '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">📅 Expiry</span>
                            <span class="small {{ $member->isExpired() ? 'text-danger' : 'text-success' }}">
                                {{ $member->end_date?->format('d M Y') ?? '—' }}
                            </span>
                        </div>
                        @if($member->end_date && !$member->isExpired())
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">⏳ Days Left</span>
                            <span class="small text-warning fw-bold">{{ $member->daysRemaining() }} days</span>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- Right Column: Notes + Document --}}
        <div class="col-lg-8">

            {{-- Document Card --}}
            <div class="card shadow border-0 rounded-4 bg-dark text-white mb-4">
                <div class="card-header rounded-top-4 text-white"
                     style="background:linear-gradient(135deg,#10b981,#059669);">
                    <h6 class="mb-0">📄 Identity Document</h6>
                </div>
                <div class="card-body p-4">
                    @if($member->document_url)
                        @php $ext = pathinfo($member->document, PATHINFO_EXTENSION); @endphp
                        @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                            <img src="{{ $member->document_url }}" alt="Document"
                                 class="img-fluid rounded-3 shadow" style="max-height:300px;">
                        @else
                            <div class="text-center py-4">
                                <div style="font-size:4rem;">📄</div>
                                <p class="text-muted mt-2">{{ basename($member->document) }}</p>
                            </div>
                        @endif
                        <div class="mt-3">
                            <a href="{{ $member->document_url }}" target="_blank" class="btn btn-outline-success">
                                📂 Open Document
                            </a>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No document uploaded.</p>
                    @endif
                </div>
            </div>

            {{-- Notes Card --}}
            @if($member->notes)
            <div class="card shadow border-0 rounded-4 bg-dark text-white mb-4">
                <div class="card-header rounded-top-4 text-white"
                     style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                    <h6 class="mb-0">📝 Notes</h6>
                </div>
                <div class="card-body p-4">
                    <p class="mb-0">{{ $member->notes }}</p>
                </div>
            </div>
            @endif

            {{-- Actions --}}
            <div class="card shadow border-0 rounded-4 bg-dark text-white">
                <div class="card-body p-4">
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('members.edit', $member->id) }}" class="btn btn-warning px-4">✏️ Edit Member</a>
                        <a href="{{ route('seats.index', $member->library_id) }}" class="btn btn-info px-4">🗺 Seat Map</a>
                        <form action="{{ route('members.destroy', $member->id) }}" method="POST"
                              onsubmit="return confirm('Delete this member permanently?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger px-4">🗑 Delete</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
