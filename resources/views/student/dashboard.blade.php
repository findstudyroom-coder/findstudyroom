@extends('master')

@section('title', 'Student Dashboard')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-semibold">Student Dashboard</h1>
    <p class="mt-4">Welcome, {{ auth()->user()->name }}. This is your student area.</p>
    <form method="POST" action="{{ route('auth.logout') }}" class="mt-4">
        @csrf
        <button class="btn-coral text-white px-4 py-2 rounded">Logout</button>
    </form>
</div>
@endsection
