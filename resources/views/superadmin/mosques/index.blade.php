@extends('layouts.admin')

@section('title', 'Manajemen Masjid Tenant — MASJID INDONESIA')
@section('page_title', 'Direktori & Manajemen Masjid Tenant')
@section('page_subtitle', 'Verifikasi pendaftaran masjid baru, status langganan, dan beralih konteks takmir.')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="font-heading font-bold text-base text-slate-900">Daftar Semua Masjid di Indonesia</h3>
            <form action="{{ route('superadmin.mosques.index') }}" method="GET" class="flex items-center space-x-2">
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama atau kota..." class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                <button type="submit" class="bg-slate-900 text-white px-3 py-1.5 rounded-xl text-xs font-semibold">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Nama Masjid</th>
                        <th class="py-3 px-4">Tipologi</th>
                        <th class="py-3 px-4">Kota & Provinsi</th>
                        <th class="py-3 px-4">Kapasitas</th>
                        <th class="py-3 px-4">Paket Langganan</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($mosques as $m)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-900 block">{{ $m->name }}</span>
                                <span class="text-[11px] text-slate-400 block font-mono">{{ $m->slug }}</span>
                            </td>
                            <td class="py-3 px-4 text-slate-600">{{ $m->type->label() }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $m->city }}, {{ $m->province }}</td>
                            <td class="py-3 px-4 text-slate-600 font-medium">{{ number_format($m->profile?->capacity ?? 0) }}</td>
                            <td class="py-3 px-4">
                                <span class="bg-slate-100 text-slate-800 text-[10px] font-bold px-2 py-0.5 rounded">
                                    {{ $m->subscription?->plan?->name ?? 'FREE' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $m->status->value === 'VERIFIED' ? 'bg-emerald-100 text-emerald-800' : ($m->status->value === 'PENDING' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $m->status->value }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right space-x-1">
                                <form action="{{ route('superadmin.mosques.switch', $m->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold px-2.5 py-1 rounded-lg text-xs" title="Beralih ke Masjid">
                                        Kelola
                                    </button>
                                </form>

                                @if($m->status->value !== 'VERIFIED')
                                    <form action="{{ route('superadmin.mosques.verify', $m->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white font-semibold px-2.5 py-1 rounded-lg text-xs">
                                            Verifikasi
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $mosques->links() }}
        </div>
    </div>
</div>
@endsection
