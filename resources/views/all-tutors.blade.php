@extends('master')

@php
use App\Services\ImageService;
@endphp

@section('title', 'All Tutors | Findstudyroom')
@section('meta_description', 'Browse all verified tutors on Findstudyroom. Find the right tutor for board exams, JEE, NEET, and more.')
@section('meta_keywords', 'all tutors, tutor listing, board exam tutor, JEE tutor, NEET tutor, local tutor')

@section('content')
<section class="py-16 px-6 bg-gray-50">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
      <div>
        <h1 class="text-4xl font-bold text-gray-900">All Tutors</h1>
        <p class="mt-3 text-gray-500 max-w-2xl">Discover verified tutors for all subjects, boards and exam preparation.</p>
      </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
      @forelse($tutors as $tutor)
      <a href="{{ url('/tutor/'.$tutor->public_slug) }}" class="group block overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
        <div class="h-56 overflow-hidden bg-gray-100">
          <img src="{{ $tutor->image ? asset('public/uploads/tutors/'.$tutor->image) : ImageService::getTutorImage() }}" alt="{{ $tutor->full_name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        </div>
        <div class="p-5">
          <h2 class="text-xl font-semibold text-gray-900">{{ $tutor->full_name }}</h2>
          <p class="text-sm text-gray-500 mt-2">{{ implode(', ', array_slice($tutor->subject ?? [], 0, 3)) ?: 'Subject details not available' }}</p>
          <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-gray-600">
            <span>₹{{ $tutor->hourly_rate ?? 'N/A' }}/hr</span>
            <span>·</span>
            <span>{{ $tutor->experience_years ? $tutor->experience_years.' yrs experience' : 'Experience not listed' }}</span>
          </div>
        </div>
      </a>
      @empty
      <div class="col-span-full rounded-3xl bg-white p-10 text-center text-gray-500 shadow-sm">
        No tutors found yet.
      </div>
      @endforelse
    </div>
  </div>
</section>
@endsection
