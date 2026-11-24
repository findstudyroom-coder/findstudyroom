@extends('master')

@php
    $cName = $coaching->coaching_name ?? 'The Movement';
    $cType = $coaching->coaching_type ?? 'Hybrid Dojo & Mindfulness Sanctuary';
    $cCity = $coaching->city ?? 'Alwar';
    $cPhone = preg_replace('/[^0-9]/', '', $coaching->whatsapp_number ?? $coaching->mobile_number ?? '919876543210');
    $cAddress = $coaching->full_address ?? ($coaching->area_locality ? $coaching->area_locality . ', ' . $cCity : 'Nehru Park Enclave, Alwar, RJ');
@endphp

@section('title', $cName . ' — ' . $cType . ' | ' . $cCity)
@section('meta_description', $coaching->short_description ?? ($coaching->description ?? ('Operating as ' . $cCity . '\'s flagship movement sanctuary, ' . $cName . ' bridges traditional martial arts with restorative yogic physiology.')))

@push('head')
<style>
    .glass-panel {
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.85);
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-panel:hover {
        border-color: rgba(59, 130, 246, 0.35);
        box-shadow: 0 20px 40px -10px rgba(37, 99, 235, 0.08), 0 10px 15px -3px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
    }
    .schedule-row:hover td {
        background-color: rgba(239, 246, 255, 0.5);
    }
</style>
@endpush

@section('content')
<div class="text-slate-800 antialiased min-h-screen pb-20 bg-slate-50/50">

    <!-- HERO & TOP GRID SECTION (BACKGROUND IMAGE AT 0.8 OPACITY ONLY BEHIND THIS GRID) -->
    <div class="relative overflow-hidden border-b border-slate-200/90">
        
        <!-- Background Image Layer (Opacity 0.8) - Only behind this top grid -->
        <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden" aria-hidden="true">
            <img 
                src="https://images.unsplash.com/photo-1545205597-3d9d02c29597?auto=format&fit=crop&w=2400&q=85" 
                alt="The Movement Sanctuary Dojo" 
                class="w-full h-full object-cover object-center"
                style="opacity: 0.8;"
            >
            <!-- Soft subtle tint so white cards float with maximum contrast and elegance -->
            <div class="absolute inset-0 bg-gradient-to-b from-white/40 via-white/20 to-white/50 backdrop-blur-[0.5px]"></div>
        </div>

        <!-- Relative Content Layer for Hero & Top Grid (z-10) -->
        <div class="relative z-10">

            <!-- Sub-Navbar / Breadcrumb & Status Ribbon -->
            <div class="bg-white/90 backdrop-blur-xl border-b border-slate-200/80 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2 text-slate-500">
                        <a href="{{ url('/') }}" class="hover:text-blue-600 transition font-medium">Home</a>
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-500">Fitness &amp; Martial Arts</span>
                        <span class="text-slate-300">/</span>
                        <a href="{{ route('libraries.district', 'alwar') }}" class="hover:text-blue-600 transition font-medium">{{ $cCity }}</a>
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-900 font-bold">{{ $cName }}</span>
                    </div>
                    
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-mono text-[11px] font-bold tracking-wider shadow-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        LIVE SCHEDULE OPEN
                    </div>
                </div>
            </div>

            <!-- Top Grid Container -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

            <!-- 1. HERO TITLE & METADATA SECTION (Exact Stitch Layout - No Top Images) -->
            <section id="overview" class="space-y-4">
                <!-- Badges line -->
                <div class="flex flex-wrap items-center gap-2.5 text-xs font-semibold">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-blue-50/95 border border-blue-200 text-blue-700 uppercase tracking-wider text-[11px] font-bold shadow-sm">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        VERIFIED FACILITY
                    </span>
                    <span class="inline-flex items-center px-3.5 py-1 rounded-full bg-white/95 border border-slate-200 text-slate-700 uppercase tracking-wider text-[11px] font-bold shadow-sm">
                        {{ strtoupper($cType) }}
                    </span>
                    <div class="flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-white/95 border border-amber-200 text-amber-800 text-[11px] font-semibold shadow-sm">
                        <span class="text-amber-500 font-bold">★★★★★</span>
                        <span class="font-bold text-slate-900">4.9</span>
                        <span class="text-slate-500">(148 reviews)</span>
                    </div>
                </div>

                <!-- Title & Quick Actions Row -->
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-4 border-b border-slate-200/80">
                    <div>
                        <h1 class="font-display text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">
                            {{ $cName }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-sm text-slate-600 mt-2.5">
                            <span class="flex items-center gap-1.5 text-slate-800 font-semibold">
                                <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $cAddress }}
                            </span>
                            <span class="text-slate-300 hidden sm:inline">•</span>
                            <span class="font-medium">Olympic Tatami Dojo</span>
                            <span class="text-slate-300 hidden sm:inline">•</span>
                            <span class="font-medium">Vinyasa &amp; Mobility Suite</span>
                        </div>
                        <div class="mt-2.5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-bold text-xs border border-blue-200 shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                8,000 Sq.Ft Certified Space
                            </span>
                        </div>
                    </div>

                    <!-- Right Action Buttons -->
                    <div class="flex items-center gap-3 flex-wrap">
                        <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied to clipboard!');" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/95 hover:bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 hover:text-slate-900 transition shadow-sm hover:shadow">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                            Share
                        </button>
                        
                        <button onclick="this.classList.toggle('text-rose-600'); this.classList.toggle('bg-rose-50');" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/95 hover:bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 hover:text-slate-900 transition shadow-sm hover:shadow">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                            Save
                        </button>

                        <a href="https://wa.me/{{ $cPhone }}?text={{ urlencode('Hello Sensei, I am inquiring about ' . $cName . '.') }}" target="_blank" class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086.159.058 1.011.477 1.184.564.173.086.289.13.332.202.043.072.043.419-.101.824z"/>
                            </svg>
                            Chat with Sensei
                        </a>
                    </div>
                </div>
            </section>

            <!-- 2. TWO-COLUMN: OVERVIEW & TRIAL BOOKING CARD (Exact Stitch 2-Column Grid) -->
            <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- LEFT 7 COLS: Institutional Overview -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="glass-panel rounded-3xl p-7 sm:p-9 space-y-6">
                        <div class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-widest text-blue-600">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            INSTITUTIONAL OVERVIEW
                        </div>

                        <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                            Master Your Body, Calm Your Mind
                        </h2>

                        <div class="space-y-4 text-slate-600 text-sm leading-relaxed">
                            @if(isset($coaching) && $coaching->description)
                                <p class="border-l-2 border-blue-500 pl-4 text-slate-700 font-medium">
                                    {!! nl2br(e($coaching->description)) !!}
                                </p>
                            @else
                                <p class="border-l-2 border-blue-500 pl-4 text-slate-700 font-medium">
                                    Operating as {{ $cCity }}'s flagship movement sanctuary, <strong>{{ $cName }}</strong> bridges the raw discipline of traditional martial arts with the deep structural restoration of yogic physiology. We believe true physical mastery is not merely offensive agility or passive flexibility, but the synchronized balance of focused explosive power and neurological calm.
                                </p>
                                <p class="text-slate-500">
                                    Housed inside an acoustically isolated 8,000 sq.ft custom-engineered facility, practitioners train on high-density Olympic-standard tatami shock mats calibrated to protect joints during rigorous throwing drills. The adjacent mindfulness sanctuary features filtered airflow, ambient temperature regulation, and natural cedar accents tailored for focused breathwork and meditative clarity.
                                </p>
                            @endif
                        </div>

                        <!-- 4 Metric Cards -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 pt-6 border-t border-slate-100">
                            <div class="p-4 bg-gradient-to-b from-blue-50/80 to-blue-50/30 rounded-2xl border border-blue-100/90 hover:border-blue-300 transition shadow-sm">
                                <div class="text-2xl sm:text-3xl font-extrabold text-blue-600 font-display">15+</div>
                                <div class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mt-1">BLACK BELT INSTRUCTORS</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Kodokan &amp; WKF Certified</div>
                            </div>

                            <div class="p-4 bg-gradient-to-b from-teal-50/80 to-teal-50/30 rounded-2xl border border-teal-100/90 hover:border-teal-300 transition shadow-sm">
                                <div class="text-2xl sm:text-3xl font-extrabold text-teal-700 font-display">800+</div>
                                <div class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mt-1">ACTIVE DISCIPLES</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Scholars &amp; Competitors</div>
                            </div>

                            <div class="p-4 bg-gradient-to-b from-emerald-50/80 to-emerald-50/30 rounded-2xl border border-emerald-100/90 hover:border-emerald-300 transition shadow-sm">
                                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 font-display">100%</div>
                                <div class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mt-1">CLIMATE CONTROLLED</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Continuous HEPA Filtration</div>
                            </div>

                            <div class="p-4 bg-gradient-to-b from-purple-50/80 to-purple-50/30 rounded-2xl border border-purple-100/90 hover:border-purple-300 transition shadow-sm">
                                <div class="text-2xl sm:text-3xl font-extrabold text-purple-600 font-display">Steam</div>
                                <div class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mt-1">RECOVERY THERMAL ZONE</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Contrast Hydrotherapy</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT 5 COLS: Book a Free Trial Card (Luxury Widget) -->
                <div id="trial" class="lg:col-span-5">
                    <div class="glass-panel rounded-3xl p-6 sm:p-8 border-2 border-blue-200/90 shadow-xl shadow-blue-500/10 space-y-5 relative">
                        
                        <div class="flex items-center justify-between text-xs pb-3 border-b border-slate-100">
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-600 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                COMPLIMENTARY SESSION
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-xs shadow-sm">
                                ₹0 Initial Due
                            </span>
                        </div>

                        <div>
                            <h3 class="font-display text-2xl font-bold text-slate-900">Book a Free Trial</h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Experience a full 60-minute session with zero financial commitment or cancellation fees.
                            </p>
                        </div>

                        <!-- Capacity meter -->
                        <div class="space-y-2 bg-slate-50/90 p-4 rounded-2xl border border-slate-200/80 text-xs">
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-slate-600 font-semibold">Capacity for this week:</span>
                                <span class="text-blue-700 font-bold">85% Reserved</span>
                            </div>
                            <div class="w-full h-2.5 bg-slate-200 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-teal-500 h-full rounded-full w-[85%]"></div>
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-slate-500">
                                <span>⚡ Only 3 trial slots left for this batch</span>
                                <span class="text-emerald-600 font-bold">Instant Allocation</span>
                            </div>
                        </div>

                        @php
                            if (!isset($commandoClasses) || $commandoClasses->isEmpty()) {
                                $commandoClasses = \App\Models\CommandoClass::where('status', 1)->orderBy('order_no', 'asc')->get();
                            }
                        @endphp

                        <!-- Trial Booking Form (Submits to Commando Classes) -->
                        <form id="commando-trial-form" method="POST" action="{{ route('commando-classes.submit') }}" class="space-y-4 text-xs">
                            @csrf
                            <input type="hidden" name="coaching_id" value="{{ $coaching->id ?? 1 }}">
                            <input type="hidden" name="coaching_name" value="{{ $cName }}">
                            <input type="hidden" name="date" value="{{ date('Y-m-d') }}">

                            <div>
                                <label class="block text-slate-700 font-semibold mb-1.5">Full Name</label>
                                <input type="text" name="name" required placeholder="e.g., Aryan Sharma" class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition font-medium shadow-sm">
                            </div>

                            <div>
                                <label class="block text-slate-700 font-semibold mb-1.5">Mobile Contact</label>
                                <div class="flex rounded-xl overflow-hidden border border-slate-200 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-50 transition shadow-sm bg-white">
                                    <span class="px-3.5 py-3 bg-slate-50 text-slate-600 border-r border-slate-200 font-bold">+91</span>
                                    <input type="tel" name="phone" required placeholder="98765 43210" pattern="[0-9]{10}" class="w-full px-3.5 py-3 bg-transparent text-slate-900 placeholder-slate-400 focus:outline-none font-medium">
                                </div>
                            </div>

                            <div>
                                <label class="block text-slate-700 font-semibold mb-1.5">Primary Discipline</label>
                                <select name="discipline" class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition font-medium shadow-sm">
                                    @if(isset($commandoClasses) && $commandoClasses->count() > 0)
                                        @foreach($commandoClasses as $cc)
                                            <option value="{{ $cc->title }}">{{ $cc->title }}</option>
                                        @endforeach
                                    @else
                                        <option value="Shotokan Karate">Shotokan Karate</option>
                                        <option value="Kodokan Judo">Kodokan Judo</option>
                                        <option value="Vinyasa & Mobility">Vinyasa & Mobility</option>
                                    @endif
                                    <option value="Combined Hybrid Pass">Combined Hybrid Pass</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-slate-700 font-semibold mb-1.5">Preferred Slot Window</label>
                                <div class="grid grid-cols-2 gap-2.5">
                                    <label class="flex items-center gap-2.5 p-3 rounded-xl bg-white border border-blue-300 cursor-pointer hover:bg-blue-50/50 transition shadow-sm">
                                        <input type="radio" name="slot" value="dawn" checked class="text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <div class="text-slate-900 font-bold text-xs">🌅 Dawn</div>
                                            <div class="text-[10px] text-slate-500">06:30 AM</div>
                                        </div>
                                    </label>
                                    <label class="flex items-center gap-2.5 p-3 rounded-xl bg-white border border-slate-200 cursor-pointer hover:bg-blue-50/50 transition shadow-sm">
                                        <input type="radio" name="slot" value="dusk" class="text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <div class="text-slate-900 font-bold text-xs">🌙 Dusk</div>
                                            <div class="text-[10px] text-slate-500">07:00 PM</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" id="trial-submit-btn" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold text-sm tracking-wide shadow-lg shadow-blue-600/30 hover:shadow-xl hover:shadow-blue-600/40 transition transform active:scale-[0.99] flex items-center justify-center gap-2 group mt-2">
                                <span id="trial-btn-text">Submit Trial Request</span>
                                <svg class="w-4 h-4 group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>

                            <div id="trial-success" class="hidden p-3.5 bg-emerald-50 border border-emerald-300 rounded-xl text-emerald-800 text-center font-semibold text-xs shadow-sm">
                                ✓ Trial slot booked! Sensei desk will confirm via WhatsApp within 24 hours.
                            </div>

                            <div id="trial-error" class="hidden p-3.5 bg-rose-50 border border-rose-300 rounded-xl text-rose-800 text-center font-semibold text-xs shadow-sm"></div>

                            <!-- Trust bullets -->
                            <div class="pt-3.5 border-t border-slate-100 space-y-2 text-[11px] text-slate-500">
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-[10px]">✓</div>
                                    <span>100% Free • No credit card required</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-[10px]">✓</div>
                                    <span>Direct confirmation within 24 operational hours</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-[10px]">✓</div>
                                    <span>Audited under FindStudyRoom Safety Protocols</span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- REST OF THE PAGE: CLEAN LIGHT THEME (NO BACKGROUND IMAGE) -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

    <!-- 3. CORE DISCIPLINES & LOCATION INFO (Dynamic Commando Classes) -->
    @php
        if (!isset($commandoClasses) || $commandoClasses->isEmpty()) {
            $commandoClasses = \App\Models\CommandoClass::where('status', 1)->orderBy('order_no', 'asc')->get();
        }
        $studioLocation = $commandoClasses->firstWhere('location_address', '!=', null) ?? $commandoClasses->first();
    @endphp
    <section id="disciplines" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- 8 Cols: Core Disciplines Cards -->
            <div class="lg:col-span-8 space-y-6">
                <div>
                    <div class="text-[10px] font-extrabold uppercase tracking-widest text-blue-600 mb-1">CURRICULUM MODULES</div>
                    <h2 class="font-display text-2xl font-bold text-slate-900">Core Disciplines</h2>
                    <p class="text-xs text-slate-500 mt-1">Structured progressions from fundamentals to advanced application</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                    @forelse($commandoClasses as $cc)
                        @php
                            $theme = $cc->theme_config;
                        @endphp
                        <div class="glass-panel rounded-3xl p-6 flex flex-col justify-between group">
                            <div>
                                <div class="w-12 h-12 rounded-2xl {{ $theme['icon_bg'] }} border flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-sm">
                                    {!! $cc->rendered_icon !!}
                                </div>
                                <h3 class="font-display text-lg font-bold text-slate-900 {{ $theme['hover_title'] }} transition">{{ $cc->title }}</h3>
                                @if($cc->tag)
                                    <div class="text-[10px] font-extrabold {{ $theme['tag_text'] }} uppercase tracking-wider mt-0.5 mb-2.5">{{ $cc->tag }}</div>
                                @endif
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    {{ $cc->description }}
                                </p>
                            </div>
                            <div class="pt-4 mt-5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-mono">
                                <span class="font-bold text-slate-800">⏱ {{ $cc->duration ?: '60 MIN' }}</span>
                                <span class="px-2.5 py-0.5 rounded-full {{ $theme['badge'] }} font-bold border">{{ $cc->level ?: 'ALL BELT TIERS' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-10 bg-slate-50 rounded-2xl border border-slate-200 text-slate-500 text-xs">
                            🥋 Commando classes are currently being scheduled. Check back shortly.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 4 Cols: Location & Operational Info Box -->
            <div class="lg:col-span-4">
                <div class="glass-panel rounded-3xl p-6 sm:p-7 space-y-5">
                    
                    <!-- Address -->
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-900">
                            <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span>Location Address</span>
                        </div>
                        <p class="text-xs text-slate-600 pl-6 leading-relaxed">
                            {{ $studioLocation->location_address ?? 'Plot 14-B, Sector 3, Near Nehru Park Promenade, Alwar, Rajasthan 301001' }}
                        </p>
                    </div>

                    <!-- Timings -->
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-900">
                            <svg class="w-4 h-4 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Operational Window</span>
                        </div>
                        <p class="text-xs text-slate-600 pl-6">
                            {{ $studioLocation->timings ?? 'Mon - Sun: 06:00 AM - 09:30 PM (IST)' }}
                        </p>
                    </div>

                    <!-- Direct Desk -->
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-900">
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>Dojo Direct Desk</span>
                        </div>
                        @php
                            $deskPhone = $studioLocation->phone ?? '+91 (0144) 283-9901';
                            $deskPhoneClean = preg_replace('/[^0-9+]/', '', $deskPhone);
                        @endphp
                        <p class="text-xs text-blue-700 font-mono pl-6 font-bold">
                            <a href="tel:{{ $deskPhoneClean }}" class="hover:underline">{{ $deskPhone }}</a>
                        </p>
                    </div>

                    <!-- Clean Stylized Map Card -->
                    <div class="relative rounded-2xl overflow-hidden border border-slate-200 h-44 bg-slate-100 group shadow-inner">
                        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:16px_16px]"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="relative">
                                <div class="w-12 h-12 rounded-full bg-blue-500/20 animate-ping absolute -inset-0"></div>
                                <div class="w-12 h-12 rounded-full bg-blue-600 border-2 border-white flex items-center justify-center shadow-lg shadow-blue-500/40">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/></svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="absolute bottom-3 inset-x-3">
                            <a href="{{ $studioLocation->map_url ?? 'https://maps.google.com/?q=Nehru+Park+Alwar+Rajasthan' }}" target="_blank" class="w-full py-2.5 px-3 rounded-xl bg-white hover:bg-slate-900 hover:text-white text-slate-800 text-xs font-bold text-center border border-slate-200 transition shadow-sm flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                OPEN IN GOOGLE MAPS
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- 4. MEET THE MASTER INSTRUCTORS (Dynamic) -->
    @php
        if (!isset($instructors) || $instructors->isEmpty()) {
            $instructors = \App\Models\CommandoInstructor::where('status', 1)->orderBy('order_no', 'asc')->get();
        }
    @endphp
    <section id="instructors" class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <div class="text-[10px] font-extrabold uppercase tracking-widest text-blue-600 mb-1">LINEAGE &amp; MASTERY</div>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900">Meet the Master Instructors</h2>
            </div>
            <p class="text-xs text-slate-500 max-w-md">
                Kodokan &amp; WKF certified black belts and veteran yogic practitioners offering personalized guidance.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($instructors as $inst)
                @php
                    $theme = $inst->theme_config;
                @endphp
                <div class="glass-panel rounded-3xl overflow-hidden flex flex-col justify-between group">
                    <div>
                        <div class="h-44 bg-gradient-to-tr {{ $theme['gradient'] }} border-b border-slate-100 relative p-4 flex items-center justify-center overflow-hidden">
                            <img src="{{ $inst->image_url }}" alt="{{ $inst->name }}" class="absolute inset-0 w-full h-full object-cover opacity-85 group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-950/20 to-transparent"></div>
                            @if($inst->rank_badge)
                                <span class="absolute top-3 right-3 px-3 py-1 rounded-full {{ $theme['rank_badge'] }} font-mono text-[10px] font-extrabold uppercase shadow-md">
                                    {{ $inst->rank_badge }}
                                </span>
                            @endif
                        </div>

                        <div class="p-6 space-y-3">
                            <div>
                                <h3 class="font-display text-lg font-bold text-slate-900">{{ $inst->name }}</h3>
                                @if($inst->role_title)
                                    <div class="text-[10px] font-extrabold {{ $theme['role_text'] }} uppercase tracking-wider mt-0.5">{{ $inst->role_title }}</div>
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                {{ $inst->bio }}
                            </p>
                            @if(!empty($inst->specialties_list) || $inst->experience)
                                <div class="flex flex-wrap gap-1.5 pt-2">
                                    @foreach($inst->specialties_list as $specialty)
                                        <span class="px-2.5 py-1 bg-slate-100 border border-slate-200 text-slate-700 text-[11px] rounded-lg font-medium">{{ $specialty }}</span>
                                    @endforeach
                                    @if($inst->experience)
                                        <span class="px-2.5 py-1 {{ $theme['exp_badge'] }} text-[11px] rounded-lg font-bold">{{ $inst->experience }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="px-6 pb-6">
                        <button onclick="document.getElementById('trial').scrollIntoView({behavior: 'smooth'})" class="w-full py-2.5 rounded-xl bg-slate-100 hover:bg-slate-900 hover:text-white border border-slate-200 text-slate-800 text-xs font-bold transition shadow-sm">
                            View Slots &amp; Bio &gt;
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-10 bg-slate-50 rounded-2xl border border-slate-200 text-slate-500 text-xs">
                    🥋 Master instructors profile directory will be published shortly.
                </div>
            @endforelse
        </div>
    </section>

            <!-- 5. VERIFIED CLASS SCHEDULE / TIMETABLE (Dynamic) -->
            @php
                if (!isset($schedules) || $schedules->isEmpty()) {
                    $schedules = \App\Models\CommandoSchedule::where('status', 1)->orderBy('order_no', 'asc')->get();
                }
            @endphp
            <section id="schedule" class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <div class="text-[10px] font-extrabold uppercase tracking-widest text-blue-600 mb-1">WEEKLY MATRIX</div>
                        <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900">Verified Class Schedule</h2>
                    </div>

                    <!-- Window Filters -->
                    <div class="flex items-center gap-1.5 bg-white/90 backdrop-blur-md p-1 rounded-2xl border border-slate-200 text-xs shadow-sm">
                        <button onclick="filterSchedule('all', this)" class="schedule-tab px-3.5 py-1.5 rounded-xl bg-blue-600 text-white font-bold transition shadow-sm">All Windows</button>
                        <button onclick="filterSchedule('dawn', this)" class="schedule-tab px-3.5 py-1.5 rounded-xl text-slate-600 hover:text-slate-900 font-medium transition">Dawn Flow</button>
                        <button onclick="filterSchedule('combat', this)" class="schedule-tab px-3.5 py-1.5 rounded-xl text-slate-600 hover:text-slate-900 font-medium transition">Dusk Combat</button>
                    </div>
                </div>

                <!-- Schedule Matrix Table -->
                <div class="glass-panel rounded-3xl overflow-hidden shadow-md">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-700 uppercase tracking-wider text-[11px] font-bold">
                                    <th class="py-4 px-6 font-mono text-slate-500 w-32">DAY</th>
                                    <th class="py-4 px-6 col-dawn">DAWN ROUTINE (06:30 - 08:30)</th>
                                    <th class="py-4 px-6 col-cadet">AFTERNOON CADETS (16:30 - 18:00)</th>
                                    <th class="py-4 px-6 col-combat">COMBAT PRIME (19:00 - 21:00)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($schedules as $sched)
                                    <tr class="schedule-row transition">
                                        <td class="py-4 px-6 font-bold text-slate-900 font-mono bg-slate-50/40">{{ $sched->day }}</td>
                                        <td class="py-4 px-6 col-dawn">
                                            @if($sched->dawn_title)
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <div class="font-bold text-slate-900">{{ $sched->dawn_title }}</div>
                                                        <div class="text-[11px] text-slate-500 mt-0.5">{{ $sched->dawn_subtitle }}</div>
                                                    </div>
                                                    <span class="font-mono text-blue-700 font-bold text-[11px] bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-200">{{ $sched->dawn_time }}</span>
                                                </div>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 col-cadet">
                                            @if($sched->cadet_title)
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <div class="font-bold text-slate-900">{{ $sched->cadet_title }}</div>
                                                        <div class="text-[11px] text-slate-500 mt-0.5">{{ $sched->cadet_subtitle }}</div>
                                                    </div>
                                                    <span class="font-mono text-teal-700 font-bold text-[11px] bg-teal-50 px-2.5 py-0.5 rounded-full border border-teal-200">{{ $sched->cadet_time }}</span>
                                                </div>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 col-combat">
                                            @if($sched->combat_title)
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <div class="font-bold text-slate-900">{{ $sched->combat_title }}</div>
                                                        <div class="text-[11px] text-slate-500 mt-0.5">{{ $sched->combat_subtitle }}</div>
                                                    </div>
                                                    <span class="font-mono text-rose-700 font-bold text-[11px] bg-rose-50 px-2.5 py-0.5 rounded-full border border-rose-200">{{ $sched->combat_time }}</span>
                                                </div>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-500">
                                            Class schedule is being updated for the upcoming week.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- 6. MEMBERSHIP TIERS (Dynamic) -->
            @php
                if (!isset($plans) || $plans->isEmpty()) {
                    $plans = \App\Models\CommandoPlan::where('status', 1)->orderBy('order_no', 'asc')->get();
                }
            @endphp
            <section id="pricing" class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <div class="text-[10px] font-extrabold uppercase tracking-widest text-blue-600 mb-1">DIRECT INSTITUTIONAL RATES</div>
                        <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900">Membership Tiers</h2>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-slate-600 font-medium">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <span class="font-bold text-slate-800">Fixed Direct Rate Guarantee</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
                    @forelse($plans as $plan)
                        @php
                            $theme = $plan->theme_config;
                        @endphp
                        @if($plan->is_featured)
                            <!-- Featured Tier Card -->
                            <div class="glass-panel rounded-3xl p-7 sm:p-8 relative border-2 border-blue-600 bg-gradient-to-b from-blue-50/50 via-white to-white flex flex-col justify-between shadow-xl shadow-blue-500/10">
                                @if($plan->featured_badge)
                                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 text-white font-bold text-[10px] uppercase tracking-wider shadow-md">
                                        {{ $plan->featured_badge }}
                                    </div>
                                @endif

                                <div class="space-y-5 pt-2">
                                    <div>
                                        @if($plan->tag)
                                            <div class="text-[10px] font-extrabold {{ $theme['tag_color'] }} uppercase tracking-widest">{{ $plan->tag }}</div>
                                        @endif
                                        <h3 class="font-display text-2xl font-bold text-slate-900 mt-1">{{ $plan->title }}</h3>
                                        <p class="text-xs text-slate-600 mt-1">{{ $plan->subtitle }}</p>
                                    </div>

                                    <div class="flex items-baseline gap-1">
                                        <span class="text-3xl font-black text-slate-900 font-display">{{ $plan->price }}</span>
                                        <span class="text-xs text-slate-500 font-medium">{{ $plan->billing_period ?: '/month' }}</span>
                                    </div>

                                    <ul class="space-y-3 text-xs text-slate-800">
                                        @foreach($plan->features_list as $feat)
                                            <li class="flex items-start gap-2.5">
                                                <span class="w-4 h-4 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-[11px] flex-shrink-0">✓</span>
                                                <span>{{ $feat }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="pt-6 mt-6 border-t border-slate-100">
                                    <button onclick="document.getElementById('trial').scrollIntoView({behavior: 'smooth'})" class="w-full py-3.5 rounded-xl {{ $theme['button_class'] }} text-xs font-bold tracking-wider shadow-lg shadow-blue-600/30 transition">
                                        {{ $plan->button_text ?: 'Claim Elite Pass' }}
                                    </button>
                                </div>
                            </div>
                        @else
                            <!-- Standard Tier Card -->
                            <div class="glass-panel rounded-3xl p-7 sm:p-8 flex flex-col justify-between">
                                <div class="space-y-5">
                                    <div>
                                        @if($plan->tag)
                                            <div class="text-[10px] font-extrabold {{ $theme['tag_color'] }} uppercase tracking-widest">{{ $plan->tag }}</div>
                                        @endif
                                        <h3 class="font-display text-2xl font-bold text-slate-900 mt-1">{{ $plan->title }}</h3>
                                        <p class="text-xs text-slate-500 mt-1">{{ $plan->subtitle }}</p>
                                    </div>

                                    <div class="flex items-baseline gap-1">
                                        <span class="text-3xl font-black text-slate-900 font-display">{{ $plan->price }}</span>
                                        <span class="text-xs text-slate-500 font-medium">{{ $plan->billing_period ?: '/month' }}</span>
                                    </div>

                                    <ul class="space-y-3 text-xs text-slate-700">
                                        @foreach($plan->features_list as $feat)
                                            <li class="flex items-start gap-2.5">
                                                <span class="{{ $theme['check_color'] }} font-bold">✓</span>
                                                <span>{{ $feat }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="pt-6 mt-6 border-t border-slate-100">
                                    <button onclick="document.getElementById('trial').scrollIntoView({behavior: 'smooth'})" class="w-full py-3 rounded-xl {{ $theme['button_class'] }} text-xs font-bold transition shadow-sm">
                                        {{ $plan->button_text ?: 'Select Plan' }}
                                    </button>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="col-span-3 text-center py-10 bg-slate-50 rounded-2xl border border-slate-200 text-slate-500 text-xs">
                            Membership tiers will be announced shortly.
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- 7. STUDIO MOMENTS (INSTAGRAM GALLERY GRID - Dynamic) -->
            @php
                if (!isset($galleries) || $galleries->isEmpty()) {
                    $galleries = \App\Models\CommandoGallery::where('status', 1)->orderBy('order_no', 'asc')->get();
                }
            @endphp
            <section id="gallery" class="space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl">📷</span>
                            <h2 class="font-display text-xl sm:text-2xl font-bold text-slate-900">Studio Moments</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Real atmosphere, verified sparring sessions, and community discipline.</p>
                    </div>
                    <a href="{{ isset($coaching) && $coaching->instagram ? (str_starts_with($coaching->instagram, 'http') ? $coaching->instagram : 'https://instagram.com/' . ltrim($coaching->instagram, '@')) : 'https://instagram.com' }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-slate-200 text-xs font-mono text-blue-600 hover:text-blue-800 shadow-sm transition">
                        <span class="font-bold">{{ isset($coaching) && $coaching->instagram ? (str_starts_with($coaching->instagram, '@') ? $coaching->instagram : '@' . basename($coaching->instagram)) : '@themovement.alwar' }}</span>
                        <span class="text-slate-300">•</span>
                        <span class="text-slate-500 font-sans">12.4k followers</span>
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @forelse($galleries as $gal)
                        <div class="relative group rounded-2xl overflow-hidden aspect-[4/3] bg-slate-100 border border-slate-200 shadow-sm">
                            <img src="{{ $gal->image_url }}" alt="{{ $gal->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-4">
                                <span class="text-xs font-bold text-white">{{ $gal->title }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-10 bg-slate-50 rounded-2xl border border-slate-200 text-slate-500 text-xs">
                            Gallery photos will be updated soon.
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- 8. TRUST & SAFETY PROTOCOLS BANNER (Dynamic Contact) -->
            <section class="rounded-3xl p-7 sm:p-9 text-white shadow-xl overflow-hidden bg-cover bg-center" style="background-image: linear-gradient(to right, rgba(37, 99, 235, 0.94), rgba(79, 70, 229, 0.94), rgba(13, 148, 136, 0.94)), url('https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&w=1600&q=80');">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="space-y-2 text-center md:text-left">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-[11px] font-bold uppercase tracking-wider">
                            🛡️ 100% Certified Facility
                        </div>
                        <h3 class="font-display text-2xl font-bold">Audited Under FindStudyRoom Standards</h3>
                        <p class="text-xs text-blue-100 max-w-xl">
                            Cleanliness inspected weekly, certified instructors with clean background verification, and transparent direct pricing without registration surcharges.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="document.getElementById('trial').scrollIntoView({behavior: 'smooth'})" class="px-5 py-3 rounded-xl bg-white text-blue-700 font-bold text-xs hover:bg-blue-50 transition shadow-md">
                            Book Complimentary Session
                        </button>
                        <a href="tel:{{ $deskPhoneClean ?? '01442839901' }}" class="px-5 py-3 rounded-xl bg-black/20 hover:bg-black/30 backdrop-blur-md text-white font-bold text-xs border border-white/20 transition">
                            Call Desk
                        </a>
                    </div>
                </div>
            </section>

    </div>
</div>

<!-- Interactive JS for Schedule Filter & Commando Classes Trial Booking -->
<script>
    function filterSchedule(windowType, btn) {
        document.querySelectorAll('.schedule-tab').forEach(b => {
            b.className = 'schedule-tab px-3.5 py-1.5 rounded-xl text-slate-600 hover:text-slate-900 font-medium transition';
        });
        btn.className = 'schedule-tab px-3.5 py-1.5 rounded-xl bg-blue-600 text-white font-bold transition shadow-sm';

        const dawnCols = document.querySelectorAll('.col-dawn');
        const cadetCols = document.querySelectorAll('.col-cadet');
        const combatCols = document.querySelectorAll('.col-combat');

        if (windowType === 'dawn') {
            dawnCols.forEach(el => el.classList.remove('hidden'));
            cadetCols.forEach(el => el.classList.add('hidden'));
            combatCols.forEach(el => el.classList.add('hidden'));
        } else if (windowType === 'combat') {
            dawnCols.forEach(el => el.classList.add('hidden'));
            cadetCols.forEach(el => el.classList.remove('hidden'));
            combatCols.forEach(el => el.classList.remove('hidden'));
        } else {
            dawnCols.forEach(el => el.classList.remove('hidden'));
            cadetCols.forEach(el => el.classList.remove('hidden'));
            combatCols.forEach(el => el.classList.remove('hidden'));
        }
    }

    // Dynamic AJAX submission for Commando Classes Free Trial
    document.addEventListener('DOMContentLoaded', function() {
        const trialForm = document.getElementById('commando-trial-form');
        const successBox = document.getElementById('trial-success');
        const errorBox = document.getElementById('trial-error');
        const submitBtn = document.getElementById('trial-submit-btn');
        const btnText = document.getElementById('trial-btn-text');

        if (trialForm) {
            trialForm.addEventListener('submit', function(e) {
                e.preventDefault();
                if (successBox) successBox.classList.add('hidden');
                if (errorBox) errorBox.classList.add('hidden');
                
                if (submitBtn) submitBtn.disabled = true;
                const originalText = btnText ? btnText.textContent : 'Submit Trial Request';
                if (btnText) btnText.textContent = 'Reserving your trial session...';

                const formData = new FormData(trialForm);

                fetch("{{ route('commando-classes.submit') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(async response => {
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        throw data;
                    }
                    return data;
                })
                .then(data => {
                    if (successBox) {
                        successBox.classList.remove('hidden');
                        successBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                    trialForm.reset();
                    if (btnText) btnText.textContent = 'Trial Slot Reserved!';
                    setTimeout(() => {
                        if (submitBtn) submitBtn.disabled = false;
                        if (btnText) btnText.textContent = originalText;
                    }, 4000);
                })
                .catch(err => {
                    if (submitBtn) submitBtn.disabled = false;
                    if (btnText) btnText.textContent = originalText;
                    let errorMsg = 'Could not reserve session. Please verify your contact details and try again.';
                    if (err && err.errors) {
                        const firstKey = Object.keys(err.errors)[0];
                        errorMsg = err.errors[firstKey][0];
                    } else if (err && err.message) {
                        errorMsg = err.message;
                    }
                    if (errorBox) {
                        errorBox.textContent = '⚠ ' + errorMsg;
                        errorBox.classList.remove('hidden');
                        errorBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    } else {
                        alert(errorMsg);
                    }
                });
            });
        }
    });
</script>
@endsection
