@extends('admin.master')

@section('content')

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white">🎓 Coachings </h2>

    <div class="d-flex align-items-center gap-3">
        <!-- SEARCH FORM -->
        <form action="{{ route('coachings.index') }}" method="GET" class="m-0">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-dark border-secondary text-white-50"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Search coaching..." value="{{ request('search') }}">
                
                <button type="submit" class="btn btn-primary px-3"><i class="fas fa-filter"></i> Filter</button>
                
                @if(request('search'))
                    <a href="{{ route('coachings.index') }}" class="btn btn-danger px-3" title="Clear Filters">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- ADD BUTTON -->
        <a href="{{ route('coachings.create') }}" class="btn btn-success text-nowrap shadow-sm">
            <i class="fas fa-plus-circle"></i> Add Coaching
        </a>
    </div>
</div>

<!-- CARD -->
<div class="card table-card border-0 mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>S.No</th>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coachings as $coaching)
                    <tr>
                        <td>{{ $loop->iteration + $coachings->firstItem() - 1 }}</td>
                        <td>
                            @if(!empty($coaching->logo))
                                <img src="{{ asset('storage/'.$coaching->logo) }}" 
                                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($coaching->coaching_name) }}&background=random&color=fff';"
                                     style="height:50px; width:50px; object-fit:cover; border-radius:6px;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($coaching->coaching_name) }}&background=random&color=fff" 
                                     style="height:50px; width:50px; object-fit:cover; border-radius:6px;" alt="Default Image">
                            @endif
                        </td>
                        <td class="fw-bold">{{ $coaching->coaching_name }}</td>
                        <td>{{ $coaching->city ?? '-' }}</td>
                        <td class="text-success fw-semibold">
                            {{ $coaching->mobile_number }}
                            @php
                            $whatsappNum = $coaching->whatsapp_number ?? $coaching->mobile_number ?? '';
                            $whatsappNum = preg_replace('/[^0-9]/', '', $whatsappNum);
                            if(strlen($whatsappNum) == 10) {
                                $whatsappNum = '91' . $whatsappNum;
                            }
                            $waLink = "https://wa.me/" . $whatsappNum;
                            @endphp
                            @if($whatsappNum)
                            <a href="{{ $waLink }}" target="_blank" class="text-success ms-2" title="Send WhatsApp Message">
                                <i class="fab fa-whatsapp fa-lg"></i>
                            </a>
                            @endif
                        </td>
                        <td>
                            <!-- Can add status logic later if needed -->
                            <span class="badge bg-success">Active</span>
                        </td>
                        <td class="text-end align-middle">
                            <a href="{{ route('coachings.edit', $coaching->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('coachings.destroy', $coaching->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this coaching?');" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- PAGINATION -->
        <div class="d-flex justify-content-end mt-3">
            {{ $coachings->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection
