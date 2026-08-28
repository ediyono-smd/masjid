@extends('layouts.admin')

@section('title', 'Super Admin — Statistik Platform Nasional')
@section('page_title', 'Super Admin Platform Overview')
@section('page_subtitle', 'Monitoring ekosistem masjid nasional, status tenant multi-masjid, dan log aktivitas keamanan.')

@section('content')
<div class="space-y-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-xs font-semibold">Total Masjid Terdaftar</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <i data-lucide="landmark" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="font-heading font-extrabold text-2xl text-slate-900">{{ number_format($stats['total_mosques']) }}</div>
            <div class="text-[11px] text-slate-500">{{ $stats['verified_mosques'] }} terverifikasi resmi</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-xs font-semibold">Sebaran Provinsi</span>
                <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                    <i data-lucide="map" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="font-heading font-extrabold text-2xl text-slate-900">{{ $stats['total_provinces'] }} Provinsi</div>
            <div class="text-[11px] text-slate-500">Cakupan nasional Indonesia</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-xs font-semibold">Total Pengguna Aktif</span>
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="font-heading font-extrabold text-2xl text-slate-900">{{ number_format($stats['total_users']) }}</div>
            <div class="text-[11px] text-slate-500">Takmir, amil & operator</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-xs font-semibold">Volume Donasi Nasional</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="font-heading font-extrabold text-2xl text-emerald-700">Rp{{ number_format($stats['total_donations'] / 1000000, 1) }} Jt</div>
            <div class="text-[11px] text-slate-500">Tersalurkan ke masjid-masjid</div>
        </div>
    </div>

    <!-- Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Registered Mosques -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="font-heading font-bold text-base text-slate-900">Masjid Baru Terdaftar</h3>
                <a href="{{ route('superadmin.mosques.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">Semua Masjid</a>
            </div>

            <div class="space-y-3">
                @foreach($recentMosques as $m)
                    <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl border border-slate-100 text-xs">
                        <div>
                            <span class="font-bold text-slate-900 block">{{ $m->name }}</span>
                            <span class="text-slate-500 text-[11px] block">{{ $m->city }}, {{ $m->province }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $m->status->value === 'VERIFIED' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $m->status->value }}
                            </span>
                            <form action="{{ route('superadmin.mosques.switch', $m->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-1.5 bg-slate-200 hover:bg-emerald-700 hover:text-white rounded-lg text-slate-700 transition" title="Beralih ke Masjid Ini">
                                    <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Audit Logs -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="font-heading font-bold text-base text-slate-900">Audit Trail Keamanan</h3>
                <a href="{{ route('superadmin.audit.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">Semua Log</a>
            </div>

            <div class="space-y-3">
                @foreach($recentLogs as $log)
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 text-xs flex justify-between items-start">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="bg-slate-200 text-slate-800 font-bold px-1.5 py-0.5 rounded text-[10px]">{{ $log->event_type }}</span>
                                <span class="font-semibold text-slate-900">{{ $log->user?->name ?? 'System' }}</span>
                            </div>
                            <span class="text-[11px] text-slate-500 block mt-1">Masjid: {{ $log->mosque?->name ?? 'Platform Global' }}</span>
                        </div>
                        <span class="text-[10px] text-slate-400 whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
