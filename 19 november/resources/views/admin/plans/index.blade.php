
@extends('admin.master')

@section('content')

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white">Plans</h2>
    <a href="{{ route('plans.create') }}" class="btn btn-primary">+ Add Plan</a>
  </div>

  <div class="card table-card border-0 mt-3">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-dark table-hover align-middle">
          <thead>
            <tr>
              <th>S.No</th>
              <th>Name</th>
              <th>Amount</th>
              <th>Validity</th>
              <th>Whats Limit</th>
              <th>Text Limit</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($plans as $plan)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td class="fw-bold">{{ $plan->name }}</td>
              <td>₹{{ $plan->amount }}</td>
              <td>{{ $plan->validity ?? '-' }}</td>
              <td>{{ $plan->whats_text_limit ?? '-' }}</td>
              <td>{{ $plan->text_limit ?? '-' }}</td>
              <td class="text-end">
                <a href="{{ route('plans.edit', $plan->id) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
