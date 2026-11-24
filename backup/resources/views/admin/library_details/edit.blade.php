@extends('admin.master')

@section('content')

<div class="container-fluid">

    <div class="card shadow-lg border-0 rounded-4 bg-dark text-white">

        <div class="card-header d-flex justify-content-between align-items-center text-white rounded-top-4"
             style="background: linear-gradient(135deg,#6366f1,#8b5cf6);">
            <h5 class="mb-0">✏️ Edit Library Detail</h5>
            <a href="{{ route('library-details.index') }}" class="btn btn-light btn-sm">Back</a>
        </div>

        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>⚠ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('library-details.update', $detail->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <input type="hidden" name="library_id" id="library_id" value="{{ $detail->library_id }}" readonly>

                    <div class="col-md-12">
                        <label class="form-label">Library Name</label>
                        <input type="text" class="form-control custom-input" value="{{ $detail->library->name ?? '' }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Select Plan</label>
                        <select name="plan_id" id="plan_id" class="form-control custom-input">
                            <option value="">Choose plan</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ old('plan_id', $detail->plan_id) == $plan->id ? 'selected' : '' }}>{{ $plan->name }} ({{ $plan->validity }} days)</option>
                            @endforeach
                        </select>
                    </div>

                    @php
                        $startDate = old('start_date') ?: ($detail->start_date ? \Carbon\Carbon::parse($detail->start_date)->format('Y-m-d') : null);
                        $endDate = old('end_date') ?: ($detail->end_date ? \Carbon\Carbon::parse($detail->end_date)->format('Y-m-d') : null);
                    @endphp

                    @if($startDate || $endDate)
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control custom-input" value="{{ $startDate }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control custom-input" value="{{ $endDate }}">
                        </div>
                    @endif

                    <div class="col-md-4">
                        <label class="form-label">Owner Name</label>
                        <input type="text" name="name" id="library_name" class="form-control custom-input" value="{{ old('name', $detail->name) }}" >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" id="library_location" class="form-control custom-input" value="{{ old('location', $detail->location) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" id="library_phone" class="form-control custom-input" value="{{ old('phone', $detail->phone) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control custom-input" value="{{ old('capacity', $detail->capacity) }}" min="0">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Logo URL</label>
                        <input type="text" name="logo_url" class="form-control custom-input" value="{{ old('logo_url', $detail->logo_url) }}" placeholder="https://example.com/logo.png">
                    </div>

                </div>

                <div class="text-end mt-4">
                    <button class="btn btn-success px-4 shadow">💾 Update</button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection

