@extends('layouts.public')

@section('title', 'MASJID INDONESIA — Platform Digital Tata Kelola & Transparansi Masjid Nasional')

@section('content')
<!-- Hero Banner Section -->
<section class="relative bg-gradient-to-br from-emerald-900 via-emerald-800 to-slate-950 text-white pt-20 pb-28 overflow-hidden">
    <!-- Subtle Background Pattern -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:24px_24px]"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto space-y-6">
            <div class="inline-flex items-center space-x-2 bg-emerald-800/80 border border-emerald-700/60 px-4 py-1.5 rounded-full text-xs font-semibold text-gold-400">
                <i data-lucide="sparkles" class="w-4 h-4"></i>
                <span>Platform Digital Manajemen Masjid Terpadu Indonesia</span>
            </div>

            <h1 class="font-heading font-extrabold text-3xl sm:text-5xl lg:text-6xl text-white tracking-tight leading-tight">
                Digitalisasi Masjid,<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-gold-400 to-amber-200">Menguatkan Umat.</span>
            </h1>

            <p class="text-base sm:text-lg text-emerald-100/90 leading-relaxed max-w-2xl mx-auto">
                Transparansi pembukuan kas, penjadwalan ibadah otomatis, syiar dakwah, penyaluran ZISWAF terpercaya, dan verifikasi e-Kwitansi ber-QR resmi.
            </p>

            <!-- Search Form -->
            <div class="pt-4 max-w-2xl mx-auto">
                <form action="{{ route('home') }}" method="GET" class="bg-white p-2 rounded-2xl shadow-2xl flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2 border border-slate-100">
                    <div class="relative flex-1 w-full">
                        <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-3.5 top-3.5"></i>
                        <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama masjid atau kota (mis: Al-Jabbar, Surabaya)..." class="w-full pl-11 pr-4 py-3 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none text-sm font-medium">
                    </div>

                    <select name="province" class="w-full sm:w-auto px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none">
                        <option value="">Semua Provinsi</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov }}" {{ ($province ?? '') === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="w-full sm:w-auto bg-emerald-700 hover:bg-emerald-800 text-white font-semibold px-6 py-3 rounded-xl text-sm transition flex items-center justify-center space-x-2">
                        <span>Cari</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Metric Counter Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mt-16 max-w-5xl mx-auto">
            <div class="bg-white/10 backdrop-blur-md border border-white/10 p-5 rounded-2xl text-center">
                <span class="block font-heading font-extrabold text-2xl sm:text-3xl text-gold-400">{{ number_format($stats['total_mosques']) }}</span>
                <span class="text-xs text-emerald-100 font-medium">Masjid Terdaftar</span>
            </div>
            <div class="bg-white/10 backdrop-blur-md border border-white/10 p-5 rounded-2xl text-center">
                <span class="block font-heading font-extrabold text-2xl sm:text-3xl text-white">{{ $stats['total_provinces'] }}</span>
                <span class="text-xs text-emerald-100 font-medium">Provinsi Terjangkau</span>
            </div>
            <div class="bg-white/10 backdrop-blur-md border border-white/10 p-5 rounded-2xl text-center">
                <span class="block font-heading font-extrabold text-2xl sm:text-3xl text-gold-400">{{ number_format($stats['total_events']) }}</span>
                <span class="text-xs text-emerald-100 font-medium">Kajian & Agenda</span>
            </div>
            <div class="bg-white/10 backdrop-blur-md border border-white/10 p-5 rounded-2xl text-center">
                <span class="block font-heading font-extrabold text-2xl sm:text-3xl text-white">Rp{{ number_format($stats['total_donations'] / 1000000, 1) }} Jt</span>
                <span class="text-xs text-emerald-100 font-medium">Donasi Terverifikasi</span>
            </div>
        </div>
    </div>
</section>

<!-- Featured Mosque Directory -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 -mt-12">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-slate-900">Direktori Masjid Nasional</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Jelajahi profil, jadwal shalat, transparansi kas, dan donasi masjid terverifikasi.</p>
        </div>
    </div>

    @if($mosques->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($mosques as $m)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg transition duration-200 flex flex-col group">
                    <!-- Top Cover & Type Badge -->
                    <div class="h-36 bg-gradient-to-r from-emerald-800 to-emerald-950 p-5 flex flex-col justify-between relative">
                        <div class="flex justify-between items-start">
                            <span class="bg-emerald-700/80 backdrop-blur-sm text-gold-400 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                {{ $m->type->label() }}
                            </span>
                            <span class="bg-white/20 backdrop-blur-sm text-white text-[10px] px-2 py-0.5 rounded flex items-center space-x-1">
                                <i data-lucide="shield-check" class="w-3 h-3 text-emerald-400"></i>
                                <span>Terverifikasi</span>
                            </span>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-lg text-white group-hover:text-gold-300 transition line-clamp-1">{{ $m->name }}</h3>
                            <p class="text-xs text-emerald-200 line-clamp-1 flex items-center space-x-1 mt-0.5">
                                <i data-lucide="map-pin" class="w-3 h-3 flex-shrink-0"></i>
                                <span>{{ $m->city }}, {{ $m->province }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                            {{ $m->profile?->history ?? $m->address_line }}
                        </p>

                        <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-100 text-xs">
                            <div class="flex items-center space-x-2 text-slate-600">
                                <i data-lucide="users" class="w-4 h-4 text-emerald-700"></i>
                                <span>{{ number_format($m->profile?->capacity ?? 0) }} Jamaah</span>
                            </div>
                            <div class="flex items-center space-x-2 text-slate-600">
                                <i data-lucide="building" class="w-4 h-4 text-emerald-700"></i>
                                <span>{{ $m->facilities->count() }} Fasilitas</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('public.mosque', $m->slug) }}" class="w-full text-center bg-emerald-50 hover:bg-emerald-700 text-emerald-800 hover:text-white font-semibold py-2.5 rounded-xl text-xs transition duration-150 flex items-center justify-center space-x-1.5">
                            <span>Kunjungi Portal Masjid</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $mosques->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
            <i data-lucide="search-x" class="w-12 h-12 text-slate-400 mx-auto mb-3"></i>
            <h4 class="font-heading font-semibold text-base text-slate-800">Masjid tidak ditemukan</h4>
            <p class="text-xs text-slate-500 mt-1">Coba gunakan kata kunci lain atau pilih provinsi yang berbeda.</p>
        </div>
    @endif
</section>

<!-- Upcoming Kajian Section -->
@if($featuredEvents->count() > 0)
    <section class="bg-slate-100 py-16 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="font-heading font-bold text-2xl text-slate-900">Agenda Kajian Terdekat</h2>
                    <p class="text-xs text-slate-500 mt-1">Ikuti kajian dan majelis ilmu yang diselenggarakan di berbagai masjid.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredEvents as $event)
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col justify-between shadow-sm hover:shadow-md transition">
                        <div class="space-y-3">
                            <span class="inline-block bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">
                                {{ $event->category?->name ?? 'Kajian' }}
                            </span>
                            <h3 class="font-heading font-bold text-base text-slate-900 leading-snug">
                                <a href="{{ route('public.events.show', [$event->mosque->slug, $event->slug]) }}" class="hover:text-emerald-700 transition">{{ $event->title }}</a>
                            </h3>
                            <div class="text-xs text-slate-600 space-y-1.5">
                                <div class="flex items-center space-x-2">
                                    <i data-lucide="user" class="w-3.5 h-3.5 text-emerald-700 flex-shrink-0"></i>
                                    <span class="font-medium text-slate-800">{{ $event->speaker_name ?? 'Ustadz Pemateri' }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-emerald-700 flex-shrink-0"></i>
                                    <span>{{ $event->start_datetime->translatedFormat('l, d F Y - H:i WIB') }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i data-lucide="landmark" class="w-3.5 h-3.5 text-emerald-700 flex-shrink-0"></i>
                                    <span>{{ $event->mosque->name }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('public.events.show', [$event->mosque->slug, $event->slug]) }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 flex items-center space-x-1">
                                <span>Detail & Reservasi</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection
