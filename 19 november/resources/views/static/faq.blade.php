@extends('master')

@php
$faqItems = [
  [
    'question' => 'How do I book a tutor?',
    'answer' => 'Search for tutors by subject or location, view their profile, and contact them to arrange sessions. Each listing includes availability and hourly rates when available.'
  ],
  [
    'question' => 'Can I book a study library through the site?',
    'answer' => 'Yes. Browse library listings, open the library page, and use the contact or booking details provided to reserve a study space.'
  ],
  [
    'question' => 'Are the tutors verified?',
    'answer' => 'We highlight tutors with verified profiles and clear subject expertise. Always review the tutor profile, experience, and ratings before booking.'
  ],
  [
    'question' => 'How do I update my booking or contact support?',
    'answer' => 'Use the contact information available on the tutor or library profile, or get in touch through our website contact page for general support.'
  ],
  [
    'question' => 'Is my personal information secure?',
    'answer' => 'Yes. We use secure data practices and do not share your information without your consent. Read our privacy policy for full details.'
  ],
];

$faqSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(function($item) {
        return [
            '@type' => 'Question',
            'name' => $item['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $item['answer']
            ]
        ];
    }, $faqItems)
];
@endphp

@section('title', 'FAQ - Findstudyroom | Find Tutors & Libraries')
@section('meta_description', 'Frequently asked questions about Findstudyroom. Get answers about booking tutors, finding study libraries, and more.')
@section('meta_keywords', 'frequently asked questions, faq, how to book tutor, study library, findstudyroom help')
@section('og_title', 'FAQ - Findstudyroom')
@section('og_description', 'Get answers to common questions about finding tutors and study libraries.')
@section('twitter_title', 'FAQ - Findstudyroom')
@section('twitter_description', 'Frequently asked questions about Findstudyroom.')

@push('head')
<script type="application/ld+json">
{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<section class="pt-24 pb-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-6">
    <div class="grid gap-10 lg:grid-cols-[1.2fr_0.8fr] items-center">
      <div class="space-y-6 rounded-[2rem] bg-white p-10 shadow-2xl border border-gray-200">
        <span class="inline-flex items-center rounded-full bg-teal-50 px-4 py-2 text-sm font-semibold text-teal-700">FAQ</span>
        <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl">Frequently asked questions</h1>
        <p class="text-lg text-gray-600 leading-relaxed">Get quick answers about booking tutors, finding libraries, and using Findstudyroom effectively.</p>
      </div>
      <div class="overflow-hidden rounded-[2rem] shadow-xl">
        <img src="https://img.magnific.com/free-vector/tiny-people-sitting-standing-near-giant-faq_74855-7879.jpg?semt=ais_hybrid&w=740&q=80" alt="FAQ illustration" class="h-full w-full object-cover" />
      </div>
    </div>

    <div class="mt-16 space-y-4">
      @foreach($faqItems as $index => $faq)
        <div class="group overflow-hidden rounded-[2rem] border border-gray-200 bg-white shadow-sm transition hover:shadow-xl">
          <button type="button" class="w-full flex items-center justify-between gap-4 p-6 text-left" onclick="toggleFaq(this)">
            <span class="text-lg font-semibold text-gray-900">{{ $faq['question'] }}</span>
            <span class="text-teal-600 text-2xl transition group-hover:text-teal-700">+</span>
          </button>
          <div class="px-6 pb-6 hidden text-gray-600 leading-relaxed faq-answer">
            <p>{{ $faq['answer'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<script>
function toggleFaq(button) {
  const card = button.closest('div');
  const answer = card.querySelector('.faq-answer');
  const plus = button.querySelector('span.text-teal-600');
  const isOpen = !answer.classList.contains('hidden');

  document.querySelectorAll('.faq-answer').forEach(el => el.classList.add('hidden'));
  document.querySelectorAll('button span.text-teal-600').forEach(el => el.textContent = '+');

  if (isOpen) {
    answer.classList.add('hidden');
    plus.textContent = '+';
  } else {
    answer.classList.remove('hidden');
    plus.textContent = '−';
  }
}
</script>
@endsection
