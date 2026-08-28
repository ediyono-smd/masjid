@extends('layouts.public')

@section('title', 'Kanal Donasi & Infaq Online — ' . $mosque->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
    <div class="text-center max-w-2xl mx-auto space-y-2">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">Amal Jariyah & ZISWAF</span>
        <h1 class="font-heading font-extrabold text-3xl text-slate-900">Salurkan Infaq & Donasi Terbaik</h1>
        <p class="text-xs sm:text-sm text-slate-500">{{ $mosque->name }} • Aman, Akuntabel & Dilengkapi Bukti QR Code Resmi</p>
    </div>

    @if($campaigns->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($campaigns as $camp)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded uppercase">
                                {{ $camp->category }}
                            </span>
                            <span class="text-[11px] text-slate-500">{{ $camp->donor_count }} Donatur</span>
                        </div>

                        <h3 class="font-heading font-bold text-lg text-slate-900">
                            <a href="{{ route('public.donations.show', [$mosque->slug, $camp->slug]) }}" class="hover:text-emerald-700 transition">{{ $camp->title }}</a>
                        </h3>

                        <p class="text-xs text-slate-600 line-clamp-2">{{ $camp->description }}</p>

                        <!-- Progress -->
                        <div class="space-y-1.5 pt-2">
                            <div class="flex justify-between text-xs font-semibold text-slate-700">
                                <span>Rp{{ number_format((float) $camp->collected_amount, 0, ',', '.') }}</span>
                                <span class="text-emerald-700">{{ $camp->progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-emerald-600 h-2.5 rounded-full" style="width: {{ $camp->progress_percentage }}%"></div>
                            </div>
                            @if($camp->target_amount)
                                <div class="text-[11px] text-slate-400">Target: Rp{{ number_format((float) $camp->target_amount, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 border-t border-slate-100">
                        <a href="{{ route('public.donations.show', [$mosque->slug, $camp->slug]) }}" class="w-full text-center block bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-2.5 rounded-xl text-xs transition">
                            Donasi Sekarang
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $campaigns->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
            <i data-lucide="heart-handshake" class="w-12 h-12 text-slate-400 mx-auto mb-3"></i>
            <h4 class="font-heading font-semibold text-base text-slate-800">Belum ada program donasi aktif</h4>
        </div>
    @endif
</div>
@endsection
