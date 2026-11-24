@extends('admin.master')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: 70vh;">
    <div class="text-center">
        <h1 class="display-1 fw-bold text-danger">403</h1>
        <h2 class="text-white mb-4">Access Denied</h2>
        <p class="text-muted mb-4">You do not have the required permissions to access this page or perform this action.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">
            <i class="fas fa-home me-2"></i> Return to Dashboard
        </a>
    </div>
</div>
@endsection
