@extends('admin.master')

@section('content')
<div class="container">
    <h2>Edit State</h2>

    <form action="{{ route('states.update', $state->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>State Name</label>
            <input type="text" name="name" value="{{ $state->name }}" class="form-control" required>

            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('states.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection