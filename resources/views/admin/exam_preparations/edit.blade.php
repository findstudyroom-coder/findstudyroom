{{-- resources/views/admin/exam_preparations/edit.blade.php --}}

@extends('admin.master')

@section('content')
<div class="container">
    <h2>Edit Exam Preparation</h2>

    <form action="{{ route('exam_preparations.update', $examPreparation->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Title</label>
            <input 
                type="text" 
                name="title" 
                class="form-control"
                value="{{ $examPreparation->title }}"
                required
            >

            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ $examPreparation->is_active == 1 ? 'selected' : '' }}>
                    Active
                </option>
                <option value="0" {{ $examPreparation->is_active == 0 ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('exam_preparations.index') }}" class="btn btn-secondary">
            Back
        </a>
    </form>
</div>
@endsection