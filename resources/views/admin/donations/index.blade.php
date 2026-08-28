@extends('layouts.admin')

@section('title', 'Verifikasi Donasi Masuk — ' . $mosque->name)
@section('page_title', 'Verifikasi & Data Donasi')
@section('page_subtitle', 'Pantau penerimaan infaq/donasi, validasi pembayaran, dan penerbitan e-Kwitansi ber-QR.')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="font-heading font-bold text-base text-slate-900">Daftar Penerimaan Infaq & Donasi</h3>
            <div class="flex space-x-2 text-xs">
                <a href="{{ route('admin.donations.index') }}" class="px-3 py-1.5 rounded-lg border {{ !$status ? 'bg-emerald-700 text-white font-bold border-emerald-700' : 'bg-slate-50 text-slate-600 border-slate-200' }}">Semua</a>
                <a href="{{ route('admin.donations.index', ['status' => 'PENDING']) }}" class="px-3 py-1.5 rounded-lg border {{ $status === 'PENDING' ? 'bg-amber-500 text-white font-bold border-amber-500' : 'bg-slate-50 text-slate-600 border-slate-200' }}">Pending</a>
                <a href="{{ route('admin.donations.index', ['status' => 'VERIFIED']) }}" class="px-3 py-1.5 rounded-lg border {{ $status === 'VERIFIED' ? 'bg-emerald-700 text-white font-bold border-emerald-700' : 'bg-slate-50 text-slate-600 border-slate-200' }}">Terverifikasi</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Nama Donatur</th>
                        <th class="py-3 px-4">Program</th>
                        <th class="py-3 px-4">Nominal</th>
                        <th class="py-3 px-4">Metode</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Kode Validasi</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($donations as $don)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap">{{ $don->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $don->display_name }}</td>
                            <td class="py-3 px-4 text-slate-600 font-medium">{{ $don->campaign?->title ?? 'Infaq Operasional' }}</td>
                            <td class="py-3 px-4 font-bold text-emerald-700">Rp{{ number_format((float) $don->amount, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $don->payment_method }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $don->status === 'VERIFIED' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $don->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('verify', $don->verification_code) }}" target="_blank" class="font-mono text-[11px] text-emerald-700 hover:underline flex items-center space-x-1">
                                    <span>{{ $don->verification_code }}</span>
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                </a>
                            </td>
                            <td class="py-3 px-4 text-right">
                                @if($don->status !== 'VERIFIED')
                                    <form action="{{ route('admin.donations.verify', $don->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-3 py-1.5 rounded-lg text-xs transition shadow-sm">
                                            Verifikasi & Terbitkan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400 text-[11px]">Terverifikasi oleh {{ $don->verifiedBy?->name ?? 'Admin' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-400 italic">Tidak ada data donasi ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $donations->links() }}
        </div>
    </div>
</div>
@endsection
