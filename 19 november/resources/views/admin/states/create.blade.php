@extends('admin.master')

@section('content')
<div class="container">
    <h2>Add State</h2>

    <form action="{{ route('states.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>State Name</label>
            <input type="text" name="name" class="form-control" required>

            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('states.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection