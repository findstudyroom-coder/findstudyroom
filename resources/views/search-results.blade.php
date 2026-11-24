@extends('master')

@php
use App\Services\ImageService;
@endphp

@section('title', 'Search results | Findstudyroom')
@section('meta_description', 'Search tutors and libraries on Findstudyroom. Find expert tutors, coaching centers, and study spaces near you.')
@section('meta_keywords', 'search tutors, search libraries, tutor results, library results, find tutors, find libraries')

@section('content')
<section class="pt-24 pb-16 px-6 bg-gray-50">
  <div class="max-w-7xl mx-auto">
    <div class="mb-8">
      <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700">
        ← Back to home
      </a>
      <h1 class="mt-6 text-4xl font-bold text-gray-900">Search results</h1>
      <p class="mt-3 text-gray-500">Showing the best matching tutors and libraries for <span class="font-semibold text-gray-900">"{{ $query }}"</span>.</p>
    </div>

    <div class="grid gap-10 lg:grid-cols-2">
      <div>
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="text-2xl font-semibold text-gray-900">Libraries</h2>
            <p class="text-sm text-gray-500">Top library results matching your query.</p>
          </div>
          <span class="text-sm text-gray-600">{{ $libraries->count() }} results</span>
        </div>

        @if($libraries->isEmpty())
          <div class="rounded-3xl bg-white p-10 text-center text-gray-500 shadow-sm">No libraries matched your search.</div>
        @else
          <div class="grid gap-6">
            @foreach($libraries as $lib)
              <a href="{{ route('library.details', $lib->slug) }}" class="group block overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex gap-4">
                  <div class="h-32 w-32 flex-shrink-0 overflow-hidden bg-gray-200">
                    <img src="{{ !empty($lib->images) && count($lib->images) > 0 ? asset('storage/app/public/'.$lib->images[0]) : ImageService::getLibraryImage() }}" onerror="this.onerror=null;this.src='{{ ImageService::getLibraryImage() }}'" alt="{{ $lib->name }}" class="h-full w-full object-cover group-hover:scale-105 transition" />
                  </div>
                  <div class="flex flex-col justify-between p-4 flex-1">
                    <div>
                      <h3 class="text-lg font-semibold text-gray-900">{{ $lib->name }}</h3>
                      <p class="mt-1 text-sm text-gray-500">📍 {{ $lib->location }} · {{ $lib->pincode }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                      <span>{{ $lib->price ? '₹'.$lib->price.'/month' : 'Price coming soon' }}</span>
                      <span>·</span>
                      <span>⭐ {{ $lib->rating ?? 'N/A' }}</span>
                      <span class="ml-auto rounded-full bg-teal-50 px-2 py-1 text-xs font-semibold text-teal-700">{{ ucfirst($lib->status ?? 'Open') }}</span>
                    </div>
                  </div>
                </div>
              </a>
            @endforeach
          </div>
        @endif
      </div>

      <div>
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="text-2xl font-semibold text-gray-900">Tutors</h2>
            <p class="text-sm text-gray-500">Verified tutors matching your search.</p>
          </div>
          <span class="text-sm text-gray-600">{{ $tutors->count() }} results</span>
        </div>

        @if($tutors->isEmpty())
          <div class="rounded-3xl bg-white p-10 text-center text-gray-500 shadow-sm">No tutors matched your search.</div>
        @else
          <div class="grid gap-6">
            @foreach($tutors as $tutor)
              <a href="{{ url('/tutor/'.$tutor->public_slug) }}" class="group block overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex gap-4">
                  <div class="h-32 w-32 flex-shrink-0 overflow-hidden bg-gray-200">
                    <img src="{{ $tutor->image ? asset('public/uploads/tutors/'.$tutor->image) : ImageService::getTutorImage() }}" alt="{{ $tutor->full_name }}" class="h-full w-full object-cover group-hover:scale-105 transition" />
                  </div>
                  <div class="flex flex-col justify-between p-4 flex-1">
                    <div>
                      <h3 class="text-lg font-semibold text-gray-900">{{ $tutor->full_name }}</h3>
                      <p class="mt-1 text-sm text-gray-500">{{ implode(', ', array_slice($tutor->subject ?? [], 0, 3)) ?: 'Tutoring available' }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                      <span class="font-semibold text-teal-600">₹{{ $tutor->hourly_rate ?? 'N/A' }}/hr</span>
                      <span>·</span>
                      <span>{{ $tutor->experience_years ? $tutor->experience_years.' yrs exp' : 'Experience not listed' }}</span>
                    </div>
                  </div>
                </div>
              </a>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection
