@extends('admin.master')

@section('content')
<div class="container">
    <h2>Edit Tehsil</h2>

    <form action="{{ route('tehsils.update', $tehsil->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>District</label>
            <select name="district_id" class="form-control" required>
                <option value="">Select District</option>

                @foreach($districts as $district)
                    <option value="{{ $district->id }}"
                        {{ $tehsil->district_id == $district->id ? 'selected' : '' }}>
                        {{ $district->name }}
                    </option>
                @endforeach
            </select>

            @error('district_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Tehsil Name</label>
            <input type="text"
                   name="name"
                   value="{{ $tehsil->name }}"
                   class="form-control"
                   required>

            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-primary">Update</button>

        <a href="{{ route('tehsils.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </form>
</div>
@endsection