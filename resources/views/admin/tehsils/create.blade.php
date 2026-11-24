@extends('admin.master')

@section('content')
<div class="container">
    <h2>Add Tehsil</h2>

    <form action="{{ route('tehsils.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>District</label>
            <select name="district_id" class="form-control" required>
                <option value="">Select District</option>

                @foreach($districts as $district)
                    <option value="{{ $district->id }}">
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
                   class="form-control"
                   required>

            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-success">Save</button>

        <a href="{{ route('tehsils.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </form>
</div>
@endsection