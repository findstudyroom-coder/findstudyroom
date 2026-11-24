@extends('master')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl mb-4">Login</h2>
    @if ($errors->any())
        <div class="bg-red-50 text-red-700 p-3 mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('auth.login.post') }}">
        @csrf
        <div class="mb-3">
            <label class="block text-sm">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border p-2 rounded" />
        </div>
        <div class="mb-3">
            <label class="block text-sm">Password</label>
            <input type="password" name="password" required class="w-full border p-2 rounded" />
        </div>
        <div class="mb-3">
            <label class="inline-flex items-center"><input type="checkbox" name="remember"> <span class="ml-2">Remember me</span></label>
        </div>
        <div>
            <button class="btn-primary text-white px-4 py-2 rounded">Login</button>
        </div>
    </form>
    <p class="mt-4">Don't have an account? <a href="{{ route('auth.register') }}" class="text-accent">Register</a></p>
</div>
@endsection
