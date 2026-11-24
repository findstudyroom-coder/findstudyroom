@extends('master')

@php
$contactSchema = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'WebPage',
            'name' => 'Contact Findstudyroom',
            'description' => 'Contact Findstudyroom for support with finding tutors, libraries, and more.',
            'url' => url('/contact-us'),
            'breadcrumb' => [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => url('/'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Contact Us',
                        'item' => url('/contact-us'),
                    ],
                ],
            ],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => url('/search?q={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ],
        ],
        [
            '@type' => 'Organization',
            '@id' => url('/#organization'),
            'name' => 'Findstudyroom',
            'url' => url('/'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'Customer Support',
                'email' => 'findstudyroom@gmail.com',
                'telephone' => '+91-8386992953',
                'areaServed' => 'IN',
                'availableLanguage' => ['en', 'hi'],
            ],
        ],
        [
            '@type' => 'ContactPage',
            'name' => 'Contact Findstudyroom',
            'description' => 'Contact Findstudyroom for support with finding tutors, libraries, and more.',
            'url' => url('/contact-us'),
            'mainEntity' => [
                '@id' => url('/#organization'),
            ],
        ],
    ],
];
@endphp

@section('title', 'Contact Us | Findstudyroom - Get Support')
@section('meta_description', 'Contact Findstudyroom for support. Reach out with questions about tutors, libraries, or listing your business.')
@section('meta_keywords', 'contact us, support, findstudyroom contact, customer service')
@section('og_title', 'Contact Findstudyroom')
@section('og_description', 'Get in touch with our support team. We are here to help you find the best tutors and study spaces.')
@section('twitter_title', 'Contact Findstudyroom')
@section('twitter_description', 'Reach out to Findstudyroom support team.')

@push('head')
<script type="application/ld+json">
{!! json_encode($contactSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<section class="pt-24 pb-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-6">
    <div class="grid gap-10 lg:grid-cols-2 items-center">
      <div class="space-y-6">
        <span class="inline-flex items-center rounded-full bg-teal-50 px-4 py-2 text-sm font-semibold text-teal-700">Get in Touch</span>
        <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl">We're here to help you succeed</h1>
        <p class="text-lg text-gray-600 leading-relaxed">Have questions about finding the right tutor or study space? Want to list your coaching institute? We're always excited to hear from our community.</p>
        <div class="grid gap-4 sm:grid-cols-2">
          <div class="rounded-3xl bg-white p-6 shadow-sm border border-gray-200">
            <div class="text-2xl mb-2">📧</div>
            <h2 class="text-lg font-semibold text-gray-900">Email Support</h2>
            <p class="mt-2 text-sm text-gray-600">findstudyroom@gmail.com</p>
          </div>
          <div class="rounded-3xl bg-white p-6 shadow-sm border border-gray-200">
            <div class="text-2xl mb-2">📱</div>
            <h2 class="text-lg font-semibold text-gray-900">Phone Support</h2>
            <p class="mt-2 text-sm text-gray-600">+91 83869 92953</p>
          </div>
        </div>
      </div>
      <div class="relative">
        <div class="overflow-hidden rounded-[2rem] shadow-2xl">
          <img src="https://plus.unsplash.com/premium_photo-1675842663249-a8b70103dbaa?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8Y29udGFjdCUyMHVzfGVufDB8fDB8fHww" alt="Contact us" class="h-full w-full object-cover" />
        </div>
      </div>
    </div>

    <div class="mt-16 rounded-[2rem] bg-white p-10 shadow-xl border border-gray-200">
      <div class="grid gap-8 lg:grid-cols-2">
        <div class="space-y-3">
          <p class="text-sm uppercase tracking-[0.2em] text-teal-600">Quick Support</p>
          <h2 class="text-3xl font-semibold text-gray-900">Send us a message</h2>
          <p class="text-gray-600 mt-4">Fill out the form below and we'll get back to you as soon as possible.</p>
        </div>
        <form class="space-y-5" action="{{ route('contact.store') }}" method="POST">
          @csrf
          <div id="contact-success-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/40">
            <div class="flex min-h-screen items-center justify-center p-4">
              <div class="w-full max-w-xl rounded-[2rem] bg-white p-8 shadow-2xl">
                <div class="flex items-start justify-between gap-4">
                  <div>
                    <div class="inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-teal-600 text-white">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5" /></svg>
                    </div>
                  </div>
                  <button type="button" onclick="document.getElementById('contact-success-modal').classList.add('hidden')" class="rounded-full bg-gray-100 p-3 text-gray-600 transition hover:bg-gray-200">✕</button>
                </div>
                <div class="mt-6 text-center">
                  <h3 class="text-2xl font-semibold text-gray-900">Query submitted successfully!</h3>
                  <p class="mt-3 text-gray-600">{{ session('success') }}</p>
                  <button type="button" onclick="document.getElementById('contact-success-modal').classList.add('hidden')" class="mt-8 inline-flex items-center justify-center rounded-full bg-teal-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">Close</button>
                </div>
              </div>
            </div>
          </div>
          @if(session('success'))
            <script>
              window.addEventListener('DOMContentLoaded', function () {
                var modal = document.getElementById('contact-success-modal');
                if (modal) modal.classList.remove('hidden');
              });
            </script>
          @endif
          <div>
            <label for="name" class="block text-sm font-medium text-gray-900 mb-2">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Your name" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
            @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label for="email" class="block text-sm font-medium text-gray-900 mb-2">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="your.email@example.com" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
            @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-900 mb-2">Phone Number</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Your phone number" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
            @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label for="location" class="block text-sm font-medium text-gray-900 mb-2">Subject</label>
            <input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="How can we help?" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
            @error('location') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label for="message" class="block text-sm font-medium text-gray-900 mb-2">Message</label>
            <textarea id="message" name="message" rows="5" placeholder="Tell us more about your inquiry..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all resize-none">{{ old('message') }}</textarea>
            @error('message') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>
          <button type="submit" class="btn-primary w-full px-6 py-3 rounded-xl font-semibold text-white transition-transform hover:scale-105">Send Message</button>
        </form>
      </div>
    </div>

    <div class="mt-16 grid gap-6 lg:grid-cols-3">
      <div class="rounded-[2rem] bg-white p-8 shadow-xl border border-gray-200">
        <div class="text-3xl mb-4">🎯</div>
        <h3 class="text-xl font-semibold text-gray-900">Find Tutors</h3>
        <p class="mt-3 text-gray-600">Need help finding a tutor? Browse our curated list of verified tutors across all subjects and classes.</p>
      </div>
      <div class="rounded-[2rem] bg-white p-8 shadow-xl border border-gray-200">
        <div class="text-3xl mb-4">📚</div>
        <h3 class="text-xl font-semibold text-gray-900">Discover Libraries</h3>
        <p class="mt-3 text-gray-600">Looking for a study space? Find the best coaching institutes and study libraries in your area.</p>
      </div>
      <div class="rounded-[2rem] bg-white p-8 shadow-xl border border-gray-200">
        <div class="text-3xl mb-4">✨</div>
        <h3 class="text-xl font-semibold text-gray-900">List Your Business</h3>
        <p class="mt-3 text-gray-600">Are you a tutor or coach? Get listed on Findstudyroom and reach thousands of students.</p>
      </div>
    </div>

    <!-- <div class="mt-16 rounded-[2rem] bg-gradient-to-r from-teal-600 to-teal-700 p-12 text-white text-center">
      <h2 class="text-3xl font-bold mb-4">Connect with our community</h2>
      <p class="text-teal-50 mb-8 max-w-2xl mx-auto">Join thousands of students and tutors who are already discovering new learning opportunities on Findstudyroom.</p>
      <div class="flex justify-center gap-4 flex-wrap">
        <a href="#" class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 px-6 py-3 rounded-xl transition-colors">
          <span>📧</span> Email
        </a>
        <a href="#" class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 px-6 py-3 rounded-xl transition-colors">
          <span>💬</span> Chat
        </a>
        <a href="#" class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 px-6 py-3 rounded-xl transition-colors">
          <span>📱</span> Phone
        </a>
      </div>
    </div> -->
  </div>
</section>
@endsection
