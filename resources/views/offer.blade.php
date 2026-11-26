@extends('master')

@section('title', 'Special ₹499 Library Owner Listing Offer | FindStudyRoom')
@section('meta_description', 'Full karein apni library ke khali desks! List your library on FindStudyRoom for just ₹499. Reach 1,000+ serious students across India with verified trust badge, Google discoverability, and 1-Year library management software.')
@section('meta_keywords', 'library listing offer, list study room, library owners ₹499, study hall promotion, FindStudyRoom offer, library management app')
@section('og_title', 'Special ₹499 Library Owner Listing Offer | FindStudyRoom')
@section('og_description', 'Showcase your library to thousands of serious aspirants. ₹499 introductory offer includes 1-Year software, verified badge, and Google discoverability.')
@section('og_image', asset('public/uploads/offer-hero-library.jpg'))
@section('twitter_title', 'Special ₹499 Library Owner Listing Offer | FindStudyRoom')
@section('twitter_description', 'Showcase your library to thousands of serious aspirants with FindStudyRoom.')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

<style>
  /* Typography & Theme Tokens for Offer Page */
  .offer-page {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #0b1c30;
    background-color: #f8faff;
  }
  .offer-page .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 500, 'GRAD' 0, 'opsz' 24;
    vertical-align: middle;
  }
  .offer-page .material-symbols-outlined.filled {
    font-variation-settings: 'FILL' 1;
  }

  /* Custom Color Classes matching Design Tokens */
  .bg-fsr-primary { background-color: #001229 !important; }
  .text-fsr-primary { color: #001229 !important; }
  .bg-fsr-primary-container { background-color: #0f2744 !important; }

  .bg-fsr-secondary { background-color: #006c4a !important; }
  .text-fsr-secondary { color: #006c4a !important; }
  .hover-bg-fsr-secondary:hover { background-color: #005a3c !important; }
  .bg-fsr-secondary-container { background-color: #e6f9f0 !important; }
  .text-fsr-secondary-container { color: #00714e !important; }

  .bg-fsr-surface { background-color: #f8faff !important; }
  .bg-fsr-surface-low { background-color: #f0f4fd !important; }
  .bg-fsr-surface-card { background-color: #ffffff !important; }
  .bg-fsr-surface-container { background-color: #e8eefc !important; }

  .text-fsr-muted { color: #526075 !important; }

  /* Rock-solid Dark Gradients and Card Backgrounds */
  .bg-offer-cta-card {
    background-color: #001229 !important;
    background-image: linear-gradient(135deg, #001229 0%, #0c2038 50%, #001229 100%) !important;
    color: #ffffff !important;
  }
  .bg-offer-roi-card {
    background-color: #022c22 !important;
    background-image: linear-gradient(135deg, #022c22 0%, #001229 55%, #020617 100%) !important;
    color: #ffffff !important;
  }
  .bg-offer-app-card {
    background-color: #001229 !important;
    background-image: linear-gradient(135deg, #001229 0%, #0f1f38 60%, #030a14 100%) !important;
    color: #ffffff !important;
  }
  .bg-offer-hero-gradient {
    background-color: #f8faff !important;
    background-image: linear-gradient(180deg, #f0f4fd 0%, #f8faff 50%, #ffffff 100%) !important;
  }

  /* Animated pulse dot */
  .live-dot {
    width: 10px;
    height: 10px;
    background-color: #22c55e;
    border-radius: 50%;
    display: inline-block;
    position: relative;
  }
  .live-dot::after {
    content: '';
    position: absolute;
    inset: -3px;
    border-radius: 50%;
    background-color: rgba(34, 197, 94, 0.45);
    animation: pulseRing 1.8s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  }
  @keyframes pulseRing {
    0% { transform: scale(0.9); opacity: 0.9; }
    50% { transform: scale(1.8); opacity: 0.3; }
    100% { transform: scale(2.4); opacity: 0; }
  }

  /* Smooth FAQ accordion chevron animation */
  .faq-icon-rotate {
    transition: transform 0.25s ease-in-out;
  }
  .rotate-180-active {
    transform: rotate(180deg);
  }

  /* Glassmorphism accents */
  .glass-card {
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(14px);
    border: 1px solid rgba(229, 238, 255, 0.85);
  }

  /* Hover Lift */
  .hover-lift {
    transition: transform 0.25s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.25s ease;
  }
  .hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 32px -8px rgba(0, 18, 41, 0.12);
  }

  /* Gradient border highlight */
  .gradient-border-glow {
    position: relative;
    border-radius: 1.5rem;
  }
  .gradient-border-glow::before {
    content: '';
    position: absolute;
    inset: -2px;
    border-radius: 1.6rem;
    background: linear-gradient(135deg, #10b981, #f59e0b, #06b6d4);
    z-index: -1;
    opacity: 0.8;
  }

  /* Countdown box */
  .countdown-box {
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(8px);
  }

  /* Sticky floating bar animation */
  #stickyFloatingBar {
    transition: transform 0.3s ease, opacity 0.3s ease;
  }
  #stickyFloatingBar.hidden-bar {
    transform: translateY(120%);
    opacity: 0;
    pointer-events: none;
  }
</style>
@endpush

@section('content')
<div class="offer-page w-full min-h-screen">

  <!-- 1. TOP URGENCY ANNOUNCEMENT & DYNAMIC COUNTDOWN BAR -->
  <aside aria-label="Limited time announcement" class="bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 text-white py-2.5 px-4 shadow-sm">
    <div class="max-w-[1240px] mx-auto flex items-center justify-between flex-wrap gap-2 text-xs sm:text-sm font-semibold">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-white text-base animate-pulse">bolt</span>
        <span>⚡ <strong>Special Introductory Window:</strong> List your library across India for just <span class="bg-white text-orange-700 px-2 py-0.5 rounded-full text-xs sm:text-sm font-extrabold shadow-sm">₹499</span> (Save ₹2,700 today!)</span>
      </div>
      <div class="flex items-center gap-3">
        <!-- Live Countdown display -->
        <div class="hidden md:flex items-center gap-1 text-[11px] font-mono bg-black/20 px-2.5 py-1 rounded-lg">
          <span class="text-amber-200">Ends in:</span>
          <span id="countdownDays">02</span>d :
          <span id="countdownHours">14</span>h :
          <span id="countdownMins">38</span>m :
          <span id="countdownSecs">45</span>s
        </div>
        <a href="https://wa.me/918386992953?text=Hi%20FindStudyRoom%20team!%20I%20want%20to%20list%20my%20library%20under%20the%20%E2%82%B9499%20introductory%20offer.%20Please%20share%20the%20package%20details." target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 bg-white text-orange-700 hover:bg-orange-50 px-3 py-1 rounded-full text-xs font-extrabold shadow-sm transition-transform hover:scale-105">
          <span>Claim on WhatsApp</span>
          <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
        </a>
      </div>
    </div>
  </aside>

  <!-- 2. HERO SECTION -->
  <section class="relative overflow-hidden bg-offer-hero-gradient pt-8 sm:pt-14 pb-14 sm:pb-20" style="background-color: #f8faff !important; background-image: linear-gradient(180deg, #f0f4fd 0%, #f8faff 50%, #ffffff 100%) !important;">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Slots Progress & Trust Pill -->
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-900 px-3.5 py-1.5 rounded-full shadow-sm text-xs font-bold">
          <span class="w-2 h-2 rounded-full bg-emerald-600 animate-ping"></span>
          <span>SPECIAL INTRODUCTORY OFFER FOR LIBRARY OWNERS</span>
        </div>
        <!-- Progress of remaining slots -->
        <div class="flex items-center gap-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 px-3.5 py-1.5 rounded-full shadow-sm">
          <span class="text-orange-600">🔥 36 of 50 Slots Claimed</span>
          <div class="w-16 h-2 bg-slate-100 rounded-full overflow-hidden">
            <div class="w-[72%] h-full bg-gradient-to-r from-orange-500 to-amber-500 rounded-full"></div>
          </div>
          <span class="text-emerald-700 font-extrabold">Only 14 Left</span>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
        <!-- Left Hero Column -->
        <div class="lg:col-span-7 space-y-6">
          <div class="space-y-4">
            <h1 class="text-3xl sm:text-5xl lg:text-[50px] font-black text-fsr-primary tracking-tight leading-[1.12]">
              Full Karein Apni Library Ke Khali Desks
            </h1>
            <p class="text-lg sm:text-xl font-bold text-emerald-800">
              Get Your Study Space Discovered by 1,000+ Serious Aspirants in Your Area
            </p>
            <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl">
              UPSC, NEET, JEE, aur CA aspirants ab sadkon par nahi, Google par silent study space search karte hain. FindStudyRoom par apni library list karein, verified badge paayein, aur direct calls &amp; WhatsApp inquiries receive karein — <strong>0% Platform Commission!</strong>
            </p>
          </div>

          <!-- Highlight Pricing Box -->
          <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-sm border border-slate-200/90 border-l-[6px] border-l-emerald-600 space-y-3 relative overflow-hidden">
            <div class="flex flex-wrap items-baseline gap-3">
              <span class="text-xl text-slate-400 line-through font-semibold">₹2,999</span>
              <span class="text-4xl sm:text-5xl font-black text-emerald-700 tracking-tight">₹499</span>
              <span class="bg-amber-100 text-amber-900 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wide border border-amber-200">
                90% OFF • Full 1-Year Access
              </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-600 font-medium">
              ⚡ <strong>Sirf 82 Paise Per Day:</strong> Includes 1-Year Library Management Software + Google SEO + 10 Marketing Posters. No monthly AMC bills!
            </p>
          </div>

          <!-- Action Buttons -->
          <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5 pt-2">
            <a href="https://wa.me/918386992953?text=Hi%20FindStudyRoom%20team!%20I%20want%20to%20list%20my%20library%20under%20the%20%E2%82%B9499%20introductory%20offer.%20Please%20share%20the%20package%20details%20and%20registration%20process." target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2.5 bg-emerald-700 hover:bg-emerald-800 text-white px-7 py-4 rounded-xl text-base font-bold shadow-lg shadow-emerald-700/25 hover:shadow-xl transition-all transform hover:-translate-y-0.5">
              <span class="material-symbols-outlined text-[24px]">chat</span>
              <span>List Your Library — Enquire on WhatsApp</span>
            </a>
            <a href="#package-benefits" class="inline-flex items-center justify-center gap-1.5 bg-white hover:bg-slate-50 text-slate-800 border border-slate-200 px-6 py-4 rounded-xl text-sm font-semibold shadow-sm transition-colors">
              <span>Explore Package Benefits</span>
              <span class="material-symbols-outlined text-[18px]">arrow_downward</span>
            </a>
          </div>

          <!-- Quick Trust Proof Counter -->
          <div class="pt-2 flex flex-wrap items-center gap-y-2.5 gap-x-5 text-slate-600 text-xs sm:text-sm font-medium">
            <div class="flex items-center gap-1.5">
              <span class="material-symbols-outlined text-emerald-600 text-[20px] filled">check_circle</span>
              <span>Top Hubs: Delhi, Kota, Pune, Patna &amp; Jaipur</span>
            </div>
            <div class="flex items-center gap-1.5">
              <span class="material-symbols-outlined text-emerald-600 text-[20px] filled">verified_user</span>
              <span>100% Genuine Student Inquiries</span>
            </div>
            <div class="flex items-center gap-1.5">
              <span class="material-symbols-outlined text-emerald-600 text-[20px] filled">speed</span>
              <span>&lt; 15 Min Instant WhatsApp Onboarding</span>
            </div>
          </div>
        </div>

        <!-- Right Visual Showcase -->
        <div class="lg:col-span-5 relative">
          <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-slate-900 border border-slate-200/50 group">
            <img src="https://www.cornell.edu/academics/assets/img/library/2024_1357_SH_0214-N.jpg" 
                 alt="Modern Indian study library reading hall with wooden cubicles, individual desk lamps, and focused students" 
                 title="Modern Study Library - Findstudyroom Exclusive Offer"
                 class="w-full h-[380px] sm:h-[460px] object-cover object-center group-hover:scale-105 transition-transform duration-700 ease-out" 
                 loading="lazy">

            <!-- Gradient bottom shade for text legibility -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-transparent to-black/20 pointer-events-none"></div>

            <!-- Top Left Floating Badge -->
            <div class="absolute top-4 left-4 glass-card px-4 py-2 rounded-2xl shadow-lg flex items-center gap-2.5">
              <span class="material-symbols-outlined text-emerald-600 text-[22px] filled">verified</span>
              <div>
                <p class="text-[11px] font-bold text-emerald-700 uppercase tracking-wide">FindStudyRoom Verified</p>
                <p class="text-xs font-bold text-slate-900">Delhi • Karol Bagh &amp; Kota Hubs</p>
              </div>
            </div>

            <!-- Top Right Rating Badge -->
            <div class="absolute top-4 right-4 glass-card px-3 py-1.5 rounded-xl shadow flex items-center gap-1 text-xs font-bold text-slate-800">
              <span class="text-amber-500 text-sm">★</span>
              <span>4.9/5 Rating (250+ Libraries)</span>
            </div>

            <!-- Bottom Right Floating Impact Badge -->
            <div class="absolute bottom-4 right-4 bg-slate-950/95 backdrop-blur-md text-white px-5 py-3 rounded-2xl shadow-2xl border border-white/10 flex items-center gap-3" style="background-color: rgba(0, 18, 41, 0.95) !important; color: #ffffff !important;">
              <div class="live-dot shrink-0"></div>
              <div>
                <p class="text-[10px] uppercase font-bold tracking-widest text-slate-300" style="color: #cbd5e1;">Live Impact</p>
                <p class="text-lg font-extrabold text-white leading-tight" style="color: #ffffff;">10x Direct Inquiries</p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- 3. NEW SECTION: BEFORE VS AFTER COMPARISON (Bina FindStudyRoom vs FindStudyRoom Ke Sath) -->
  <section class="py-14 sm:py-20 bg-slate-50 border-y border-slate-200">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="text-center max-w-3xl mx-auto space-y-2 mb-12">
        <span class="text-xs font-bold uppercase tracking-widest text-orange-600 bg-orange-100 px-3 py-1 rounded-full">
          The Smart Difference
        </span>
        <h2 class="text-2xl sm:text-4xl font-extrabold text-fsr-primary tracking-tight">
          Aapki Library: Pehle vs FindStudyRoom Ke Sath
        </h2>
        <p class="text-sm sm:text-base text-slate-600">
          Dekhein kaise sirf ₹499 aapki poori library operations aur student admissions ko transform kar deta hai.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 max-w-4xl mx-auto">
        <!-- Without FindStudyRoom -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-red-200 shadow-sm space-y-4">
          <div class="flex items-center gap-3 pb-3 border-b border-red-100">
            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
              <span class="material-symbols-outlined text-[24px]">close</span>
            </div>
            <div>
              <h3 class="text-lg font-bold text-red-900">Bina FindStudyRoom</h3>
              <p class="text-xs text-red-600">The Traditional, Stressful Way</p>
            </div>
          </div>
          <ul class="space-y-3.5 text-xs sm:text-sm text-slate-700">
            <li class="flex items-start gap-2.5">
              <span class="text-red-500 font-bold">✕</span>
              <span>Paper register me attendance aur monthly fees ka messy record jo aksar kho jata hai.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="text-red-500 font-bold">✕</span>
              <span>Roadside flex banners aur pamphlets par har saal ₹5,000–₹10,000 kharch karke bhi kam response.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="text-red-500 font-bold">✕</span>
              <span>Seats khali rehne par har mahine ₹15,000–₹40,000 ka direct revenue nuksan.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="text-red-500 font-bold">✕</span>
              <span>Aspirants Google par 'library near me' search karte hain par aapki library list nahi milti.</span>
            </li>
          </ul>
        </div>

        <!-- With FindStudyRoom -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-emerald-500 shadow-xl space-y-4 relative overflow-hidden">
          <div class="absolute -right-12 -top-12 w-28 h-28 bg-emerald-100 rounded-full blur-2xl pointer-events-none"></div>
          <div class="flex items-center gap-3 pb-3 border-b border-emerald-100">
            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
              <span class="material-symbols-outlined text-[24px] filled">check</span>
            </div>
            <div>
              <h3 class="text-lg font-bold text-emerald-950">FindStudyRoom Ke Sath</h3>
              <p class="text-xs text-emerald-700 font-semibold">100% Digital &amp; Profitable Growth</p>
            </div>
          </div>
          <ul class="space-y-3.5 text-xs sm:text-sm text-slate-800">
            <li class="flex items-start gap-2.5">
              <span class="text-emerald-600 font-bold">✓</span>
              <span><strong>1-Year Mobile App</strong> se Seat Grid, Morning/Evening Shifts &amp; Digital Fee Receipts ek touch par.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="text-emerald-600 font-bold">✓</span>
              <span><strong>Google Search SEO:</strong> Aapke area ke students seedhe aapki library profile khoj paate hain.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="text-emerald-600 font-bold">✓</span>
              <span><strong>10 Ready-made WhatsApp Posters</strong> special discount batches aur admission promote karne ke liye.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="text-emerald-600 font-bold">✓</span>
              <span><strong>Green 'Verified Library' Badge:</strong> Students aur parents ka blind trust turant ban jaata hai.</span>
            </li>
          </ul>
        </div>
      </div>

    </div>
  </section>

  <!-- 4. NEW SECTION: NO-BRAINER ROI MATH (Hisab-Kitab Card) -->
  <section class="py-14 sm:py-20 bg-white">
    <div class="max-w-[1000px] mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-offer-roi-card bg-[#022c22] text-white rounded-3xl p-7 sm:p-12 shadow-2xl relative overflow-hidden border border-emerald-500/30" style="background-color: #022c22 !important; background-image: linear-gradient(135deg, #022c22 0%, #001229 55%, #020617 100%) !important; color: #ffffff !important;">
        
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
          <div class="md:col-span-7 space-y-4">
            <span class="text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full border" style="background-color: rgba(52, 211, 153, 0.12); color: #34d399; border-color: rgba(52, 211, 153, 0.25);">
              Simple Profit Calculation (Hisab-Kitab)
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white leading-snug" style="color: #ffffff !important;">
              Agar Sirf 1 Student Bhi Aaya, Toh 300% Paisa Vasool!
            </h2>
            <div class="space-y-2.5 text-xs sm:text-sm text-slate-300" style="color: #cbd5e1 !important;">
              <p>• Aapki 1 Seat Ki Monthly Fee = <strong>₹1,000 – ₹1,500</strong></p>
              <p>• FindStudyRoom 1-Year Membership Cost = <strong>Sirf ₹499 (Ek Baar)</strong></p>
              <p class="font-bold text-sm sm:text-base pt-1" style="color: #6ee7b7 !important;">
                👉 Pehle hi mahine me aapki investment 400% se zyada recover ho jayegi! Baki poore 335 dino ke sabhi admissions ka munafa 100% aapka.
              </p>
            </div>
          </div>

          <div class="md:col-span-5 backdrop-blur-md p-6 rounded-2xl border text-center space-y-4" style="background-color: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.15);">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-300" style="color: #cbd5e1;">Real Package Value</p>
            <div class="space-y-1">
              <p class="text-xs line-through" style="color: #94a3b8;">Total Market Value: ₹6,900</p>
              <p class="text-4xl sm:text-5xl font-black" style="color: #34d399;">₹499</p>
              <p class="text-xs font-semibold" style="color: #fcd34d;">Valid for Full 365 Days</p>
            </div>
            <a href="https://wa.me/918386992953?text=Hi%20FindStudyRoom%20team!%20I%20want%20to%20list%20my%20library%20under%20the%20%E2%82%B9499%20introductory%20offer.%20Please%20share%20the%20package%20details." target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold py-3.5 px-5 rounded-xl text-sm transition-all shadow-lg hover:shadow-emerald-500/30" style="background-color: #10b981; color: #020617;">
              <span>Lock ₹499 Offer on WhatsApp</span>
              <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
          </div>
        </div>

        <!-- Ambient blur circle -->
        <div class="absolute -right-16 -top-16 w-60 h-60 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
      </div>
    </div>
  </section>

  <!-- 5. COMPREHENSIVE PACKAGE BENEFITS WITH REAL VALUE TAGS -->
  <section class="py-16 sm:py-24 bg-fsr-surface-low border-y border-slate-200" id="package-benefits">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="text-center max-w-3xl mx-auto space-y-3 mb-14">
        <span class="text-xs font-bold uppercase tracking-widest text-emerald-700 bg-emerald-100 px-3.5 py-1.5 rounded-full border border-emerald-200">
          Unbeatable Value Bundle (Worth ₹6,900+)
        </span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-fsr-primary tracking-tight">
          Everything Included in Your ₹499 Package
        </h2>
        <p class="text-base text-fsr-muted leading-relaxed">
          Har feature transparent aur practical hai. Koi hidden cost ya annual renewal commission nahi.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl p-7 border border-slate-200 hover-lift flex flex-col justify-between shadow-sm">
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm" style="background-color: #e8eefc !important; color: #001229 !important;">
                <span class="material-symbols-outlined text-[28px]">storefront</span>
              </div>
              <span class="text-[11px] font-bold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">Value: ₹1,200</span>
            </div>
            <h3 class="text-xl font-bold text-fsr-primary">Premium Library Page</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
              HD photo gallery, AC/Wi-Fi/Power socket amenity list, operating shift hours, fee tariffs, and an official green 'Verified by FindStudyRoom' trust badge.
            </p>
          </div>
          <div class="pt-6 border-t border-slate-100 mt-4 flex items-center justify-between">
            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider flex items-center gap-1">
              <span class="material-symbols-outlined text-sm">check_circle</span> Included in ₹499
            </span>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl p-7 border border-slate-200 hover-lift flex flex-col justify-between shadow-sm">
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm" style="background-color: #e8eefc !important; color: #001229 !important;">
                <span class="material-symbols-outlined text-[28px]">travel_explore</span>
              </div>
              <span class="text-[11px] font-bold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">Value: ₹1,500</span>
            </div>
            <h3 class="text-xl font-bold text-fsr-primary">Google Discoverability</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
              Structured JSON-LD local schema &amp; search indexing. Helps students find your library when searching for 'study rooms near me' in your exact locality.
            </p>
          </div>
          <div class="pt-6 border-t border-slate-100 mt-4 flex items-center justify-between">
            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider flex items-center gap-1">
              <span class="material-symbols-outlined text-sm">check_circle</span> Included in ₹499
            </span>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl p-7 border border-slate-200 hover-lift flex flex-col justify-between shadow-sm">
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm" style="background-color: #e8eefc !important; color: #001229 !important;">
                <span class="material-symbols-outlined text-[28px]">campaign</span>
              </div>
              <span class="text-[11px] font-bold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">Value: ₹1,000</span>
            </div>
            <h3 class="text-xl font-bold text-fsr-primary">10 Promotional WhatsApp Posts</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
              10 ready-to-share promotional image creatives with your library details, discount batches, and quiet ambiance to post to WhatsApp Status and local student groups.
            </p>
          </div>
          <div class="pt-6 border-t border-slate-100 mt-4 flex items-center justify-between">
            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider flex items-center gap-1">
              <span class="material-symbols-outlined text-sm">check_circle</span> Included in ₹499
            </span>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-2xl p-7 border border-slate-200 hover-lift flex flex-col justify-between shadow-sm">
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm" style="background-color: #e8eefc !important; color: #001229 !important;">
                <span class="material-symbols-outlined text-[28px]">photo_camera</span>
              </div>
              <span class="text-[11px] font-bold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">Value: ₹800</span>
            </div>
            <h3 class="text-xl font-bold text-fsr-primary">Instagram Promotional Feature</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
              1 dedicated highlight post broadcasted on FindStudyRoom's official social channels showcasing your facility interiors and location landmarks.
            </p>
          </div>
          <div class="pt-6 border-t border-slate-100 mt-4 flex items-center justify-between">
            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider flex items-center gap-1">
              <span class="material-symbols-outlined text-sm">check_circle</span> Included in ₹499
            </span>
          </div>
        </div>

        <!-- Card 5 (Featured - 1-Year App License) -->
        <div class="bg-offer-app-card bg-[#001229] text-white rounded-2xl p-7 shadow-xl md:col-span-2 flex flex-col justify-between relative overflow-hidden border border-slate-800" style="background-color: #001229 !important; background-image: linear-gradient(135deg, #001229 0%, #0f1f38 60%, #030a14 100%) !important; color: #ffffff !important;">
          <div class="space-y-4 relative z-10">
            <div class="flex items-center justify-between flex-wrap gap-2">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm border" style="background-color: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.15); color: #34d399;">
                <span class="material-symbols-outlined text-[28px]">phone_android</span>
              </div>
              <span class="text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm" style="background-color: #059669; color: #ffffff;">
                Worth ₹2,400 • Free for 1 Year
              </span>
            </div>
            <h3 class="text-2xl font-bold text-white" style="color: #ffffff !important;">Library Management App (1-Year Access)</h3>
            <p class="text-sm leading-relaxed max-w-2xl" style="color: #cbd5e1 !important;">
              Purana register fek dijiye! Apne mobile se individual seat allocation dekhein (A1, A2, B1...), morning/evening slots allot karein, student attendance track karein, aur monthly fee due alerts bhejein.
            </p>
          </div>
          <div class="pt-6 mt-4 flex flex-wrap items-center gap-3 relative z-10">
            <div class="flex items-center gap-1.5 text-xs sm:text-sm font-semibold px-3.5 py-1.5 rounded-lg border" style="background-color: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.12); color: #6ee7b7;">
              <span class="material-symbols-outlined text-[18px]">done_all</span> Seat Grid Manager
            </div>
            <div class="flex items-center gap-1.5 text-xs sm:text-sm font-semibold px-3.5 py-1.5 rounded-lg border" style="background-color: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.12); color: #6ee7b7;">
              <span class="material-symbols-outlined text-[18px]">done_all</span> Shift Slot Allocation
            </div>
            <div class="flex items-center gap-1.5 text-xs sm:text-sm font-semibold px-3.5 py-1.5 rounded-lg border" style="background-color: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.12); color: #6ee7b7;">
              <span class="material-symbols-outlined text-[18px]">done_all</span> Fee Receipt Logs
            </div>
          </div>
          <div class="absolute -right-16 -bottom-16 w-56 h-56 rounded-full bg-emerald-500/20 blur-3xl pointer-events-none"></div>
        </div>
      </div>

    </div>
  </section>

  <!-- 6. NEW SECTION: APP INTERACTIVE SEAT GRID PREVIEW -->
  {{--<section class="py-16 sm:py-20 bg-white">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
        <div class="lg:col-span-6 space-y-5">
          <span class="text-xs font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
            Included Software Sneak Peek
          </span>
          <h2 class="text-2xl sm:text-4xl font-extrabold text-fsr-primary tracking-tight">
            Managing Seats &amp; Shifts is Now as Simple as WhatsApp
          </h2>
          <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
            Aapki poori reading hall ka live layout aapke mobile par dikhega. Dekhiye kaunsi seat khali hai, kis student ki fee baaki hai, aur kis time shift change hona hai.
          </p>

          <div class="space-y-3 pt-2">
            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200">
              <span class="material-symbols-outlined text-emerald-600 text-2xl mt-0.5">grid_view</span>
              <div>
                <h4 class="text-sm font-bold text-slate-900">Color-Coded Live Desk Status</h4>
                <p class="text-xs text-slate-600">Green = Khali seat, Red = Booked student, Yellow = Free trial reserved.</p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200">
              <span class="material-symbols-outlined text-blue-600 text-2xl mt-0.5">receipt</span>
              <div>
                <h4 class="text-sm font-bold text-slate-900">Instant WhatsApp Payment Receipts</h4>
                <p class="text-xs text-slate-600">Student fee jama kare toh 1-click me unke WhatsApp par official receipt chali jaati hai.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Interactive Visual Desk Grid Mockup -->
        <div class="lg:col-span-6">
          <div class="bg-slate-900 text-white p-6 sm:p-7 rounded-3xl shadow-2xl border border-slate-800 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
              <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                <span class="text-xs font-mono text-slate-400 ms-2">Live Floor Grid • Hall A</span>
              </div>
              <span class="text-[11px] bg-emerald-500/20 text-emerald-400 px-2.5 py-0.5 rounded font-mono font-bold">28/36 Occupied</span>
            </div>

            <!-- Seat grid demo buttons -->
            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2.5 pt-2">
              <div class="bg-red-500/20 border border-red-500/50 text-red-300 p-2.5 rounded-xl text-center text-xs font-bold font-mono">A1<br><span class="text-[9px] font-normal text-slate-400">Rahul</span></div>
              <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 p-2.5 rounded-xl text-center text-xs font-bold font-mono">A2<br><span class="text-[9px] text-emerald-400">Available</span></div>
              <div class="bg-red-500/20 border border-red-500/50 text-red-300 p-2.5 rounded-xl text-center text-xs font-bold font-mono">A3<br><span class="text-[9px] font-normal text-slate-400">Pooja</span></div>
              <div class="bg-red-500/20 border border-red-500/50 text-red-300 p-2.5 rounded-xl text-center text-xs font-bold font-mono">A4<br><span class="text-[9px] font-normal text-slate-400">Aman</span></div>
              <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 p-2.5 rounded-xl text-center text-xs font-bold font-mono">A5<br><span class="text-[9px] text-emerald-400">Available</span></div>
              <div class="bg-amber-500/20 border border-amber-500/50 text-amber-300 p-2.5 rounded-xl text-center text-xs font-bold font-mono">A6<br><span class="text-[9px] text-amber-400">Trial</span></div>

              <div class="bg-red-500/20 border border-red-500/50 text-red-300 p-2.5 rounded-xl text-center text-xs font-bold font-mono">B1<br><span class="text-[9px] font-normal text-slate-400">Deepak</span></div>
              <div class="bg-red-500/20 border border-red-500/50 text-red-300 p-2.5 rounded-xl text-center text-xs font-bold font-mono">B2<br><span class="text-[9px] font-normal text-slate-400">Kavita</span></div>
              <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 p-2.5 rounded-xl text-center text-xs font-bold font-mono">B3<br><span class="text-[9px] text-emerald-400">Available</span></div>
              <div class="bg-red-500/20 border border-red-500/50 text-red-300 p-2.5 rounded-xl text-center text-xs font-bold font-mono">B4<br><span class="text-[9px] font-normal text-slate-400">Sunil</span></div>
              <div class="bg-red-500/20 border border-red-500/50 text-red-300 p-2.5 rounded-xl text-center text-xs font-bold font-mono">B5<br><span class="text-[9px] font-normal text-slate-400">Nisha</span></div>
              <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 p-2.5 rounded-xl text-center text-xs font-bold font-mono">B6<br><span class="text-[9px] text-emerald-400">Available</span></div>
            </div>

            <div class="flex items-center justify-between text-[11px] text-slate-400 pt-2 border-t border-slate-800">
              <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-emerald-500"></span> Available (Green)</span>
              <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-red-500"></span> Booked (Red)</span>
              <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-amber-500"></span> Trial Slot (Yellow)</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>--}}

  <!-- 7. HOW IT WORKS: 3 STEPS -->
  <section class="py-16 sm:py-24 bg-fsr-surface-low border-y border-slate-200/80" id="how-it-works">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="text-center max-w-2xl mx-auto space-y-3 mb-14">
        <span class="text-xs font-bold uppercase tracking-widest text-emerald-700 bg-emerald-100 px-3.5 py-1.5 rounded-full border border-emerald-200">
          Fast &amp; Frictionless
        </span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-fsr-primary tracking-tight">
          How to Go Live in 3 Simple Steps
        </h2>
        <p class="text-base text-fsr-muted leading-relaxed">
          Koi lambi technical documentation nahi. Hamari dedicated onboarding team WhatsApp par aapka poora setup karwa degi.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
        <!-- Step 1 -->
        <div class="relative bg-white p-7 rounded-2xl shadow-sm border border-slate-200 hover-lift space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-4xl sm:text-5xl font-black text-slate-200">01</span>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
              <span class="material-symbols-outlined text-[28px]">edit_document</span>
            </div>
          </div>
          <div class="space-y-2">
            <h3 class="text-xl font-bold text-fsr-primary">Share Your Library Details</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
              Submit basic information: library name, desk capacity, amenities (Wi-Fi, AC, lockers), shift timings, and photos through our quick form or directly on WhatsApp.
            </p>
          </div>
        </div>

        <!-- Step 2 -->
        <div class="relative bg-white p-7 rounded-2xl shadow-sm border border-slate-200 hover-lift space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-4xl sm:text-5xl font-black text-slate-200">02</span>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
              <span class="material-symbols-outlined text-[28px]">payments</span>
            </div>
          </div>
          <div class="space-y-2">
            <h3 class="text-xl font-bold text-fsr-primary">Confirm Package &amp; Pay ₹499</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
              Review your draft page preview prepared by our design desk. Complete the one-time ₹499 introductory onboarding payment securely via official UPI QR or instant link.
            </p>
          </div>
        </div>

        <!-- Step 3 -->
        <div class="relative bg-white p-7 rounded-2xl shadow-sm border border-slate-200 hover-lift space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-4xl sm:text-5xl font-black text-slate-200">03</span>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
              <span class="material-symbols-outlined text-[28px]">rocket_launch</span>
            </div>
          </div>
          <div class="space-y-2">
            <h3 class="text-xl font-bold text-fsr-primary">Verification &amp; Activation</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
              Our team verifies premises authenticity, issues the official 'Verified Library' trust badge, generates your 10 promotional creatives, and activates your 1-Year library software.
            </p>
          </div>
        </div>
      </div>

      <div class="mt-10 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-[22px]">timer</span>
          </div>
          <span class="text-sm sm:text-base font-bold text-fsr-primary">Average activation turnaround time: Under 24 Hours</span>
        </div>
        <a href="#enquiry-form" class="text-sm font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 transition-colors">
          <span>Get Started Now</span>
          <span class="material-symbols-outlined text-[18px]">chevron_right</span>
        </a>
      </div>

    </div>
  </section>

  <!-- 8. REAL LIBRARY OWNER TESTIMONIALS (Social Proof) -->
  <section class="py-16 sm:py-20 bg-white">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="text-center max-w-3xl mx-auto space-y-2 mb-12">
        <span class="text-xs font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">
          Verified Reviews
        </span>
        <h2 class="text-2xl sm:text-4xl font-extrabold text-fsr-primary tracking-tight">
          What Library Owners Say Across India
        </h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Testimonial 1 -->
        <div class="bg-fsr-surface rounded-2xl p-6 border border-slate-200 space-y-4 hover-lift">
          <div class="flex items-center gap-1 text-amber-500 text-sm">
            ★★★★★
          </div>
          <p class="text-xs sm:text-sm text-slate-700 italic leading-relaxed">
            "Pehle register me record rakhna bada headache tha. Is app se desk slot manage karna bohot easy ho gaya aur Google se humari 45 seats sirf 12 din me full ho gayi!"
          </p>
          <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-xs">
            <div>
              <p class="font-bold text-slate-900">Rajesh Sharma</p>
              <p class="text-slate-500">Apex Self-Study Reading Hall</p>
            </div>
            <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded">Karol Bagh, Delhi</span>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="bg-fsr-surface rounded-2xl p-6 border border-slate-200 space-y-4 hover-lift">
          <div class="flex items-center gap-1 text-amber-500 text-sm">
            ★★★★★
          </div>
          <p class="text-xs sm:text-sm text-slate-700 italic leading-relaxed">
            "Kota me competition bohot zyada hai. FindStudyRoom ke green verified badge se student aur parents ka trust seedhe ban gaya. ₹499 me 1-Year app milna best deal hai."
          </p>
          <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-xs">
            <div>
              <p class="font-bold text-slate-900">Amit Choudhary</p>
              <p class="text-slate-500">Target Study Point</p>
            </div>
            <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded">Kota, Rajasthan</span>
          </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="bg-fsr-surface rounded-2xl p-6 border border-slate-200 space-y-4 hover-lift">
          <div class="flex items-center gap-1 text-amber-500 text-sm">
            ★★★★★
          </div>
          <p class="text-xs sm:text-sm text-slate-700 italic leading-relaxed">
            "Bina kisi brokerage ya commission ke students ke direct call mere WhatsApp par aate hain. Jo 10 poster unhone banake diye usse status lagane me bohot help mili."
          </p>
          <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-xs">
            <div>
              <p class="font-bold text-slate-900">Vikram Patil</p>
              <p class="text-slate-500">Silent Zone Library</p>
            </div>
            <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded">Pune, Maharashtra</span>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- 9. REGISTRATION & INTERACTIVE ENQUIRY FORM + WHATSAPP HUB -->
  <section class="py-16 sm:py-24 bg-fsr-surface-low border-t border-slate-200" id="enquiry-form">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="text-center max-w-2xl mx-auto space-y-3 mb-14">
        <span class="text-xs font-bold uppercase tracking-widest text-emerald-700 bg-emerald-100 px-3.5 py-1.5 rounded-full border border-emerald-200">
          Instant 1-Minute Registration
        </span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-fsr-primary tracking-tight">
          Join India’s Dedicated Study Room Network
        </h2>
        <p class="text-base text-fsr-muted leading-relaxed">
          Connect immediately via WhatsApp or fill the quick details form below. Our support executive will reach out to you within 15 minutes.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
        <!-- Left Column: WhatsApp Fast-Track Hub -->
        <div class="lg:col-span-5 bg-white p-7 sm:p-8 rounded-3xl border border-slate-200 shadow-lg space-y-6">
          <div class="space-y-2">
            <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full">
              <span class="w-2 h-2 rounded-full bg-emerald-600 animate-ping"></span>
              <span class="text-xs font-bold uppercase">Fastest Response Route</span>
            </div>
            <h3 class="text-2xl font-bold text-fsr-primary">Prefer WhatsApp?</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
              Skip typing lengthy forms. Send us your address and desk details on WhatsApp for real-time verification and instant onboarding.
            </p>
          </div>

          <!-- Pre-filled message preview -->
          <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-2 shadow-inner">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Pre-filled Message Preview</span>
            <p class="text-xs sm:text-sm text-slate-800 italic leading-relaxed font-mono">
              "Hi FindStudyRoom team! I want to list my library under the ₹499 introductory offer. Please share the package details and registration process."
            </p>
          </div>

          <a href="https://wa.me/918386992953?text=Hi%20FindStudyRoom%20team!%20I%20want%20to%20list%20my%20library%20under%20the%20%E2%82%B9499%20introductory%20offer.%20Please%20share%20the%20package%20details%20and%20registration%20process." target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-center gap-2.5 bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-4 rounded-xl text-base font-bold transition-all shadow-lg shadow-emerald-700/25 hover:shadow-xl transform hover:-translate-y-0.5">
            <span class="material-symbols-outlined text-[24px]">chat</span>
            <span>Chat Directly on WhatsApp</span>
          </a>

          <div class="flex items-center justify-between text-xs sm:text-sm text-slate-600 pt-1 border-t border-slate-200">
            <span>Direct WhatsApp Helpline:</span>
            <span class="font-extrabold text-fsr-primary text-sm sm:text-base">+91 83869 92953</span>
          </div>

          <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-700 text-[24px] filled shrink-0">verified</span>
            <p class="text-xs text-emerald-950 font-medium">
              Official FindStudyRoom verified business desk. Safe UPI &amp; direct assistance.
            </p>
          </div>
        </div>

        <!-- Right Column: Interactive Form -->
        <div class="lg:col-span-7 bg-white p-7 sm:p-9 rounded-3xl border border-slate-200 shadow-lg">
          <form class="space-y-5" id="ownerRegistrationForm" onsubmit="handleOfferFormSubmit(event)">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
              <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-fsr-primary" for="ownerName">Your Full Name *</label>
                <input type="text" id="ownerName" name="name" required placeholder="e.g. Rajesh Sharma" class="w-full bg-slate-50 px-4 py-3 rounded-xl text-sm text-slate-900 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
              </div>
              <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-fsr-primary" for="libraryName">Library / Study Hall Name *</label>
                <input type="text" id="libraryName" name="library_name" required placeholder="e.g. Apex Self Study Reading Hall" class="w-full bg-slate-50 px-4 py-3 rounded-xl text-sm text-slate-900 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
              <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-fsr-primary" for="mobileNumber">WhatsApp Mobile Number *</label>
                <input type="tel" id="mobileNumber" name="phone" pattern="[0-9]{10}" required placeholder="10-digit mobile number" class="w-full bg-slate-50 px-4 py-3 rounded-xl text-sm text-slate-900 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
              </div>
              <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-fsr-primary" for="cityHub">City / Major Study Hub *</label>
                <select id="cityHub" name="city" required class="w-full bg-slate-50 px-4 py-3 rounded-xl text-sm text-slate-900 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                  <option value="" disabled selected>Select Your City</option>
                  <option value="Delhi NCR">Delhi NCR (Karol Bagh, Mukherjee Nagar, Laxmi Nagar)</option>
                  <option value="Kota">Kota (Rajasthan)</option>
                  <option value="Pune">Pune (Maharashtra)</option>
                  <option value="Patna">Patna (Bihar)</option>
                  <option value="Jaipur">Jaipur (Rajasthan)</option>
                  <option value="Hyderabad">Hyderabad (Telangana)</option>
                  <option value="Lucknow">Lucknow / Prayagraj (UP)</option>
                  <option value="Bengaluru">Bengaluru (Karnataka)</option>
                  <option value="Indore">Indore (MP)</option>
                  <option value="Other">Other City / District Across India</option>
                </select>
              </div>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold uppercase tracking-wider text-fsr-primary" for="address">Library Address / Landmark *</label>
              <input type="text" id="address" name="address" required placeholder="Shop/Floor No., Street, Near Metro Station or Coaching Institute" class="w-full bg-slate-50 px-4 py-3 rounded-xl text-sm text-slate-900 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
              <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-fsr-primary" for="seatCount">Total Study Desks / Seats *</label>
                <select id="seatCount" name="seats" required class="w-full bg-slate-50 px-4 py-3 rounded-xl text-sm text-slate-900 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                  <option value="Under 30">Under 30 Seats</option>
                  <option value="30 - 60" selected>30 to 60 Seats</option>
                  <option value="60 - 120">60 to 120 Seats</option>
                  <option value="120+">120+ Seats (Multi-Floor)</option>
                </select>
              </div>
              <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-fsr-primary">Applicable Special Offer</label>
                <div class="bg-slate-50 px-4 py-2.5 rounded-xl flex items-center justify-between border border-slate-200">
                  <span class="text-xs sm:text-sm font-semibold text-slate-800">Introductory Package</span>
                  <span class="text-base font-extrabold text-emerald-700">₹499 Only</span>
                </div>
              </div>
            </div>

            <!-- Submit Button with loading state -->
            <button type="submit" id="submitBtn" class="w-full bg-[#001229] hover:bg-slate-900 text-white py-4 px-6 rounded-xl text-base font-bold transition-all flex items-center justify-center gap-2 shadow-lg hover:shadow-xl cursor-pointer" style="background-color: #001229 !important; color: #ffffff !important;">
              <span id="btnText">Submit Enquiry &amp; Lock ₹499 Offer</span>
              <span id="btnSpinner" class="hidden material-symbols-outlined text-[20px] animate-spin">progress_activity</span>
            </button>

            <!-- Success State Notification -->
            <div id="formSuccessMessage" class="hidden bg-emerald-50 border border-emerald-200 p-5 rounded-2xl text-emerald-950 space-y-2">
              <div class="flex items-center gap-2 text-base font-bold text-emerald-800">
                <span class="material-symbols-outlined text-emerald-600 filled">check_circle</span>
                <span>Thank you! Your enquiry has been recorded.</span>
              </div>
              <p class="text-sm text-emerald-900">
                Opening WhatsApp to connect you directly with our onboarding specialist. If WhatsApp doesn't open automatically, our executive will contact you within 15 minutes!
              </p>
            </div>
          </form>
        </div>
      </div>

    </div>
  </section>

  <!-- 10. FREQUENTLY ASKED QUESTIONS -->
  <section class="py-16 sm:py-24 bg-white" id="faq">
    <div class="max-w-[860px] mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="text-center space-y-3 mb-14">
        <span class="text-xs font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-full border border-emerald-200">
          Clear Answers
        </span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-fsr-primary tracking-tight">
          Frequently Asked Questions
        </h2>
        <p class="text-base text-fsr-muted leading-relaxed">
          Everything you need to know about our listing policy, terms, and the ₹499 introductory package.
        </p>
      </div>

      <div class="space-y-3.5" id="faqAccordion">
        <!-- FAQ Item 1 -->
        <div class="bg-fsr-surface rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
          <button type="button" class="w-full text-left p-5 sm:p-6 flex items-center justify-between gap-4 text-fsr-primary font-bold text-base sm:text-lg focus:outline-none cursor-pointer hover:bg-slate-50/80 transition-colors" onclick="toggleFaqAccordion(this)">
            <span>Who can list a library on FindStudyRoom?</span>
            <span class="material-symbols-outlined text-[22px] text-slate-500 faq-icon-rotate shrink-0">expand_more</span>
          </button>
          <div class="faq-body hidden px-5 sm:px-6 pb-6 text-slate-600 text-sm sm:text-base leading-relaxed border-t border-slate-100 pt-3">
            Any self-study library, private reading hall, student study lounge, co-study space, or coaching institute reading room operating legally anywhere in India can list. Whether you have 20 seats in a smaller district or 200+ seats in Kota, Delhi, Pune, or Patna, you are welcome!
          </div>
        </div>

        <!-- FAQ Item 2 -->
        <div class="bg-fsr-surface rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
          <button type="button" class="w-full text-left p-5 sm:p-6 flex items-center justify-between gap-4 text-fsr-primary font-bold text-base sm:text-lg focus:outline-none cursor-pointer hover:bg-slate-50/80 transition-colors" onclick="toggleFaqAccordion(this)">
            <span>What exactly is included in the ₹499 package?</span>
            <span class="material-symbols-outlined text-[22px] text-slate-500 faq-icon-rotate shrink-0">expand_more</span>
          </button>
          <div class="faq-body hidden px-5 sm:px-6 pb-6 text-slate-600 text-sm sm:text-base leading-relaxed border-t border-slate-100 pt-3">
            You receive complete access to: (1) A dedicated high-speed Library Profile Page with your photos and amenities, (2) Official 'Verified Library' trust badge, (3) Google Search schema indexing optimization, (4) 10 custom social/WhatsApp promotional image creatives, (5) 1 dedicated Instagram promotional feature post, and (6) A full 1-Year license to our Library Management Web App to track desk allocations and fee payments.
          </div>
        </div>

        <!-- FAQ Item 3 -->
        <div class="bg-fsr-surface rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
          <button type="button" class="w-full text-left p-5 sm:p-6 flex items-center justify-between gap-4 text-fsr-primary font-bold text-base sm:text-lg focus:outline-none cursor-pointer hover:bg-slate-50/80 transition-colors" onclick="toggleFaqAccordion(this)">
            <span>Does FindStudyRoom take commission from student admissions?</span>
            <span class="material-symbols-outlined text-[22px] text-slate-500 faq-icon-rotate shrink-0">expand_more</span>
          </button>
          <div class="faq-body hidden px-5 sm:px-6 pb-6 text-slate-600 text-sm sm:text-base leading-relaxed border-t border-slate-100 pt-3">
            <strong>0% Commission!</strong> We do not take a single rupee cut from student fees, monthly desk rents, or admission charges. All enquiries and calls come directly to you.
          </div>
        </div>

        <!-- FAQ Item 4 -->
        <div class="bg-fsr-surface rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
          <button type="button" class="w-full text-left p-5 sm:p-6 flex items-center justify-between gap-4 text-fsr-primary font-bold text-base sm:text-lg focus:outline-none cursor-pointer hover:bg-slate-50/80 transition-colors" onclick="toggleFaqAccordion(this)">
            <span>How does the verification process work?</span>
            <span class="material-symbols-outlined text-[22px] text-slate-500 faq-icon-rotate shrink-0">expand_more</span>
          </button>
          <div class="faq-body hidden px-5 sm:px-6 pb-6 text-slate-600 text-sm sm:text-base leading-relaxed border-t border-slate-100 pt-3">
            Verification is quick and friendly. After submitting your details, our team conducts a quick WhatsApp video call or reviews recent interior photos and electricity/business registration documents to confirm genuine student seating amenities before issuing the verified trust badge.
          </div>
        </div>

        <!-- FAQ Item 5 -->
        <div class="bg-fsr-surface rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
          <button type="button" class="w-full text-left p-5 sm:p-6 flex items-center justify-between gap-4 text-fsr-primary font-bold text-base sm:text-lg focus:outline-none cursor-pointer hover:bg-slate-50/80 transition-colors" onclick="toggleFaqAccordion(this)">
            <span>How do I pay the ₹499 fee?</span>
            <span class="material-symbols-outlined text-[22px] text-slate-500 faq-icon-rotate shrink-0">expand_more</span>
          </button>
          <div class="faq-body hidden px-5 sm:px-6 pb-6 text-slate-600 text-sm sm:text-base leading-relaxed border-t border-slate-100 pt-3">
            Payment is completed via official UPI (Google Pay, PhonePe, Paytm), BHIM UPI QR codes, or direct Razorpay secure payment link .
          </div>
        </div>

        <!-- FAQ Item 6 -->
        <div class="bg-fsr-surface rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
          <button type="button" class="w-full text-left p-5 sm:p-6 flex items-center justify-between gap-4 text-fsr-primary font-bold text-base sm:text-lg focus:outline-none cursor-pointer hover:bg-slate-50/80 transition-colors" onclick="toggleFaqAccordion(this)">
            <span>When will my listing and app be activated?</span>
            <span class="material-symbols-outlined text-[22px] text-slate-500 faq-icon-rotate shrink-0">expand_more</span>
          </button>
          <div class="faq-body hidden px-5 sm:px-6 pb-6 text-slate-600 text-sm sm:text-base leading-relaxed border-t border-slate-100 pt-3">
            Your live profile is created and published within 24 to 48 hours of photo submission and detail confirmation. Your 10 marketing banners are shared on WhatsApp right after activation.
          </div>
        </div>

        <!-- FAQ Item 7 -->
        <div class="bg-fsr-surface rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
          <button type="button" class="w-full text-left p-5 sm:p-6 flex items-center justify-between gap-4 text-fsr-primary font-bold text-base sm:text-lg focus:outline-none cursor-pointer hover:bg-slate-50/80 transition-colors" onclick="toggleFaqAccordion(this)">
            <span>Are there any recurring monthly charges after paying ₹499?</span>
            <span class="material-symbols-outlined text-[22px] text-slate-500 faq-icon-rotate shrink-0">expand_more</span>
          </button>
          <div class="faq-body hidden px-5 sm:px-6 pb-6 text-slate-600 text-sm sm:text-base leading-relaxed border-t border-slate-100 pt-3">
            <strong>None!</strong> The ₹499 fee is a one-time introductory registration. It covers your full 12 months (365 days) of directory hosting and library owner software access with zero monthly maintenance bills during this period.
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- 11. FINAL HIGH-CONVERTING CTA BANNER -->
  <section class="py-16 sm:py-20 bg-white">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="bg-offer-cta-card bg-[#001229] text-white rounded-3xl p-8 sm:p-14 lg:p-16 relative overflow-hidden shadow-2xl border border-slate-800" style="background-color: #001229 !important; background-image: linear-gradient(135deg, #001229 0%, #0c2038 50%, #001229 100%) !important; color: #ffffff !important;">
        <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-amber-400/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -top-20 w-80 h-80 rounded-full bg-emerald-500/20 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl mx-auto text-center space-y-6">
          <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full shadow-sm" style="background-color: rgba(251, 191, 36, 0.15); color: #fcd34d; border: 1px solid rgba(252, 211, 77, 0.3);">
            <span class="material-symbols-outlined text-base animate-pulse" style="color: #fcd34d;">campaign</span>
            <span class="text-xs font-bold uppercase tracking-wider" style="color: #fcd34d;">Introductory Slots Closing Soon</span>
          </div>

          <h2 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight leading-[1.15]" style="color: #ffffff !important;">
            Your Library Deserves to Be Full Every Single Day!
          </h2>

          <p class="text-base sm:text-lg leading-relaxed" style="color: #cbd5e1 !important;">
            Start building your library’s digital footprint with FindStudyRoom today before the introductory rate expires. Reach serious students actively seeking quiet reading desks in your area.
          </p>

          <div class="pt-3 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="https://wa.me/918386992953?text=Hi%20FindStudyRoom%20team!%20I%20want%20to%20list%20my%20library%20under%20the%20%E2%82%B9499%20introductory%20offer.%20Please%20share%20the%20package%20details%20and%20registration%20process." target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-xl text-base font-bold transition-all shadow-xl shadow-emerald-600/30 transform hover:-translate-y-0.5" style="background-color: #059669; color: #ffffff;">
              <span class="material-symbols-outlined text-[24px]">chat</span>
              <span>Enquire on WhatsApp — Just ₹499</span>
            </a>
            <a href="#enquiry-form" class="w-full sm:w-auto inline-flex items-center justify-center bg-white/10 hover:bg-white/20 text-white border border-white/20 px-7 py-4 rounded-xl text-sm font-bold transition-colors" style="background-color: rgba(255, 255, 255, 0.12); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.25);">
              <span>Submit Web Enquiry</span>
            </a>
          </div>

          <div class="pt-3 flex flex-wrap items-center justify-center gap-y-2 gap-x-6 text-xs sm:text-sm font-medium" style="color: #94a3b8 !important;">
            <span>⚡ Instant response on WhatsApp</span>
            <span class="hidden sm:inline opacity-40">•</span>
            <span>🛡️ Verified authenticity badge</span>
            <span class="hidden sm:inline opacity-40">•</span>
            <span>👥 100% direct student admissions</span>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- 12. COMPLIANCE & OPERATIONAL GUARANTEE POLICY -->
  <section class="pt-12 pb-36 sm:pt-16 sm:pb-40 bg-slate-50 border-t border-slate-200" id="policy-disclaimer">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="bg-white rounded-3xl p-6 sm:p-9 border border-slate-200 shadow-md space-y-6">
        
        <!-- Header row -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
          <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-slate-100 text-[#001229] flex items-center justify-center shrink-0 border border-slate-200 shadow-sm">
              <span class="material-symbols-outlined text-[24px]">policy</span>
            </div>
            <div>
              <h3 class="text-base sm:text-lg font-bold text-[#001229] tracking-tight">
                Disclaimer &amp; Operational Guarantee Policy
              </h3>
              <p class="text-xs text-slate-500 font-medium">
                Transparent operating standards for library owners and study room partners
              </p>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-800 text-[11px] font-bold px-3 py-1 rounded-full border border-emerald-200">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
              100% Transparent
            </span>
            <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 text-[11px] font-bold px-3 py-1 rounded-full border border-slate-200">
              0% Commission
            </span>
          </div>
        </div>

        <!-- 3 Feature Columns -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-slate-600 text-xs sm:text-sm">
          
          <div class="p-4 sm:p-5 rounded-2xl bg-slate-50/80 border border-slate-200/80 space-y-2">
            <div class="flex items-center gap-2 font-bold text-[#001229]">
              <span class="material-symbols-outlined text-emerald-600 text-[20px]">travel_explore</span>
              <span>Google Discoverability &amp; SEO</span>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed">
              FindStudyRoom optimizes local directory visibility and search schema. We do not make false guaranteed #1 Google ranking claims, as search algorithms depend on locality demand and user queries.
            </p>
          </div>

          <div class="p-4 sm:p-5 rounded-2xl bg-slate-50/80 border border-slate-200/80 space-y-2">
            <div class="flex items-center gap-2 font-bold text-[#001229]">
              <span class="material-symbols-outlined text-emerald-600 text-[20px]">groups</span>
              <span>Direct Admissions &amp; Seats</span>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed">
              All student calls, walk-ins, and WhatsApp enquiries connect directly with the library owner. We do not act as middle-men, nor do we promise fixed seat occupancy quotas or guaranteed admissions.
            </p>
          </div>

          <div class="p-4 sm:p-5 rounded-2xl bg-slate-50/80 border border-slate-200/80 space-y-2">
            <div class="flex items-center gap-2 font-bold text-[#001229]">
              <span class="material-symbols-outlined text-emerald-600 text-[20px]">verified</span>
              <span>Physical Space Verification</span>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed">
              Listing approvals and 'Verified Library' badges require verification of genuine reading hall facilities, quiet ambiance, and functional student amenities prior to permanent directory activation.
            </p>
          </div>

        </div>

        <!-- Footnote note -->
        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500">
          <p>
            Have questions regarding package terms or verification? Reach out to our partner desk at 
            <a href="https://wa.me/918386992953" target="_blank" rel="noopener noreferrer" class="font-bold text-emerald-700 hover:underline">+91 83869 92953</a>.
          </p>
          <span class="text-[11px] text-slate-400">FindStudyRoom Partner Network • Updated 2026</span>
        </div>

      </div>

    </div>
  </section>

  <!-- 13. STICKY FLOATING QUICK-ACTION BAR (Mobile & Desktop) -->
  <div id="stickyFloatingBar" class="fixed bottom-3 sm:bottom-4 left-3 sm:left-4 right-3 sm:right-4 z-40 max-w-[850px] mx-auto bg-slate-950/95 backdrop-blur-xl border border-emerald-500/40 text-white p-3 sm:p-3.5 rounded-2xl shadow-2xl flex items-center justify-between gap-2.5 sm:gap-3" style="background-color: rgba(2, 6, 23, 0.96) !important; color: #ffffff !important;">
    <div class="flex items-center gap-2.5 sm:gap-3">
      <!-- WhatsApp Brand Icon with Pulsing Online Dot -->
      <div class="relative w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#25D366]/20 text-[#25D366] flex items-center justify-center shrink-0 border border-[#25D366]/30 shadow-inner">
        <svg class="w-5 h-5 sm:w-6 sm:h-6 fill-[#25D366]" viewBox="0 0 24 24">
          <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
        </svg>
        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
      </div>
      <div>
        <p class="text-xs sm:text-sm font-bold text-white flex items-center gap-1.5 flex-wrap">
          <span>₹499 Library Owner Offer</span>
          <span class="bg-[#25D366]/20 text-[#25D366] text-[10px] font-extrabold px-2 py-0.5 rounded-full border border-[#25D366]/30">Save 90%</span>
        </p>
        <p class="text-[11px] text-slate-300 hidden sm:block">1-Year App + Google SEO + 10 WhatsApp Posters</p>
      </div>
    </div>
    <div class="flex items-center gap-1.5 sm:gap-2">
      <a href="https://wa.me/918386992953?text=Hi%20FindStudyRoom%20team!%20I%20want%20to%20list%20my%20library%20under%20the%20%E2%82%B9499%20introductory%20offer.%20Please%20share%20the%20package%20details." target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 bg-[#25D366] hover:bg-[#20ba59] text-slate-950 font-black px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm transition-all shadow-md shadow-[#25D366]/30 transform hover:scale-105 shrink-0" style="background-color: #25D366 !important; color: #020617 !important;">
        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
          <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
        </svg>
        <span>Claim on WhatsApp</span>
      </a>
      <button type="button" onclick="dismissStickyBar()" class="text-slate-400 hover:text-white p-1 rounded-lg transition-colors cursor-pointer" title="Close floating bar">
        <span class="material-symbols-outlined text-[18px]">close</span>
      </button>
    </div>
  </div>

</div>

<!-- SCRIPT FOR FORM SUBMISSION, FAQ ACCORDION, COUNTDOWN & STICKY BAR -->
<script>
  // 1. Live Countdown Timer Simulation (Persistent for urgency)
  (function initCountdown() {
    let targetTime = localStorage.getItem('fsr_offer_countdown');
    if (!targetTime || new Date().getTime() > parseInt(targetTime)) {
      targetTime = new Date().getTime() + (2 * 24 * 60 * 60 * 1000) + (14 * 60 * 60 * 1000);
      localStorage.setItem('fsr_offer_countdown', targetTime);
    }

    function updateTimer() {
      const now = new Date().getTime();
      const difference = parseInt(targetTime) - now;

      if (difference <= 0) return;

      const days = Math.floor(difference / (1000 * 60 * 60 * 24));
      const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((difference % (1000 * 60)) / 1000);

      const dEl = document.getElementById('countdownDays');
      const hEl = document.getElementById('countdownHours');
      const mEl = document.getElementById('countdownMins');
      const sEl = document.getElementById('countdownSecs');

      if (dEl) dEl.textContent = String(days).padStart(2, '0');
      if (hEl) hEl.textContent = String(hours).padStart(2, '0');
      if (mEl) mEl.textContent = String(minutes).padStart(2, '0');
      if (sEl) sEl.textContent = String(seconds).padStart(2, '0');
    }

    setInterval(updateTimer, 1000);
    updateTimer();
  })();

  // 2. FAQ Accordion Toggle
  function toggleFaqAccordion(button) {
    const body = button.nextElementSibling;
    const icon = button.querySelector('.faq-icon-rotate');
    const isExpanded = !body.classList.contains('hidden');

    document.querySelectorAll('#faqAccordion .faq-body').forEach(b => b.classList.add('hidden'));
    document.querySelectorAll('#faqAccordion .faq-icon-rotate').forEach(i => i.classList.remove('rotate-180-active'));

    if (!isExpanded) {
      body.classList.remove('hidden');
      if (icon) icon.classList.add('rotate-180-active');
    }
  }

  // 3. Sticky WhatsApp Action Bar Trigger (Stays visible across the page)
  let stickyBarDismissed = false;
  function dismissStickyBar() {
    stickyBarDismissed = true;
    const bar = document.getElementById('stickyFloatingBar');
    if (bar) bar.style.display = 'none';
  }

  function revealStickyBar() {
    if (stickyBarDismissed) return;
    const bar = document.getElementById('stickyFloatingBar');
    if (bar) {
      bar.classList.remove('hidden-bar');
    }
  }

  // Auto-show after 1 second on page load so it's always ready
  setTimeout(revealStickyBar, 1000);

  // Keep visible as user scrolls
  window.addEventListener('scroll', function() {
    if (stickyBarDismissed) return;
    revealStickyBar();
  });

  // 4. Enquiry Form Submission Handler
  function handleOfferFormSubmit(event) {
    event.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const successMsg = document.getElementById('formSuccessMessage');

    const ownerName = document.getElementById('ownerName').value.trim();
    const libraryName = document.getElementById('libraryName').value.trim();
    const mobileNumber = document.getElementById('mobileNumber').value.trim();
    const city = document.getElementById('cityHub').value;
    const address = document.getElementById('address').value.trim();
    const seats = document.getElementById('seatCount').value;

    btnText.textContent = 'Recording your details...';
    btnSpinner.classList.remove('hidden');
    submitBtn.disabled = true;

    try {
      fetch("{{ route('contact.store') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
          name: ownerName,
          phone: mobileNumber,
          message: `Special ₹499 Offer Enquiry for ${libraryName} in ${city}. Address: ${address}. Capacity: ${seats}`
        })
      }).catch(function(){});
    } catch(e) {}

    setTimeout(() => {
      btnText.textContent = 'Enquiry Registered!';
      btnSpinner.classList.add('hidden');
      successMsg.classList.remove('hidden');

      const encodedMsg = encodeURIComponent(
        `Hi FindStudyRoom team!\n\nI just filled the ₹499 Introductory Offer enquiry form:\n• Name: ${ownerName}\n• Library: ${libraryName}\n• City: ${city}\n• Address: ${address}\n• Desks: ${seats}\n• Phone: ${mobileNumber}\n\nPlease confirm my ₹499 offer slot!`
      );

      setTimeout(() => {
        window.open(`https://wa.me/918386992953?text=${encodedMsg}`, '_blank', 'noopener,noreferrer');
      }, 1200);

    }, 800);
  }
</script>
@endsection
