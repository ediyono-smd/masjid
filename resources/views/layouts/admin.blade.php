<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') — MASJID INDONESIA</title>

    <!-- Google Fonts: Poppins & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                        gold: {
                            400: '#facc15',
                            500: '#D4AF37',
                            600: '#b89326',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Poppins', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-900 min-h-screen antialiased flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden transition-opacity"></div>

    <!-- Sidebar Container -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col transition-transform duration-300 ease-in-out border-r border-slate-800 shadow-xl">
        <!-- Brand Header -->
        <div class="h-20 flex items-center px-6 bg-slate-950 border-b border-slate-800/80">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-700 to-emerald-500 flex items-center justify-center text-white shadow-md">
                    <i data-lucide="landmark" class="w-5 h-5 text-gold-400"></i>
                </div>
                <div>
                    <span class="font-heading font-extrabold text-white text-base tracking-tight block">MASJID INDO</span>
                    <span class="text-[11px] text-emerald-400 font-semibold block uppercase tracking-wider">Takmir Portal</span>
                </div>
            </a>
        </div>

        <!-- Active Mosque Context Info -->
        @if(isset($currentMosque))
            <div class="px-4 py-3 bg-slate-800/60 border-b border-slate-800 flex items-center justify-between">
                <div class="truncate">
                    <span class="text-[10px] text-slate-400 block font-medium">Masjid Aktif:</span>
                    <span class="text-xs font-semibold text-white truncate block">{{ $currentMosque->name }}</span>
                </div>
                <a href="{{ route('public.mosque', $currentMosque->slug) }}" target="_blank" title="Lihat Website Publik" class="p-1.5 rounded-lg bg-slate-700 hover:bg-emerald-700 text-slate-300 hover:text-white transition">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        @endif

        <!-- Scrollable Navigation Items -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6 text-xs font-medium">
            <!-- Section 1: Dashboard -->
            <div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 text-white font-semibold shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-emerald-400"></i>
                    <span>Dashboard Utama</span>
                </a>
            </div>

            <!-- Section 2: Profil & Takmir -->
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Tata Kelola & Staf</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.profile.edit') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.profile*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                        <span>Profil & Fasilitas</span>
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.staff*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        <span>Struktur Pengurus</span>
                    </a>
                    <a href="{{ route('admin.congregations.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.congregations*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="contact" class="w-4 h-4"></i>
                        <span>Data Jamaah</span>
                    </a>
                </div>
            </div>

            <!-- Section 3: Ibadah & Dakwah -->
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Ibadah & Syiar</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.prayers.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.prayers*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                        <span>Jadwal Shalat & Khatib</span>
                    </a>
                    <a href="{{ route('admin.events.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.events*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <span>Agenda Kajian & Acara</span>
                    </a>
                    <a href="{{ route('admin.news.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.news*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="newspaper" class="w-4 h-4"></i>
                        <span>Warta & Berita</span>
                    </a>
                    <a href="{{ route('admin.announcements.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.announcements*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="bell-ring" class="w-4 h-4"></i>
                        <span>Pengumuman</span>
                    </a>
                </div>
            </div>

            <!-- Section 4: Keuangan & Donasi -->
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Keuangan & Donasi</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.finances.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.finances*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="wallet" class="w-4 h-4"></i>
                        <span>Buku Kas & Transaksi</span>
                    </a>
                    <a href="{{ route('admin.campaigns.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.campaigns*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="heart-handshake" class="w-4 h-4"></i>
                        <span>Program Donasi / Infaq</span>
                    </a>
                    <a href="{{ route('admin.donations.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.donations*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="receipt" class="w-4 h-4"></i>
                        <span>Verifikasi Donasi</span>
                    </a>
                </div>
            </div>

            <!-- Section 5: Sosial & Zakat -->
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Sosial & ZISWAF</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.social.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.social*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="hand-heart" class="w-4 h-4"></i>
                        <span>Bantuan & Mustahiq</span>
                    </a>
                    <a href="{{ route('admin.zakat.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.zakat*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="scale" class="w-4 h-4"></i>
                        <span>Zakat Fitrah & Maal</span>
                    </a>
                </div>
            </div>

            <!-- Section 6: Aset & Perpustakaan -->
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Inventaris & Perpus</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.inventory.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.inventory*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="box" class="w-4 h-4"></i>
                        <span>Inventaris & Maintenance</span>
                    </a>
                    <a href="{{ route('admin.library.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.library*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="book-open" class="w-4 h-4"></i>
                        <span>Perpustakaan Masjid</span>
                    </a>
                    <a href="{{ route('admin.submissions.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.submissions*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                        <i data-lucide="file-check" class="w-4 h-4"></i>
                        <span>Pengajuan & Approval</span>
                    </a>
                </div>
            </div>

            <!-- Section 7: Super Admin Tools (If Super Admin) -->
            @if(Auth::user()->hasRole('SUPER_ADMIN'))
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-gold-400">Super Admin Panel</div>
                    <div class="space-y-1">
                        <a href="{{ route('superadmin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('superadmin.dashboard') ? 'bg-slate-800 text-gold-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                            <i data-lucide="shield" class="w-4 h-4 text-gold-400"></i>
                            <span>Statistik Nasional</span>
                        </a>
                        <a href="{{ route('superadmin.mosques.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('superadmin.mosques*') ? 'bg-slate-800 text-gold-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                            <i data-lucide="landmark" class="w-4 h-4 text-gold-400"></i>
                            <span>Semua Masjid Tenant</span>
                        </a>
                        <a href="{{ route('superadmin.audit.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('superadmin.audit*') ? 'bg-slate-800 text-gold-400 font-semibold' : 'hover:bg-slate-800/60 text-slate-400 hover:text-slate-200' }}">
                            <i data-lucide="activity" class="w-4 h-4 text-gold-400"></i>
                            <span>Audit Trail Global</span>
                        </a>
                    </div>
                </div>
            @endif
        </nav>

        <!-- User Profile & Logout Bottom Bar -->
        <div class="p-4 bg-slate-950 border-t border-slate-800 flex items-center justify-between">
            <div class="flex items-center space-x-3 truncate">
                <div class="w-8 h-8 rounded-full bg-emerald-800 text-white flex items-center justify-center font-bold text-xs">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="truncate">
                    <span class="text-xs font-semibold text-white block truncate">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] text-slate-400 block">{{ Auth::user()->roles->first()?->name ?? 'Pengurus' }}</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" title="Keluar" class="p-2 text-slate-400 hover:text-red-400 transition">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Canvas Area -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">
        <!-- Top Application Header Bar -->
        <header class="h-20 bg-white border-b border-slate-200 sticky top-0 z-30 px-4 sm:px-8 flex items-center justify-between shadow-sm">
            <!-- Mobile Toggle -->
            <div class="flex items-center space-x-3">
                <button @click="sidebarOpen = true" class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 lg:hidden">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="hidden sm:block">
                    <h1 class="font-heading font-bold text-lg text-slate-900">@yield('page_title', 'Dashboard Takmir')</h1>
                    <p class="text-xs text-slate-500">@yield('page_subtitle', 'Sistem Manajemen Operasional & Keuangan Masjid')</p>
                </div>
            </div>

            <!-- Right Controls -->
            <div class="flex items-center space-x-3">
                <div class="hidden md:flex items-center space-x-2 text-xs bg-emerald-50 text-emerald-800 px-3 py-1.5 rounded-lg font-medium border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Sistem Multi-Tenant Aktif</span>
                </div>

                @if(isset($currentMosque))
                    <a href="{{ route('public.mosque', $currentMosque->slug) }}" target="_blank" class="inline-flex items-center space-x-1.5 text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2 rounded-xl transition">
                        <i data-lucide="globe" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Lihat Portal</span>
                    </a>
                @endif
            </div>
        </header>

        <!-- Main Body Workspace -->
        <main class="flex-1 p-4 sm:p-8 max-w-7xl w-full mx-auto">
            <!-- Alerts -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-600 p-4 rounded-r-xl text-emerald-900 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-600 p-4 rounded-r-xl text-red-900 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-xl text-amber-900 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600 flex-shrink-0"></i>
                        <span>{{ session('warning') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
