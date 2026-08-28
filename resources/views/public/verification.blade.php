@extends('layouts.public')

@section('title', 'Verifikasi Dokumen Digital — ' . $code)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-16">
    <!-- Verification Card Container -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
        <!-- Top Status Banner Header -->
        <div class="p-8 text-center {{ $result['is_valid'] ? 'bg-gradient-to-r from-emerald-800 to-emerald-700 text-white' : 'bg-red-700 text-white' }}">
            <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center {{ $result['is_valid'] ? 'bg-white/20 text-gold-400' : 'bg-white/20 text-white' }}">
                <i data-lucide="{{ $result['is_valid'] ? 'shield-check' : 'shield-alert' }}" class="w-8 h-8"></i>
            </div>
            <span class="text-xs uppercase font-bold tracking-widest block opacity-80">Sistem Verifikasi Digital Resmi</span>
            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl mt-1 tracking-tight">{{ $result['status_label'] }}</h1>
            <p class="text-xs sm:text-sm mt-2 opacity-90">Token Validasi: <code class="bg-black/20 px-2 py-0.5 rounded font-mono">{{ $code }}</code></p>
        </div>

        <!-- Verification Meta Table -->
        <div class="p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                    <span class="text-slate-500 font-medium block">Nomor Dokumen:</span>
                    <span class="font-heading font-bold text-slate-900 text-sm block">{{ $result['document_number'] }}</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                    <span class="text-slate-500 font-medium block">Jenis Dokumen:</span>
                    <span class="font-heading font-bold text-slate-900 text-sm block">{{ $result['document_type'] }}</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                    <span class="text-slate-500 font-medium block">Masjid Penerbit:</span>
                    <span class="font-heading font-bold text-emerald-800 text-sm block">{{ $result['mosque_name'] }} ({{ $result['mosque_city'] }})</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                    <span class="text-slate-500 font-medium block">Waktu Penerbitan:</span>
                    <span class="font-heading font-bold text-slate-900 text-sm block">{{ $result['issued_at'] }}</span>
                </div>
            </div>

            <!-- Sanitized Payload Data Items -->
            @if(!empty($result['payload']))
                <div class="border-t border-slate-100 pt-6">
                    <h3 class="font-heading font-semibold text-xs text-slate-500 uppercase tracking-wider mb-4">Rincian Transaksi Dokumen</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        @foreach($result['payload'] as $key => $val)
                            <div class="p-3 bg-slate-50 rounded-lg flex justify-between items-center">
                                <dt class="text-slate-500 capitalize">{{ str_replace('_', ' ', $key) }}:</dt>
                                <dd class="font-semibold text-slate-800">{{ is_array($val) ? json_encode($val) : $val }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            @endif

            <!-- Security Assurance Notice -->
            <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-2xl text-xs text-emerald-900 space-y-1.5 flex items-start space-x-3">
                <i data-lucide="lock" class="w-5 h-5 text-emerald-700 flex-shrink-0 mt-0.5"></i>
                <div>
                    <span class="font-bold block">Jaminan Keaslian Digital MASJID INDONESIA</span>
                    <span class="leading-relaxed block text-emerald-800">
                        Dokumen ini diterbitkan secara sah oleh takmir masjid melalui sistem tersentralisasi dengan perlindungan enkripsi token kriptografis. Data pribadi terlindungi sesuai standar privasi data nasional.
                    </span>
                </div>
            </div>

            <div class="text-center pt-4">
                <a href="{{ route('home') }}" class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-600 hover:text-emerald-700 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Kembali ke Beranda Utama</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
