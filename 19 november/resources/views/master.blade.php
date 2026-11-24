<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Findstudyroom - Find the Best Tutors Near You')</title>
  <meta name="description" content="@yield('meta_description', 'Find the best tutors near you for board exam preparation, JEE, NEET, and home tuition. Discover trusted local tutors across subjects and classes.')">
  <meta name="keywords" content="@yield('meta_keywords', 'tutors near me, online tutor, home tutor, JEE coaching, NEET coaching, board exam tutor, local tutor, best tutor')">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="@yield('canonical', url()->current())">
  <meta property="og:title" content="@yield('og_title', 'Findstudyroom - Find the Best Tutors Near You')" />
  <meta property="og:description" content="@yield('og_description', 'Find the best tutors near you for board exam preparation, JEE, NEET, and home tuition. Discover trusted local tutors across subjects and classes.')" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:image" content="{{ asset('public/uploads/og-image.png') }}" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="@yield('twitter_title', 'Findstudyroom - Find the Best Tutors Near You')" />
  <meta name="twitter:description" content="@yield('twitter_description', 'Find the best tutors near you for board exam preparation, JEE, NEET, and home tuition. Discover trusted local tutors across subjects and classes.')" />
  @stack('head')
      <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('public/uploads/fav.svg') }}">
  <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['Space Grotesk', 'sans-serif'],
            body: ['DM Sans', 'sans-serif'],
          },
          colors: {
            bg: '#FAFAF8',
            fg: '#1A1A2E',
            muted: '#6B7280',
            accent: '#0D9488',
            'accent-light': '#14B8A6',
            coral: '#F97316',
            card: '#FFFFFF',
            border: '#E5E7EB',
            'soft-teal': '#F0FDFA',
            'fsr-primary': '#001229',
            'fsr-secondary': '#006c4a',
            'fsr-surface': '#f8faff',
            'fsr-surface-low': '#f0f4fd',
            'fsr-muted': '#526075',
          }
        }
      }
    }
  </script>
  <style>
    :root {
      --bg: #FAFAF8;
      --fg: #1A1A2E;
      --muted: #6B7280;
      --accent: #0D9488;
      --accent-light: #14B8A6;
      --coral: #F97316;
      --card: #FFFFFF;
      --border: #E5E7EB;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg);
      color: var(--fg);
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: 'Space Grotesk', sans-serif;
    }

    .page {
      display: none;
      opacity: 0;
      transform: translateY(20px);
    }

    .page.active {
      display: block;
      animation: fadeIn 0.5s ease forwards;
    }

    @keyframes fadeIn {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    @keyframes pulse {
      0%, 100% { opacity: 0.4; }
      50% { opacity: 0.8; }
    }

    .animate-slide-up {
      animation: slideUp 0.6s ease forwards;
    }

    .animate-float {
      animation: float 6s ease-in-out infinite;
    }

    .stagger-1 { animation-delay: 0.1s; }
    .stagger-2 { animation-delay: 0.2s; }
    .stagger-3 { animation-delay: 0.3s; }
    .stagger-4 { animation-delay: 0.4s; }

    .gradient-blob {
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.4;
      pointer-events: none;
    }

    .card-hover {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-hover:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 40px -12px rgba(13, 148, 136, 0.15);
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px -8px rgba(13, 148, 136, 0.5);
    }

    .btn-coral {
      background: linear-gradient(135deg, var(--coral) 0%, #FB923C 100%);
    }

    .btn-coral:hover {
      box-shadow: 0 8px 24px -8px rgba(249, 115, 22, 0.5);
    }

    .nav-link {
      position: relative;
    }

    .nav-link::after {
      content: '';
      position: absolute;
      bottom: -4px;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--accent);
      transition: width 0.3s ease;
    }

    .nav-link:hover::after,
    .nav-link.active::after {
      width: 100%;
    }

    .search-input:focus {
      box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.15);
    }

    .rating-star {
      color: #FBBF24;
    }

    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }

    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
      }
    }

    .mobile-menu {
      transform: translateX(-100%);
      transition: transform 0.3s ease;
    }

    .mobile-menu.open {
      transform: translateX(0);
    }

    .tab-active {
      border-bottom: 2px solid var(--accent);
      color: var(--accent);
    }

    input:focus, select:focus, button:focus-visible {
      outline: 2px solid var(--accent);
      outline-offset: 2px;
    }
  </style>
</head>
<body class="min-h-screen">
  <!-- Navigation -->
  @include('website.navbar')


  <main class="pt-16">
    <!-- HOME PAGE -->
    <div id="page-home" class="page active">
         @yield('content')
    </div>
  </main>

  <!-- FOOTER -->
  @include('website.footer')

  <!-- UNIVERSAL CALL MODAL -->
  @include('website.call-modal')

<script>
document.addEventListener('DOMContentLoaded', function(){
  function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const btn = document.getElementById('mobileMenuButton');
    const backdropId = 'mobileMenuBackdrop';
    if (!menu) return;

    const isOpen = !menu.classList.contains('open');
    if (isOpen) {
      menu.classList.add('open');
      menu.style.transform = 'translateX(0)';
      menu.setAttribute('aria-hidden', 'false');
      if (btn) btn.setAttribute('aria-expanded', 'true');
      document.body.style.overflow = 'hidden';

      let backdrop = document.getElementById(backdropId);
      if (!backdrop) {
        backdrop = document.createElement('div');
        backdrop.id = backdropId;
        backdrop.style.position = 'fixed';
        backdrop.style.inset = '0';
        backdrop.style.background = 'rgba(0,0,0,0.28)';
        backdrop.style.zIndex = '49';
        backdrop.addEventListener('click', toggleMobileMenu);
        document.body.appendChild(backdrop);
      }
    } else {
      menu.classList.remove('open');
      menu.style.transform = 'translateX(-110%)';
      menu.setAttribute('aria-hidden', 'true');
      if (btn) btn.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';

      const backdrop = document.getElementById(backdropId);
      if (backdrop) backdrop.remove();
    }
  }

  // attach to global so inline onclicks still work
  window.toggleMobileMenu = toggleMobileMenu;

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      const menu = document.getElementById('mobileMenu');
      if (menu && menu.classList.contains('open')) {
        toggleMobileMenu();
      }
    }
  });
});
</script>
</body>
</html>
