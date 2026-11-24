{{-- resources/views/admin/boards/edit.blade.php --}}

@extends('admin.master')

@section('content')
<div class="container">
    <h2>Edit Board</h2>

    <form action="{{ route('boards.update', $board->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Board Name</label>
            <input 
                type="text" 
                name="board_name" 
                class="form-control" 
                value="{{ $board->board_name }}" 
                required
            >

            @error('board_name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ $board->is_active == 1 ? 'selected' : '' }}>
                    Active
                </option>
                <option value="0" {{ $board->is_active == 0 ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('boards.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection