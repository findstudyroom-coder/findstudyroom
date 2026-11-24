@extends('admin.master')

@section('content')
<div class="container">
    <h2>Add Class</h2>

    <form action="{{ route('classes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Class Name</label>
            <input type="text" name="name" class="form-control" required>

            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('classes.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection