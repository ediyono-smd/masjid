@extends('layouts.public')

@section('title', $event->title . ' — ' . $mosque->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12 space-y-8">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-10 space-y-8">
        <div class="space-y-4">
            <span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full uppercase">
                {{ $event->category?->name ?? 'Kajian' }}
            </span>
            <h1 class="font-heading font-extrabold text-2xl sm:text-4xl text-slate-900 leading-tight">{{ $event->title }}</h1>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-b border-slate-100 py-4 text-xs text-slate-600">
                <div class="space-y-1">
                    <span class="text-slate-400 font-medium block">Narasumber / Ustadz:</span>
                    <span class="font-bold text-slate-900 text-sm block">{{ $event->speaker_name ?? 'Takmir Masjid' }}</span>
                    <span class="text-slate-500 block">{{ $event->speaker_title }}</span>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 font-medium block">Waktu & Tanggal:</span>
                    <span class="font-bold text-slate-900 text-sm block">{{ $event->start_datetime->translatedFormat('l, d F Y') }}</span>
                    <span class="text-emerald-700 font-semibold block">{{ $event->start_datetime->format('H:i') }} WIB s/d Selesai</span>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 font-medium block">Lokasi Kegiatan:</span>
                    <span class="font-bold text-slate-900 text-sm block">{{ $event->location }}</span>
                    <span class="text-slate-500 block">{{ $mosque->name }}</span>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="space-y-4 text-sm text-slate-700 leading-relaxed">
            <h3 class="font-heading font-bold text-base text-slate-900">Deskripsi & Pokok Bahasan</h3>
            <p>{{ $event->description }}</p>
        </div>

        <!-- RSVP Form if open -->
        @if($event->is_registration_open)
            <div class="bg-emerald-50/70 border border-emerald-200 p-6 sm:p-8 rounded-2xl space-y-4">
                <div class="flex items-center space-x-2 text-emerald-900">
                    <i data-lucide="ticket" class="w-5 h-5 text-emerald-700"></i>
                    <h3 class="font-heading font-bold text-base">Formulir Konfirmasi Kehadiran (RSVP)</h3>
                </div>
                <p class="text-xs text-emerald-800">Daftarkan kehadiran Anda untuk memudahkan takmir dalam menyiapkan konsumsi dan materi kajian.</p>

                <form action="{{ route('public.events.register', [$mosque->slug, $event->slug]) }}" method="POST" class="space-y-4 max-w-lg">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-700 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">No. WhatsApp / HP *</label>
                        <input type="text" name="phone" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-700 focus:outline-none" placeholder="0812xxxxxxxx">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email (Opsional)</label>
                        <input type="email" name="email" class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-700 focus:outline-none">
                    </div>

                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs px-6 py-3 rounded-xl transition shadow-sm">
                        Konfirmasi Pendaftaran Sekarang
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
