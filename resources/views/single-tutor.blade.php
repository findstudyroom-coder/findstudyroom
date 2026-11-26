@extends('master')

@php
use App\Services\ImageService;
@endphp

@section('title', $tutor->full_name . ' | Tutor Profile | Findstudyroom')
@section('meta_description', 'View the profile of ' . $tutor->full_name . ', an experienced tutor for ' . implode(', ', array_slice($tutor->subject ?? [], 0, 4)) . '.')
@section('meta_keywords', $tutor->full_name . ', tutor profile, ' . implode(', ', array_slice($tutor->subject ?? [], 0, 5)))

@section('content')
<section class="py-16 px-6 bg-gray-50">
  <div class="max-w-6xl mx-auto grid gap-10 lg:grid-cols-3">

    <div class="lg:col-span-2 space-y-8">
      <div class="bg-white rounded-3xl p-8 shadow-sm">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $tutor->full_name }}</h1>
            <p class="mt-3 text-gray-500">Experienced tutor in {{ implode(', ', $tutor->subject ?? ['Multiple subjects']) }}</p>
          </div>
          <div class="inline-flex items-center gap-3 rounded-full bg-teal-100 px-4 py-2 text-sm font-semibold text-teal-700">
            ₹{{ $tutor->hourly_rate ?? 'N/A' }}/hr
          </div>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2">
          <div class="rounded-3xl bg-gray-50 p-5">
            <h2 class="font-semibold text-gray-900">Experience</h2>
            <p class="mt-2 text-gray-600">{{ $tutor->experience_years ? $tutor->experience_years.' years' : 'Not specified' }}</p>
          </div>
          <div class="rounded-3xl bg-gray-50 p-5">
            <h2 class="font-semibold text-gray-900">Total Students</h2>
            <p class="mt-2 text-gray-600">{{ $tutor->total_students ?? 'N/A' }}</p>
          </div>
        </div>

        <div class="mt-8">
          <h2 class="text-xl font-semibold text-gray-900">About</h2>
          <p class="mt-4 text-gray-600 leading-relaxed">{{ $tutor->bio ?? 'No biography has been provided yet.' }}</p>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-2">
          <div class="rounded-3xl bg-gray-50 p-5">
            <h3 class="font-semibold text-gray-900">Subjects</h3>
            <div class="mt-3 flex flex-wrap gap-2">
              @foreach($tutor->subject ?? [] as $subject)
                <span class="rounded-full bg-indigo-100 px-3 py-1 text-sm text-indigo-700">{{ $subject }}</span>
              @endforeach
              @if(empty($tutor->subject))
                <span class="text-gray-400">Not listed</span>
              @endif
            </div>
          </div>
          <div class="rounded-3xl bg-gray-50 p-5">
            <h3 class="font-semibold text-gray-900">Classes</h3>
            <div class="mt-3 flex flex-wrap gap-2">
              @foreach($tutor->classes ?? [] as $class)
                <span class="rounded-full bg-teal-100 px-3 py-1 text-sm text-teal-700">{{ $class }}</span>
              @endforeach
              @if(empty($tutor->classes))
                <span class="text-gray-400">Not listed</span>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <aside class="space-y-6">
      <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
        <img src="{{ $tutor->image ? asset('public/uploads/tutors/'.$tutor->image) : ImageService::getTutorImage() }}"
             alt="{{ $tutor->full_name }} - Expert Tutor on Findstudyroom"
             title="{{ $tutor->full_name }} - Tutor Profile"
             class="h-80 w-full object-cover"
             loading="lazy">
      </div>

      <div class="rounded-3xl bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-gray-900">Contact Info</h2>
        <div class="mt-4 space-y-3 text-sm text-gray-600">
          <div><span class="font-semibold text-gray-800">Phone:</span> {{ $tutor->phone ?? 'Not provided' }}</div>
          <div><span class="font-semibold text-gray-800">Email:</span> {{ $tutor->email ?? 'Not provided' }}</div>
          <div><span class="font-semibold text-gray-800">Location:</span> {{ $tutor->location_area ?? $tutor->location ?? 'Not specified' }}</div>
        </div>
      </div>

      <div class="rounded-3xl bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-gray-900">Availability</h2>
        <p class="mt-3 text-gray-600">{{ $tutor->availability ? 'Available now' : 'Currently unavailable' }}</p>
      </div>
    </aside>
  </div>
</section>
@endsection
