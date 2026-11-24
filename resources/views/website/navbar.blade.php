<nav class="fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md z-50 border-b border-border">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="relative flex items-center justify-between h-16">
      <div class="flex items-center gap-2">
        <a href="{{ url('/') }}" class="flex items-center gap-2">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center">
            <svg viewBox="0 0 100 120" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <linearGradient id="pinGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                  <stop offset="0%" style="stop-color:#14B8A6" />
                  <stop offset="100%" style="stop-color:#14B8A6" />
                </linearGradient>
              </defs>

              <path d="M50 110C35 95 15 75 15 50C15 30.67 30.67 15 50 15C69.33 15 85 30.67 85 50C85 75 65 95 50 110Z"
                    fill="url(#pinGradient)" />

              <circle cx="50" cy="50" r="22" fill="white" />

              <path d="M50 58V40C46 38 42 38 38 40V58C42 56 46 56 50 58Z"
                    fill="#4ADE80" stroke="#333" stroke-width="0.5" />

              <path d="M50 58V40C54 38 58 38 62 40V58C58 56 54 56 50 58Z"
                    fill="#F87171" stroke="#333" stroke-width="0.5" />
            </svg>
          </div>
          <span class="font-display font-bold text-xl text-fg">Findstudyroom</span>
        </a>

        <!-- Desktop Nav -->
        <div class="hidden md:flex items-center gap-8 absolute left-0 right-0 justify-center pointer-events-none">
          <div class="pointer-events-auto flex items-center gap-8">
            <a href="{{ url('/') }}" class="nav-link text-sm font-medium {{ request()->is('/') ? 'text-teal-600 font-bold active' : 'text-muted hover:text-fg' }}">Home</a>
            <a href="{{ route('libraries.all') }}" class="nav-link text-sm font-medium {{ request()->routeIs('libraries.*') ? 'text-teal-600 font-bold active' : 'text-muted hover:text-fg' }}">Library</a>
            <a href="{{ route('sports-hobby') }}" class="nav-link text-sm font-medium {{ request()->routeIs('sports-hobby') || request()->routeIs('coaching.*') || request()->routeIs('the-movement') ? 'text-teal-600 font-bold active' : 'text-muted hover:text-fg' }}">Sports &amp; Hobby</a>
            <a href="{{ route('blogs.index') }}" class="nav-link text-sm font-medium {{ request()->routeIs('blogs.*') ? 'text-teal-600 font-bold active' : 'text-muted hover:text-fg' }}">Blogs</a>
            <a href="{{ route('contact.us') }}" class="nav-link text-sm font-medium {{ request()->routeIs('contact.us') ? 'text-teal-600 font-bold active' : 'text-muted hover:text-fg' }}">Contact Us</a>
          </div>
        </div>
      </div>

      <div class="flex items-center gap-3 ml-auto">
        <div class="hidden md:flex items-center">
          <a href="{{ route('offer') }}" class="inline-flex items-center gap-1.5 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white text-xs font-semibold px-3.5 py-2 rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5">
            <span>List Library</span>
            <!-- <span class="bg-amber-400 text-slate-900 text-[10px] font-extrabold px-1.5 py-0.5 rounded-full">₹299</span>
         -->
          </a>
        </div>

        <button
          id="mobileMenuButton"
          type="button"
          onclick="toggleMobileMenu()"
          class="md:hidden p-2 rounded-lg hover:bg-gray-100"
          aria-label="Toggle menu"
          aria-controls="mobileMenu"
          aria-expanded="false"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- Mobile Menu -->
<div id="mobileMenu" class="mobile-menu fixed top-16 left-0 h-[calc(100vh-4rem)] transform -translate-x-full w-72 max-w-full bg-white shadow-xl md:hidden z-50 overflow-y-auto" aria-hidden="true">
  <div class="p-4 border-b border-border flex justify-between items-center">
    <span class="font-display font-bold text-lg">Menu</span>
    <button type="button" onclick="toggleMobileMenu()" class="p-2 rounded-lg hover:bg-gray-100" aria-label="Close menu">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>

  <div class="p-4 flex flex-col gap-2">
    <a href="{{ url('/') }}" onclick="toggleMobileMenu();" class="px-4 py-3 rounded-lg hover:bg-soft-teal text-fg font-medium">Home</a>
    <a href="{{ route('libraries.all') }}" onclick="toggleMobileMenu();" class="px-4 py-3 rounded-lg hover:bg-soft-teal text-fg font-medium">Library</a>
    <a href="{{ route('sports-hobby') }}" onclick="toggleMobileMenu();" class="px-4 py-3 rounded-lg hover:bg-soft-teal text-fg font-medium flex items-center justify-between {{ request()->routeIs('sports-hobby') ? 'bg-soft-teal text-teal-600 font-bold' : '' }}">
      <div class="flex items-center gap-2">
        <span>🥋 Sports &amp; Hobby</span>
      </div>
      <span class="bg-gradient-to-r from-teal-500 to-emerald-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow-sm">New</span>
    </a>
    <a href="{{ route('offer') }}" onclick="toggleMobileMenu();" class="px-4 py-3 rounded-lg hover:bg-orange-50 text-orange-600 font-semibold flex items-center justify-between {{ request()->routeIs('offer') ? 'bg-orange-50' : '' }}">
      <div class="flex items-center gap-2">
        <span>⚡ Special Offer</span>
      </div>
      <span class="bg-gradient-to-r from-orange-500 to-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm">₹299 Listing</span>
    </a>
    <a href="{{ route('blogs.index') }}" onclick="toggleMobileMenu();" class="px-4 py-3 rounded-lg hover:bg-soft-teal text-fg font-medium">Blogs</a>
    <a href="{{ route('contact.us') }}" onclick="toggleMobileMenu();" class="px-4 py-3 rounded-lg hover:bg-soft-teal text-fg font-medium">Contact Us</a>
    <hr class="my-2 border-border">

    <!-- @auth
      <span class="px-4 py-3 rounded-lg bg-soft-teal text-fg font-medium">Hi, {{ Auth::user()->name }}</span>
      <a href="{{ url('/') }}" onclick="toggleMobileMenu();" class="px-4 py-3 rounded-lg hover:bg-soft-teal text-fg font-medium">Home</a>
      <form method="POST" action="{{ route('auth.logout') }}">
        @csrf
        <button type="submit" class="btn-primary mt-4 px-5 py-3 rounded-xl font-semibold text-white w-full">Logout</button>
      </form>
    @else
      <div class="mt-2 overflow-hidden rounded-xl border border-border bg-white shadow-sm">
        <a href="{{ route('auth.login') }}" onclick="toggleMobileMenu();" class="block px-4 py-3 text-center text-fg font-medium hover:bg-soft-teal">Login</a>
        <div class="h-px bg-border"></div>
        <a href="{{ route('auth.register') }}" onclick="toggleMobileMenu();" class="btn-primary block px-5 py-3 text-center font-semibold text-white">Sign Up</a>
      </div>
      <p class="px-4 pt-2 text-sm text-muted">Already have an account? <a href="{{ route('auth.login') }}" onclick="toggleMobileMenu();" class="text-accent font-semibold">Login</a></p>
    @endauth -->
  </div>
</div>

<!-- mobile toggle script moved to footer for reliability -->
