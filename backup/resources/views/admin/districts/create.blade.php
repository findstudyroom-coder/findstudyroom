@extends('admin.master')

@section('content')
<div class="container">
    <h2>Add District</h2>

    <form action="{{ route('districts.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>State</label>
            <select name="state_id" class="form-control" required>
                <option value="">Select State</option>

                @foreach($states as $state)
                    <option value="{{ $state->id }}">
                        {{ $state->name }}
                    </option>
                @endforeach
            </select>

            @error('state_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>District Name</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   required>

            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-success">Save</button>

        <a href="{{ route('districts.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </form>
</div>
@endsection