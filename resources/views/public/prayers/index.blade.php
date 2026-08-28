@extends('layouts.public')

@section('title', 'Jadwal Shalat & Waktu Imsakiyah — ' . $mosque->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
    <!-- Header -->
    <div class="text-center max-w-2xl mx-auto space-y-2">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">Hisab & Waktu Shalat</span>
        <h1 class="font-heading font-extrabold text-3xl text-slate-900">Jadwal Shalat & Petugas Ibadah</h1>
        <p class="text-xs sm:text-sm text-slate-500">{{ $mosque->name }} • Berdasarkan Koordinat Astronomis Kemenag RI</p>
    </div>

    <!-- Today's Highlight Card -->
    <div class="bg-gradient-to-r from-emerald-800 to-emerald-950 text-white rounded-3xl p-8 shadow-xl">
        <div class="text-center mb-6">
            <span class="text-xs text-gold-400 font-semibold block uppercase">Waktu Shalat Hari Ini</span>
            <h2 class="font-heading font-bold text-xl text-white">{{ $todaySchedule['date'] }}</h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-6 gap-4 text-center">
            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                <span class="text-xs text-emerald-200 uppercase font-bold block">Imsak</span>
                <span class="font-heading font-extrabold text-xl text-white block mt-1">{{ $todaySchedule['imsak'] }}</span>
            </div>
            <div class="bg-gold-500 text-slate-900 rounded-2xl p-4 shadow-lg">
                <span class="text-xs text-slate-800 uppercase font-bold block">Subuh</span>
                <span class="font-heading font-extrabold text-xl text-slate-950 block mt-1">{{ $todaySchedule['fajr'] }}</span>
            </div>
            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                <span class="text-xs text-emerald-200 uppercase font-bold block">Dzuhur</span>
                <span class="font-heading font-extrabold text-xl text-white block mt-1">{{ $todaySchedule['dhuhr'] }}</span>
            </div>
            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                <span class="text-xs text-emerald-200 uppercase font-bold block">Ashar</span>
                <span class="font-heading font-extrabold text-xl text-white block mt-1">{{ $todaySchedule['asr'] }}</span>
            </div>
            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                <span class="text-xs text-emerald-200 uppercase font-bold block">Maghrib</span>
                <span class="font-heading font-extrabold text-xl text-white block mt-1">{{ $todaySchedule['maghrib'] }}</span>
            </div>
            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                <span class="text-xs text-emerald-200 uppercase font-bold block">Isya</span>
                <span class="font-heading font-extrabold text-xl text-white block mt-1">{{ $todaySchedule['isha'] }}</span>
            </div>
        </div>
    </div>

    <!-- Monthly Table & Khatib Duty Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Monthly Prayer Calendar Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h3 class="font-heading font-bold text-lg text-slate-900 mb-4">Tabel Jadwal Shalat Bulan Ini</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-center">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                            <th class="py-2.5 px-3 text-left">Tanggal</th>
                            <th class="py-2.5 px-2">Imsak</th>
                            <th class="py-2.5 px-2">Subuh</th>
                            <th class="py-2.5 px-2">Dzuhur</th>
                            <th class="py-2.5 px-2">Ashar</th>
                            <th class="py-2.5 px-2">Maghrib</th>
                            <th class="py-2.5 px-2">Isya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($monthSchedules as $sc)
                            <tr class="{{ $sc->schedule_date->isToday() ? 'bg-emerald-50/80 font-bold text-emerald-900' : '' }}">
                                <td class="py-2.5 px-3 text-left whitespace-nowrap">{{ $sc->schedule_date->translatedFormat('d F Y') }}</td>
                                <td class="py-2.5 px-2 text-slate-600">{{ substr($sc->imsak, 0, 5) }}</td>
                                <td class="py-2.5 px-2 text-emerald-700 font-semibold">{{ substr($sc->fajr, 0, 5) }}</td>
                                <td class="py-2.5 px-2 text-slate-600">{{ substr($sc->dhuhr, 0, 5) }}</td>
                                <td class="py-2.5 px-2 text-slate-600">{{ substr($sc->asr, 0, 5) }}</td>
                                <td class="py-2.5 px-2 text-slate-600">{{ substr($sc->maghrib, 0, 5) }}</td>
                                <td class="py-2.5 px-2 text-slate-600">{{ substr($sc->isha, 0, 5) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Khatib & Imam Duty Schedule -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-base text-slate-900">Jadwal Khatib Jumat</h3>
                <div class="space-y-3">
                    @foreach($khatibList as $k)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs space-y-1">
                            <span class="text-emerald-700 font-bold block">{{ $k->schedule_date->translatedFormat('l, d F Y') }}</span>
                            <span class="font-semibold text-slate-800 block">{{ $k->assigned_name }}</span>
                            @if($k->title_or_theme)
                                <span class="text-slate-500 text-[11px] block italic">"{{ $k->title_or_theme }}"</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
