@extends('admin.master')

@section('content')


    <!-- HEADER -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white">📚 Libraries </h2>

    <div class="d-flex align-items-center gap-3">
        <!-- SEARCH FORM -->
        <form action="{{ route('libraries.index') }}" method="GET" class="m-0">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-dark border-secondary text-white-50"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Search library..." value="{{ request('search') }}">
                
                <select name="status" class="form-select bg-dark text-white border-secondary" style="max-width: 140px;">
                    <option value="">All Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                
                <select name="is_verified" class="form-select bg-dark text-white border-secondary" style="max-width: 140px;">
                    <option value="">Verification</option>
                    <option value="1" {{ request('is_verified') == '1' ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ request('is_verified') == '0' ? 'selected' : '' }}>Unverified</option>
                </select>

                <button type="submit" class="btn btn-primary px-3"><i class="fas fa-filter"></i> Filter</button>
                
                @if(request('search') || request('status') || request('is_verified') != '')
                    <a href="{{ route('libraries.index') }}" class="btn btn-danger px-3" title="Clear Filters">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- ADD BUTTON -->
        <a href="{{ route('libraries.create') }}" class="btn btn-success text-nowrap shadow-sm">
            <i class="fas fa-plus-circle"></i> Add Library
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
                        <th>Image</th>
                        <th>Name</th>
                        <th>State</th>
                         <th>Districe</th>
                        <th>Price</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($libraries as $lib)
                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>
    @if(!empty($lib->images) && is_array($lib->images) && count($lib->images) > 0 && !empty($lib->images[0]))
        <img src="{{ asset('storage/app/public/'.$lib->images[0]) }}" 
             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($lib->name) }}&background=random&color=fff';"
             style="height:50px; width:50px; object-fit:cover; border-radius:6px;">
    @else
        <img src="https://ui-avatars.com/api/?name={{ urlencode($lib->name) }}&background=random&color=fff" 
             style="height:50px; width:50px; object-fit:cover; border-radius:6px;" alt="Default Image">
    @endif
</td>

                        <td class="fw-bold">{{ $lib->name }}</td>

                       <td>
    {{ $lib->state->name ?? '-' }}</td>
   <td> {{ $lib->district->name ?? '-' }}
</td>

                        <td class="text-success fw-semibold">₹{{ $lib->price }}</td>
                        <td class="text-success fw-semibold">
                            {{ $lib->phone }}
                            @php
                            $whatsappNum = $lib->whatsapp_no ?? $lib->phone ?? '';
                            $whatsappNum = preg_replace('/[^0-9]/', '', $whatsappNum);
                            if(strlen($whatsappNum) == 10) {
                                $whatsappNum = '91' . $whatsappNum;
                            }
                            $waMessage = "नमस्ते सर/मैडम,\n\nमैं *FindStudyRoom* टीम से बात कर रहा हूँ।\n\nहम आपके *Library का Verified Premium Profile* हमारी वेबसाइट पर बना रहे हैं, ताकि आपके आसपास के छात्र आसानी से आपकी लाइब्रेरी खोज सकें।\n\nइसके लिए हमें आपकी लाइब्रेरी की कुछ जानकारी चाहिए:\n\n• Library Name\n• Full Address / Location\n• Contact Number\n• Photos (Library, Seats, Reception आदि)\n• Fees & Available Plans\n• Timings\n• Facilities (Wi-Fi, AC, CCTV, Parking, RO Water, Power Backup आदि)\n\nआपकी जानकारी मिलने के बाद हम आपकी *Premium Library Profile* तैयार करेंगे, जिससे छात्रों को आपकी लाइब्रेरी की पूरी जानकारी मिलेगी और आपकी ऑनलाइन विज़िबिलिटी बढ़ेगी।\n\nयदि आपको कोई सहायता चाहिए, तो कृपया हमें बताइए।\nधन्यवाद!\n\n*FindStudyRoom Team*\nhttps://findstudyroom.com/";
                            $waLink = "https://wa.me/" . $whatsappNum . "?text=" . urlencode($waMessage);
                            @endphp
                            @if($whatsappNum)
                            <a href="{{ $waLink }}" target="_blank" class="text-success ms-2" title="Send WhatsApp Message">
                                <i class="fab fa-whatsapp fa-lg"></i>
                            </a>
                            @endif
                        </td>

                        <td>
                            @if($lib->status == 'open')
                                <span class="badge bg-success">Open</span>
                            @else
                                <span class="badge bg-danger">Closed</span>
                            @endif
                        </td>

                        <td class="text-end align-middle">

                            <a href="{{ route('seats.index', $lib->id) }}" class="btn btn-sm btn-info me-1" title="Seat Map">
                                🗺
                            </a>

                            <a href="{{ route('members.index') }}?library_id={{ $lib->id }}" class="btn btn-sm btn-outline-light me-1" title="Members">
                                👥
                            </a>

                            <a href="{{ route('libraries.edit',$lib->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('libraries.destroy',$lib->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this library?');" style="display:inline-block;">
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

    </div>

</div>



@endsection