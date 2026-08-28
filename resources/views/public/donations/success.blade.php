@extends('layouts.public')

@section('title', 'Instruksi Pembayaran Donasi — ' . $mosque->name)

@section('content')
<div class="max-w-xl mx-auto px-4 py-16">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xl text-center space-y-6">
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 mx-auto flex items-center justify-center">
            <i data-lucide="check" class="w-8 h-8"></i>
        </div>

        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900">Niat Infaq Telah Diterima!</h1>
            <p class="text-xs text-slate-500 mt-1">Silakan lakukan pembayaran sesuai dengan QRIS resmi masjid berikut:</p>
        </div>

        <!-- Static / Dynamic QRIS Container -->
        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200 max-w-xs mx-auto space-y-3">
            <div class="w-48 h-48 mx-auto bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center">
                <!-- QR Code representation -->
                <i data-lucide="qr-code" class="w-36 h-36 text-slate-800"></i>
            </div>
            <span class="text-[11px] font-bold text-slate-600 block">Scan dengan Aplikasi Bank / e-Wallet</span>
            <span class="text-[10px] text-slate-400 block">NMID: ID102026889900 • {{ $mosque->name }}</span>
        </div>

        <div class="bg-amber-50 p-4 rounded-xl text-xs text-amber-900 border border-amber-200 text-left space-y-1">
            <span class="font-bold block">Kode Validasi Transaksi:</span>
            <code class="font-mono text-xs bg-white px-2 py-0.5 rounded border border-amber-300 block font-bold text-amber-950">{{ $code }}</code>
            <p class="text-[11px] text-amber-800 mt-1">Setelah takmir memverifikasi dana masuk, Anda dapat mengunduh e-Kwitansi resmi dengan memindai link verifikasi:</p>
            <a href="{{ route('verify', $code) }}" target="_blank" class="text-emerald-700 font-bold underline block pt-1">Cek Status Verifikasi: /verify/{{ $code }}</a>
        </div>

        <div class="pt-4 flex justify-center space-x-3">
            <a href="{{ route('public.mosque', $mosque->slug) }}" class="bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold px-6 py-2.5 rounded-xl transition">
                Kembali ke Beranda Masjid
            </a>
        </div>
    </div>
</div>
@endsection
