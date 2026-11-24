@extends('admin.master')

@section('content')
<div class="container">
    <h2>Edit District</h2>

    <form action="{{ route('districts.update', $district->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>State</label>
            <select name="state_id" class="form-control" required>
                <option value="">Select State</option>

                @foreach($states as $state)
                    <option value="{{ $state->id }}"
                        {{ $district->state_id == $state->id ? 'selected' : '' }}>
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
                   value="{{ $district->name }}"
                   class="form-control"
                   required>

            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-primary">Update</button>

        <a href="{{ route('districts.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </form>
</div>
@endsection