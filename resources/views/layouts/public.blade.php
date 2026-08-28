<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MASJID INDONESIA — Digitalisasi Masjid, Menguatkan Umat')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Poppins', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 flex flex-col min-h-screen antialiased selection:bg-emerald-700 selection:text-white">

    <!-- Top Notification / Announcement Bar if any -->
    @if(isset($pinnedAnnouncements) && count($pinnedAnnouncements) > 0)
        <div class="bg-emerald-900 text-white text-xs py-2 px-4 text-center font-medium flex items-center justify-center space-x-2">
            <span class="bg-gold-500 text-slate-900 font-bold px-2 py-0.5 rounded text-[10px] uppercase">Warta Khusus</span>
            <span>{{ $pinnedAnnouncements->first()->title }}: {{ Str::limit($pinnedAnnouncements->first()->body, 100) }}</span>
        </div>
    @endif

    <!-- Main Navigation Bar -->
    <header class="bg-white/90 backdrop-blur-md sticky top-0 z-40 border-b border-slate-200 shadow-sm" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <a href="{{ isset($mosque) ? route('public.mosque', $mosque->slug) : route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-emerald-800 to-emerald-600 flex items-center justify-center text-white shadow-md group-hover:scale-105 transition duration-200">
                            <i data-lucide="landmark" class="w-6 h-6 text-gold-400"></i>
                        </div>
                        <div>
                            <span class="font-heading font-extrabold text-lg sm:text-xl text-slate-900 tracking-tight block">
                                {{ isset($mosque) ? $mosque->name : 'MASJID INDONESIA' }}
                            </span>
                            <span class="text-xs text-slate-500 font-medium block">
                                {{ isset($mosque) ? $mosque->city . ' • ' . $mosque->province : 'Digitalisasi Masjid, Menguatkan Umat' }}
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2 font-medium text-sm text-slate-700">
                    @if(isset($mosque))
                        <a href="{{ route('public.mosque', $mosque->slug) }}" class="px-3 py-2 rounded-lg hover:text-emerald-700 hover:bg-emerald-50 transition {{ request()->routeIs('public.mosque') ? 'text-emerald-700 bg-emerald-50 font-semibold' : '' }}">Beranda</a>
                        <a href="{{ route('public.prayers', $mosque->slug) }}" class="px-3 py-2 rounded-lg hover:text-emerald-700 hover:bg-emerald-50 transition {{ request()->routeIs('public.prayers') ? 'text-emerald-700 bg-emerald-50 font-semibold' : '' }}">Jadwal Shalat</a>
                        <a href="{{ route('public.events', $mosque->slug) }}" class="px-3 py-2 rounded-lg hover:text-emerald-700 hover:bg-emerald-50 transition {{ request()->routeIs('public.events*') ? 'text-emerald-700 bg-emerald-50 font-semibold' : '' }}">Kajian & Agenda</a>
                        <a href="{{ route('public.donations', $mosque->slug) }}" class="px-3 py-2 rounded-lg hover:text-emerald-700 hover:bg-emerald-50 transition {{ request()->routeIs('public.donations*') ? 'text-emerald-700 bg-emerald-50 font-semibold' : '' }}">Donasi & Infaq</a>
                    @else
                        <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg hover:text-emerald-700 hover:bg-emerald-50 transition">Direktori Masjid</a>
                    @endif
                </nav>

                <!-- Action Button & Login -->
                <div class="hidden md:flex items-center space-x-3">
                    @if(isset($mosque))
                        <a href="{{ route('public.donations', $mosque->slug) }}" class="inline-flex items-center space-x-2 bg-gradient-to-r from-emerald-700 to-emerald-600 hover:from-emerald-800 hover:to-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm hover:shadow-md transition">
                            <i data-lucide="heart-handshake" class="w-4 h-4 text-gold-400"></i>
                            <span>Donasi Cepat</span>
                        </a>
                    @endif

                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center space-x-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center space-x-1.5 text-slate-700 hover:text-emerald-700 text-sm font-medium px-3 py-2 rounded-lg transition">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                            <span>Masuk Takmir</span>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenu = !mobileMenu" class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none">
                        <i data-lucide="menu" class="w-6 h-6" x-show="!mobileMenu"></i>
                        <i data-lucide="x" class="w-6 h-6" x-show="mobileMenu" style="display: none;"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Dropdown -->
        <div x-show="mobileMenu" class="md:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-2" style="display: none;">
            @if(isset($mosque))
                <a href="{{ route('public.mosque', $mosque->slug) }}" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">Beranda Masjid</a>
                <a href="{{ route('public.prayers', $mosque->slug) }}" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">Jadwal Shalat</a>
                <a href="{{ route('public.events', $mosque->slug) }}" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">Kajian</a>
                <a href="{{ route('public.donations', $mosque->slug) }}" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">Donasi & Infaq</a>
            @else
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">Cari Masjid</a>
            @endif

            <div class="pt-4 border-t border-slate-100 flex flex-col space-y-2">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="w-full text-center bg-slate-900 text-white font-medium py-2.5 rounded-lg text-sm">Masuk Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center bg-emerald-700 text-white font-medium py-2.5 rounded-lg text-sm">Masuk Akun Pengurus</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Dynamic Content -->
    <main class="flex-grow">
        <!-- Flash Alert Messages -->
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-6">
                <div class="bg-emerald-50 border-l-4 border-emerald-600 p-4 rounded-r-xl text-emerald-900 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 mt-6">
                <div class="bg-red-50 border-l-4 border-red-600 p-4 rounded-r-xl text-red-900 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Modern Footer -->
    <footer class="bg-slate-950 text-slate-300 border-t border-slate-800 pt-16 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Brand Info -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-700 flex items-center justify-center text-white">
                            <i data-lucide="landmark" class="w-5 h-5 text-gold-400"></i>
                        </div>
                        <span class="font-heading font-bold text-xl text-white tracking-tight">MASJID INDONESIA</span>
                    </div>
                    <p class="text-sm text-slate-400 max-w-md leading-relaxed">
                        Platform tata kelola digital, transparansi keuangan kas, penjadwalan ibadah, dan verifikasi dokumen resmi masjid se-Indonesia.
                    </p>
                    <div class="text-xs text-slate-500">
                        Didukung oleh infrastruktur aman Serverless Neon PostgreSQL & Laravel.
                    </div>
                </div>

                <!-- Navigation Links -->
                <div>
                    <h4 class="font-heading font-semibold text-white text-sm mb-4">Navigasi Utama</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-400 transition">Direktori Masjid</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-emerald-400 transition">Daftarkan Masjid Baru</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-emerald-400 transition">Portal Pengurus & Takmir</a></li>
                    </ul>
                </div>

                <!-- Security & Verification -->
                <div>
                    <h4 class="font-heading font-semibold text-white text-sm mb-4">Verifikasi Digital</h4>
                    <p class="text-xs text-slate-400 mb-3">
                        Pindai kode QR pada e-Kwitansi atau sertifikat resmi masjid untuk memvalidasi keabsahan dokumen.
                    </p>
                    <div class="inline-flex items-center space-x-2 text-xs bg-slate-900 border border-slate-800 text-emerald-400 px-3 py-2 rounded-lg">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i>
                        <span>Anti-Pemalsuan Dokumen</span>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-800 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} MASJID INDONESIA. Hak Cipta Dilindungi Undang-Undang.</p>
                <p class="mt-2 sm:mt-0">Dirancang dengan prinsip <em>Modern Islamic Minimalism</em>.</p>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
