@extends('admin.master')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white">Library Details</h2>
    <a href="{{ route('library-details.create') }}" class="btn btn-primary">+ Add Detail</a>
</div>

<div class="card table-card border-0 mt-3">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle">
        <thead>
          <tr>
            <th>S.No</th>
            <th>Library</th>
            <th>Name</th>
            <th>Location</th>
            <th>Phone</th>
            <th>Capacity</th>
            <th>Logo</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($details as $d)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $d->library->name ?? '-' }}</td>
            <td>{{ $d->name }}</td>
            <td>{{ $d->location ?? '-' }}</td>
            <td>{{ $d->phone ?? '-' }}</td>
            <td>{{ $d->capacity ?? '-' }}</td>
            <td>
              @if($d->logo_url)
                <a href="{{ $d->logo_url }}" target="_blank" rel="noopener" class="text-info">View</a>
              @else
                -
              @endif
            </td>
            <td class="text-end">
              <a href="{{ route('library-details.edit', $d->id) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
              <form action="{{ route('library-details.destroy', $d->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
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
