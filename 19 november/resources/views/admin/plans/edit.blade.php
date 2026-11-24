
@extends('admin.master')

@section('content')

<div class="container-fluid">

    <div class="card shadow-lg border-0 rounded-4 bg-dark text-white">

        <div class="card-header d-flex justify-content-between align-items-center text-white rounded-top-4"
             style="background: linear-gradient(135deg,#6366f1,#8b5cf6);">
            <h5 class="mb-0">✏️ Edit Plan</h5>
            <a href="{{ route('plans.index') }}" class="btn btn-light btn-sm">Back</a>
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

            <form action="{{ route('plans.update', $plan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" value="{{ old('name', $plan->name) }}" class="form-control custom-input" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Amount (₹)</label>
                        <input type="number" name="amount" value="{{ old('amount', $plan->amount) }}" class="form-control custom-input" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Validity (days)</label>
                        <input type="number" name="validity" value="{{ old('validity', $plan->validity) }}" class="form-control custom-input">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Whats Text Limit</label>
                        <input type="number" name="whats_text_limit" value="{{ old('whats_text_limit', $plan->whats_text_limit) }}" class="form-control custom-input">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Text Limit</label>
                        <input type="number" name="text_limit" value="{{ old('text_limit', $plan->text_limit) }}" class="form-control custom-input">
                    </div>

                </div>

                <div class="text-end mt-4">
                    <button class="btn btn-success px-4 shadow">💾 Update Plan</button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection
