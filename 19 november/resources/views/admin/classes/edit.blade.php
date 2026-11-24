@extends('admin.master')

@section('content')
<div class="container">
    <h2>Edit Class</h2>

    <form action="{{ route('classes.update', $class->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Class Name</label>
            <input type="text" name="name" class="form-control" value="{{ $class->name }}" required>

            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('classes.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection