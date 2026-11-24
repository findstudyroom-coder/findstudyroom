@extends('master')

@section('title', 'Register')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl mb-4">Register</h2>
    @if ($errors->any())
        <div class="bg-red-50 text-red-700 p-3 mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('auth.register.post') }}">
        @csrf
        <div class="mb-3">
            <label class="block text-sm">Full name</label>
            <input name="name" value="{{ old('name') }}" required class="w-full border p-2 rounded" />
        </div>
        <div class="mb-3">
            <label class="block text-sm">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border p-2 rounded" />
        </div>
        <div class="mb-3">
            <label class="block text-sm">Password</label>
            <input type="password" name="password" required class="w-full border p-2 rounded" />
        </div>
        <div class="mb-3">
            <label class="block text-sm">Confirm Password</label>
            <input type="password" name="password_confirmation" required class="w-full border p-2 rounded" />
        </div>
        <div class="mb-3">
            <label class="block text-sm">I am a</label>
            <label class="inline-flex items-center mr-4"><input type="radio" name="role" value="student" checked> <span class="ml-2">Student</span></label>
            <label class="inline-flex items-center"><input type="radio" name="role" value="tutor"> <span class="ml-2">Tutor</span></label>
        </div>
        <div>
            <button class="btn-primary text-white px-4 py-2 rounded">Register</button>
        </div>
    </form>
    <p class="mt-4">Already have an account? <a href="{{ route('auth.login') }}" class="text-accent">Login</a></p>
</div>
@endsection
