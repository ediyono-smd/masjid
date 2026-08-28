@extends('layouts.public')

@section('title', $mosque->name . ' — Portal Resmi & Transparansi')

@section('content')
<!-- Hero Section for Specific Mosque -->
<section class="relative bg-gradient-to-r from-emerald-950 via-emerald-900 to-slate-900 text-white pt-12 pb-24 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="space-y-4 max-w-2xl text-center md:text-left">
                <div class="inline-flex items-center space-x-2 bg-emerald-800/80 border border-emerald-700 px-3 py-1 rounded-full text-xs font-semibold text-gold-400">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    <span>Masjid Terverifikasi Resmi • ID SIMAS: {{ $mosque->kemenag_id ?? 'Kemenag RI' }}</span>
                </div>

                <h1 class="font-heading font-extrabold text-3xl sm:text-5xl text-white tracking-tight leading-tight">
                    {{ $mosque->name }}
                </h1>

                <p class="text-xs sm:text-sm text-emerald-100/90 flex items-center justify-center md:justify-start space-x-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-gold-400 flex-shrink-0"></i>
                    <span>{{ $mosque->address_line }}, {{ $mosque->city }}, {{ $mosque->province }} {{ $mosque->postal_code }}</span>
                </p>

                <p class="text-xs text-slate-300 max-w-xl line-clamp-2 leading-relaxed">
                    {{ $mosque->profile?->history ?? 'Pusat peribadatan, dakwah, dan pemberdayaan umat.' }}
                </p>

                <div class="pt-2 flex flex-wrap gap-3 justify-center md:justify-start">
                    <a href="{{ route('public.donations', $mosque->slug) }}" class="bg-gradient-to-r from-gold-500 to-amber-500 hover:from-gold-600 hover:to-amber-600 text-slate-950 font-bold px-6 py-3 rounded-xl text-sm shadow-md transition flex items-center space-x-2">
                        <i data-lucide="heart-handshake" class="w-4 h-4"></i>
                        <span>Salurkan Infaq & Donasi</span>
                    </a>
                    <a href="{{ route('public.prayers', $mosque->slug) }}" class="bg-white/10 hover:bg-white/20 border border-white/10 text-white font-medium px-5 py-3 rounded-xl text-sm transition flex items-center space-x-2">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                        <span>Jadwal Shalat Lengkap</span>
                    </a>
                </div>
            </div>

            <!-- Quick Stat Box -->
            <div class="bg-white/10 backdrop-blur-md border border-white/10 p-6 rounded-2xl w-full md:w-80 text-center space-y-4">
                <span class="text-xs text-emerald-200 font-semibold block uppercase tracking-wider">Kapasitas Jamaah</span>
                <span class="font-heading font-extrabold text-3xl sm:text-4xl text-white block">{{ number_format($mosque->profile?->capacity ?? 0) }}</span>
                <span class="text-xs text-slate-300 block">Daya Tampung Ruang Shalat</span>
                <div class="pt-3 border-t border-white/10 flex justify-around text-xs">
                    <div>
                        <span class="font-bold text-gold-400 block">{{ $mosque->facilities->count() }}</span>
                        <span class="text-[10px] text-slate-300">Fasilitas</span>
                    </div>
                    <div>
                        <span class="font-bold text-gold-400 block">{{ $mosque->staff->count() }}</span>
                        <span class="text-[10px] text-slate-300">Pengurus</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Prayer Times Bar (Floating Card) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-4 pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-sm text-slate-900">Jadwal Waktu Shalat Hari Ini</h3>
                    <p class="text-[11px] text-slate-500">{{ $todaySchedule['date'] }} (Metode Kemenag RI)</p>
                </div>
            </div>
            <div class="text-xs bg-amber-50 text-amber-800 font-semibold px-3 py-1.5 rounded-lg border border-amber-200 flex items-center space-x-1.5">
                <i data-lucide="hourglass" class="w-3.5 h-3.5 text-amber-600"></i>
                <span>Waktu Shalat Berikutnya: <strong>{{ is_array($todaySchedule['next_prayer']) ? $todaySchedule['next_prayer']['name'] . ' (' . $todaySchedule['next_prayer']['time'] . ')' : $todaySchedule['next_prayer'] }}</strong></span>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-6 gap-3 text-center">
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <span class="text-[11px] font-bold text-slate-500 uppercase block">Imsak</span>
                <span class="font-heading font-extrabold text-base text-slate-800">{{ $todaySchedule['imsak'] }}</span>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <span class="text-[11px] font-bold text-emerald-800 uppercase block">Subuh</span>
                <span class="font-heading font-extrabold text-base text-emerald-700">{{ $todaySchedule['fajr'] }}</span>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <span class="text-[11px] font-bold text-slate-500 uppercase block">Dzuhur</span>
                <span class="font-heading font-extrabold text-base text-slate-800">{{ $todaySchedule['dhuhr'] }}</span>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <span class="text-[11px] font-bold text-slate-500 uppercase block">Ashar</span>
                <span class="font-heading font-extrabold text-base text-slate-800">{{ $todaySchedule['asr'] }}</span>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <span class="text-[11px] font-bold text-slate-500 uppercase block">Maghrib</span>
                <span class="font-heading font-extrabold text-base text-slate-800">{{ $todaySchedule['maghrib'] }}</span>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <span class="text-[11px] font-bold text-slate-500 uppercase block">Isya</span>
                <span class="font-heading font-extrabold text-base text-slate-800">{{ $todaySchedule['isha'] }}</span>
            </div>
        </div>
    </div>
</section>

<!-- Main Content Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 lg:grid-cols-3 gap-10">
    <!-- Left Column: Programs, Events, News (2 Cols) -->
    <div class="lg:col-span-2 space-y-12">

        <!-- 1. Featured Donation Campaigns -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-heading font-bold text-xl text-slate-900">Program Donasi & Sedekah Jariyah</h2>
                    <p class="text-xs text-slate-500">Salurkan infaq terbaik Anda untuk pembangunan dan kemakmuran masjid.</p>
                </div>
                <a href="{{ route('public.donations', $mosque->slug) }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 flex items-center space-x-1">
                    <span>Lihat Semua</span>
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($activeCampaigns as $camp)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm flex flex-col justify-between">
                        <div class="p-5 space-y-3">
                            <span class="inline-block bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase">
                                {{ $camp->category }}
                            </span>
                            <h3 class="font-heading font-bold text-base text-slate-900 line-clamp-1">
                                <a href="{{ route('public.donations.show', [$mosque->slug, $camp->slug]) }}" class="hover:text-emerald-700 transition">{{ $camp->title }}</a>
                            </h3>
                            <p class="text-xs text-slate-600 line-clamp-2">{{ $camp->description }}</p>

                            <!-- Progress Bar -->
                            <div class="space-y-1.5 pt-2">
                                <div class="flex justify-between text-[11px] font-semibold text-slate-700">
                                    <span>Terkumpul: Rp{{ number_format((float) $camp->collected_amount, 0, ',', '.') }}</span>
                                    <span>{{ $camp->progress_percentage }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-emerald-600 h-2 rounded-full transition-all duration-500" style="width: {{ $camp->progress_percentage }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50 border-t border-slate-100">
                            <a href="{{ route('public.donations.show', [$mosque->slug, $camp->slug]) }}" class="w-full text-center block bg-emerald-700 hover:bg-emerald-800 text-white font-semibold py-2.5 rounded-xl text-xs transition">
                                Donasi Sekarang
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 2. Upcoming Kajian & Events -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-heading font-bold text-xl text-slate-900">Agenda Kajian Pekan Ini</h2>
                    <p class="text-xs text-slate-500">Majelis taklim dan kegiatan ibadah di {{ $mosque->name }}.</p>
                </div>
                <a href="{{ route('public.events', $mosque->slug) }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 flex items-center space-x-1">
                    <span>Lihat Jadwal Lengkap</span>
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            </div>

            @if($upcomingEvents->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingEvents as $evt)
                        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="space-y-1.5">
                                <span class="bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase">
                                    {{ $evt->category?->name ?? 'Kajian' }}
                                </span>
                                <h3 class="font-heading font-bold text-base text-slate-900">
                                    <a href="{{ route('public.events.show', [$mosque->slug, $evt->slug]) }}" class="hover:text-emerald-700 transition">{{ $evt->title }}</a>
                                </h3>
                                <div class="text-xs text-slate-600 flex flex-wrap gap-x-4 gap-y-1">
                                    <span class="flex items-center space-x-1">
                                        <i data-lucide="user" class="w-3.5 h-3.5 text-emerald-700"></i>
                                        <span>{{ $evt->speaker_name ?? 'Ustadz Pemateri' }}</span>
                                    </span>
                                    <span class="flex items-center space-x-1">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-emerald-700"></i>
                                        <span>{{ $evt->start_datetime->translatedFormat('l, d F Y - H:i WIB') }}</span>
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('public.events.show', [$mosque->slug, $evt->slug]) }}" class="bg-emerald-50 hover:bg-emerald-700 text-emerald-800 hover:text-white text-xs font-semibold px-4 py-2 rounded-xl transition flex-shrink-0">
                                Detail / RSVP
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-500 italic bg-white p-6 rounded-2xl border border-slate-200">Belum ada agenda kajian terdekat yang dijadwalkan.</p>
            @endif
        </div>

        <!-- 3. Transparansi Kas & Keuangan Terbuka -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="font-heading font-bold text-lg text-slate-900 flex items-center space-x-2">
                        <i data-lucide="shield-check" class="w-5 h-5 text-emerald-600"></i>
                        <span>Transparansi Keuangan Kas Masjid</span>
                    </h2>
                    <p class="text-xs text-slate-500">Laporan pertanggungjawaban kas infaq dan operasional terbuka untuk jamaah.</p>
                </div>
                <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-3 py-1 rounded-full">Akuntabel & Diaudit</span>
            </div>

            <!-- Ledger Summary Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <span class="text-[11px] text-slate-500 font-medium block">Total Saldo Kas Berjalan</span>
                    <span class="font-heading font-extrabold text-lg text-slate-900 block mt-1">Rp{{ number_format($financeSummary['current_balance'], 0, ',', '.') }}</span>
                </div>
                <div class="bg-emerald-50/60 p-4 rounded-xl border border-emerald-100">
                    <span class="text-[11px] text-emerald-800 font-medium block">Pemasukan Bulan Ini</span>
                    <span class="font-heading font-extrabold text-lg text-emerald-700 block mt-1">+Rp{{ number_format($financeSummary['this_month_income'], 0, ',', '.') }}</span>
                </div>
                <div class="bg-red-50/60 p-4 rounded-xl border border-red-100">
                    <span class="text-[11px] text-red-800 font-medium block">Pengeluaran Bulan Ini</span>
                    <span class="font-heading font-extrabold text-lg text-red-700 block mt-1">-Rp{{ number_format($financeSummary['this_month_expense'], 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Recent Approved Cash Transactions Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-semibold">
                            <th class="pb-2">Tanggal</th>
                            <th class="pb-2">Uraian / Keterangan</th>
                            <th class="pb-2">Kategori</th>
                            <th class="pb-2 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentTransactions as $tx)
                            <tr>
                                <td class="py-2.5 text-slate-600 whitespace-nowrap">{{ $tx->transaction_date->format('d/m/Y') }}</td>
                                <td class="py-2.5 font-medium text-slate-800">{{ $tx->description }}</td>
                                <td class="py-2.5 text-slate-500">{{ $tx->incomeCategory?->name ?? $tx->expenseCategory?->name }}</td>
                                <td class="py-2.5 text-right font-semibold {{ $tx->transaction_type->value === 'INCOME' ? 'text-emerald-700' : 'text-red-600' }}">
                                    {{ $tx->transaction_type->value === 'INCOME' ? '+' : '-' }}Rp{{ number_format((float) $tx->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Sidebar Column (1 Col) -->
    <div class="space-y-8">
        <!-- Khatib Jumat Card -->
        @if($khatibJumat)
            <div class="bg-gradient-to-br from-emerald-800 to-emerald-950 text-white p-6 rounded-2xl shadow-md space-y-4">
                <span class="bg-gold-500 text-slate-900 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider">
                    Petugas Shalat Jumat
                </span>
                <div>
                    <span class="text-xs text-emerald-200 block">{{ $khatibJumat->schedule_date->translatedFormat('l, d F Y') }}</span>
                    <h3 class="font-heading font-bold text-lg text-white mt-1">{{ $khatibJumat->assigned_name }}</h3>
                    @if($khatibJumat->title_or_theme)
                        <p class="text-xs text-gold-300 italic mt-1">"{{ $khatibJumat->title_or_theme }}"</p>
                    @endif
                </div>

                <div class="pt-3 border-t border-white/10 text-xs text-emerald-100 space-y-1">
                    @if($khatibJumat->muadzin_name)
                        <div>Muadzin: <span class="font-medium text-white">{{ $khatibJumat->muadzin_name }}</span></div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Mosque Facilities -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h3 class="font-heading font-bold text-base text-slate-900">Fasilitas Masjid</h3>
            <div class="grid grid-cols-1 gap-3">
                @foreach($mosque->facilities as $fac)
                    <div class="flex items-start space-x-3 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                        <div class="p-2 rounded-lg bg-emerald-100 text-emerald-800">
                            <i data-lucide="{{ $fac->icon ?? 'check' }}" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="font-bold text-slate-800 block">{{ $fac->name }}</span>
                            <span class="text-slate-500 block text-[11px]">{{ $fac->description }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Takmir Structure -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h3 class="font-heading font-bold text-base text-slate-900">Struktur Pengurus Takmir</h3>
            <div class="space-y-3">
                @foreach($mosque->staff->take(4) as $stf)
                    <div class="flex items-center justify-between text-xs py-1 border-b border-slate-100">
                        <div>
                            <span class="font-bold text-slate-800 block">{{ $stf->name }}</span>
                            <span class="text-slate-500 text-[11px] block">{{ $stf->position }}</span>
                        </div>
                        <span class="text-[10px] bg-slate-100 px-2 py-0.5 rounded text-slate-600 font-medium">Periode {{ $stf->period_start }}-{{ $stf->period_end }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
