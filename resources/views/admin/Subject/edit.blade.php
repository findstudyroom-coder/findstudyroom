@extends('admin.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Edit Subject</h4>
        </div>

        <div class="card-body">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('Subject.update', $Subject->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Subject Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $Subject->name) }}"
                        placeholder="Enter subject name"
                        required
                    >

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('Subject.index') }}" class="btn btn-secondary">
                        Back
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Update Subject
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection