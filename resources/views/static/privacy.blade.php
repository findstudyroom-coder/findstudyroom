@extends('master')

@php
$privacySchema = [
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    'name' => 'Privacy Policy - Findstudyroom',
    'description' => 'Findstudyroom Privacy Policy - Learn how we collect, use, and protect your personal data.',
    'url' => url('/privacy-policy'),
    'mainEntity' => [
        '@type' => 'PrivacyPolicy',
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Findstudyroom',
            'url' => url('/')
        ]
    ]
];
@endphp

@section('title', 'Privacy Policy | Findstudyroom - Data Protection & Security')
@section('meta_description', 'Findstudyroom Privacy Policy. Understand how we protect your data, what we collect, and your privacy rights.')
@section('meta_keywords', 'privacy policy, data protection, personal information, security, findstudyroom')
@section('og_title', 'Privacy Policy - Findstudyroom')
@section('og_description', 'Your privacy is our priority. Read how Findstudyroom protects your personal data.')
@section('twitter_title', 'Privacy Policy - Findstudyroom')
@section('twitter_description', 'Learn how we protect your privacy and data.')

@push('head')
<script type="application/ld+json">
{!! json_encode($privacySchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<section class="pt-24 pb-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-6">
    <div class="grid gap-10 lg:grid-cols-2 items-center rounded-[2rem] bg-white p-10 shadow-2xl border border-gray-200">
      <div>
        <span class="inline-flex items-center rounded-full bg-teal-50 px-4 py-2 text-sm font-semibold text-teal-700">Privacy Policy</span>
        <h1 class="mt-6 text-4xl font-bold text-gray-900 sm:text-5xl">Your privacy is safe with us</h1>
        <p class="mt-6 text-lg text-gray-600 leading-relaxed">We collect only the data needed to deliver a great experience and protect it with strong safeguards. Your trust is our top priority.</p>
        <div class="mt-8 grid gap-4 sm:grid-cols-2">
          <div class="rounded-3xl bg-gray-50 p-6 border border-gray-200">
            <h2 class="font-semibold text-gray-900">Minimal data</h2>
            <p class="mt-2 text-gray-600 text-sm">We ask only for what helps you find tutors and libraries.</p>
          </div>
          <div class="rounded-3xl bg-gray-50 p-6 border border-gray-200">
            <h2 class="font-semibold text-gray-900">No resale</h2>
            <p class="mt-2 text-gray-600 text-sm">Personal details are never sold to third parties.</p>
          </div>
        </div>
      </div>
      <div class="overflow-hidden rounded-[2rem] shadow-xl">
        <img src="https://plus.unsplash.com/premium_photo-1677093905933-22469186a34b?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cHJpdmFjeSUyMHBvbGljeXxlbnwwfHwwfHx8MA%3D%3D"
             alt="Privacy Policy and Data Protection"
             title="Privacy Policy - Findstudyroom"
             class="h-full w-full object-cover"
             loading="lazy" />
      </div>
    </div>

    <div class="mt-16 grid gap-6 lg:grid-cols-3">
      <div class="rounded-[2rem] bg-white p-8 shadow-xl border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">What we collect</h2>
        <p class="mt-4 text-gray-600">Profile information, contact details, search preferences, and booking history.</p>
      </div>
      <div class="rounded-[2rem] bg-white p-8 shadow-xl border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">How we use it</h2>
        <p class="mt-4 text-gray-600">To improve search, personalize recommendations, and complete tutor or library bookings.</p>
      </div>
      <div class="rounded-[2rem] bg-white p-8 shadow-xl border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Your control</h2>
        <p class="mt-4 text-gray-600">You can request updates or removal of your information at any time.</p>
      </div>
    </div>

    <div class="mt-16 rounded-[2rem] bg-gradient-to-r from-teal-600 to-cyan-500 p-10 text-white shadow-2xl">
      <h2 class="text-3xl font-semibold">Security and trust</h2>
      <p class="mt-4 text-gray-100 leading-relaxed">We use secure systems and best practices to protect your data. Findstudyroom is designed for safe browsing and trusted connections between students and educators.</p>
      <div class="mt-8 grid gap-4 sm:grid-cols-2">
        <div class="rounded-3xl bg-white/10 p-6">
          <p class="font-semibold">Encrypted data</p>
          <p class="mt-2 text-sm text-gray-200">Sensitive information is stored securely.</p>
        </div>
        <div class="rounded-3xl bg-white/10 p-6">
          <p class="font-semibold">Responsible use</p>
          <p class="mt-2 text-sm text-gray-200">We do not share information without consent.</p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
