@extends('master')

@php
$aboutSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'AboutPage',
    'mainEntity' => [
        '@type' => 'Organization',
        'name' => 'Findstudyroom',
        'url' => url('/'),
        'logo' => asset('public/uploads/fav.svg'),
    'description' => 'Findstudyroom connects learners with verified tutors, coaching institutes, and study libraries across India.',
        'sameAs' => [
            'https://www.facebook.com/findstudyroom',
            'https://twitter.com/findstudyroom',
            'https://www.instagram.com/findstudyroom'
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'contactType' => 'Customer Support',
            'email' => 'support@findstudyroom.com'
        ]
    ]
];
@endphp

@section('title', 'About Us | Findstudyroom - Find Best Tutors & Study Libraries')
@section('meta_description', 'Learn about Findstudyroom. We connect students with verified tutors and study libraries across India. Find your perfect tutor or study space today.')
@section('meta_keywords', 'about findstudyroom, find tutors, study libraries, coaching institutes, verified tutors, education platform')
@section('og_title', 'About Findstudyroom - Connecting Students & Tutors')
@section('og_description', 'Discover how Findstudyroom helps students find the best tutors and study libraries across India.')
@section('twitter_title', 'About Findstudyroom')
@section('twitter_description', 'Connecting students with verified tutors and study spaces.')

@push('head')
<script type="application/ld+json">
{!! json_encode($aboutSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<section class="pt-24 pb-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-6">
    <div class="grid gap-10 lg:grid-cols-2 items-center">
      <div class="space-y-6">
        <span class="inline-flex items-center rounded-full bg-teal-50 px-4 py-2 text-sm font-semibold text-teal-700">{{ $about->subtitle ?? 'About Findstudyroom' }}</span>
        <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl">{{ $about->title ?? 'Helping students find the right tutors and study spaces' }}</h1>
        <p class="text-lg text-gray-600 leading-relaxed">{!! $about->description ?? 'Findstudyroom connects learners with verified tutors, coaching institutes, and study libraries across India. We make discovery simple, transparent, and fast.' !!}</p>
        <div class="grid gap-4 sm:grid-cols-2">
          <div class="rounded-3xl bg-white p-6 shadow-sm border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Instant discovery</h2>
            <p class="mt-2 text-sm text-gray-600">Search tutors, libraries and courses in one place.</p>
          </div>
          <div class="rounded-3xl bg-white p-6 shadow-sm border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Verified choices</h2>
            <p class="mt-2 text-sm text-gray-600">Access trusted profiles and study spaces curated for students.</p>
          </div>
        </div>
      </div>
      <div class="relative">
        <div class="overflow-hidden rounded-[2rem] shadow-2xl">
          @php
            $images = json_decode($about->images ?? '[]');
            $img = '';
            if (!empty($images) && is_array($images)) {
                $img = asset('storage/' . $images[0]);
            }
          @endphp
          <img src="{{ $img ?: 'https://plus.unsplash.com/premium_photo-1668383778557-d71c562fdb4b?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YWJvdXQlMjB1c3xlbnwwfHwwfHx8MA%3D%3D' }}"
               alt="{{ $about->title ?? 'About Findstudyroom' }}"
               title="{{ $about->title ?? 'About Findstudyroom - Platform for Libraries &amp; Students' }}"
               class="h-full w-full object-cover"
               loading="lazy" />
        </div>
      </div>
    </div>

    <div class="mt-16 grid gap-6 lg:grid-cols-3">
      <div class="rounded-[2rem] bg-white p-8 shadow-xl border border-gray-200">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-teal-50 text-teal-700 mb-5">1</div>
        <h3 class="text-xl font-semibold text-gray-900">Our mission</h3>
        <p class="mt-3 text-gray-600">Make learning resources accessible for every student, regardless of where they live.</p>
      </div>
      <div class="rounded-[2rem] bg-white p-8 shadow-xl border border-gray-200">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-coral-50 text-coral-700 mb-5">2</div>
        <h3 class="text-xl font-semibold text-gray-900">What we do</h3>
        <p class="mt-3 text-gray-600">Bring together tutors, libraries, and exam coaches under one easy-to-use platform.</p>
      </div>
      <div class="rounded-[2rem] bg-white p-8 shadow-xl border border-gray-200">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-blue-50 text-blue-700 mb-5">3</div>
        <h3 class="text-xl font-semibold text-gray-900">How we help</h3>
        <p class="mt-3 text-gray-600">Provide better search, reliable profile details, and fast contact between students and educators.</p>
      </div>
    </div>

    <div class="mt-16 rounded-[2rem] bg-white p-10 shadow-xl border border-gray-200">
      <div class="grid gap-8 lg:grid-cols-3">
        <div class="space-y-3">
          <p class="text-sm uppercase tracking-[0.2em] text-teal-600">Growth</p>
          <h2 class="text-3xl font-semibold text-gray-900">Thousands of success stories</h2>
        </div>
        <div class="col-span-2 space-y-4 text-gray-600">
          <p>{!! $about->description_two ?? 'Students and tutors use Findstudyroom to connect faster, study smarter, and achieve academic goals. Whether you need help with board exams, competitive entrance tests, or daily tuition, we help you find the right match.' !!}</p>
          <div class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-3xl bg-gray-50 p-5">
              <p class="text-sm text-gray-500">Verified tutor profiles</p>
              <p class="mt-3 text-xl font-semibold text-gray-900">Reliable choices</p>
            </div>
            <div class="rounded-3xl bg-gray-50 p-5">
              <p class="text-sm text-gray-500">Library & coaching listings</p>
              <p class="mt-3 text-xl font-semibold text-gray-900">Trusted study spaces</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
