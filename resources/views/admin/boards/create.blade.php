{{-- resources/views/admin/boards/create.blade.php --}}

@extends('admin.master')

@section('content')
<div class="container">
    <h2>Add Board</h2>

    <form action="{{ route('boards.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Board Name</label>
            <input type="text" name="board_name" class="form-control" required>

            @error('board_name')
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
        <a href="{{ route('boards.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection