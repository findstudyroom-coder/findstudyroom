{{-- resources/views/admin/exam_preparations/create.blade.php --}}

@extends('admin.master')

@section('content')
<div class="container">
    <h2>Add Exam Preparation</h2>

    <form action="{{ route('exam_preparations.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Title</label>
            <input 
                type="text" 
                name="title" 
                class="form-control" 
                required
            >

            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="is_active" class="form-control">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('exam_preparations.index') }}" class="btn btn-secondary">
            Back
        </a>
    </form>
</div>
@endsection