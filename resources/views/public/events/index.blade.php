@extends('layouts.public')

@section('title', 'Kajian & Agenda Kegiatan — ' . $mosque->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="text-center max-w-2xl mx-auto space-y-2">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">Majelis Ilmu & PHBI</span>
        <h1 class="font-heading font-extrabold text-3xl text-slate-900">Agenda Kajian & Kegiatan</h1>
        <p class="text-xs sm:text-sm text-slate-500">{{ $mosque->name }}</p>
    </div>

    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div class="p-6 space-y-3">
                        <span class="inline-block bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">
                            {{ $event->category?->name ?? 'Kajian' }}
                        </span>
                        <h3 class="font-heading font-bold text-lg text-slate-900">
                            <a href="{{ route('public.events.show', [$mosque->slug, $event->slug]) }}" class="hover:text-emerald-700 transition">{{ $event->title }}</a>
                        </h3>
                        <div class="text-xs text-slate-600 space-y-1.5 pt-1">
                            <div class="flex items-center space-x-2">
                                <i data-lucide="user" class="w-3.5 h-3.5 text-emerald-700"></i>
                                <span class="font-medium text-slate-800">{{ $event->speaker_name ?? 'Ustadz Pemateri' }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-emerald-700"></i>
                                <span>{{ $event->start_datetime->translatedFormat('l, d F Y - H:i WIB') }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-700"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-500">{{ $event->registered_participants }} Jamaah Terdaftar</span>
                        <a href="{{ route('public.events.show', [$mosque->slug, $event->slug]) }}" class="bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs px-4 py-2 rounded-xl transition">
                            Detail & RSVP
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $events->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
            <i data-lucide="calendar-x" class="w-12 h-12 text-slate-400 mx-auto mb-3"></i>
            <h4 class="font-heading font-semibold text-base text-slate-800">Belum ada agenda kajian</h4>
            <p class="text-xs text-slate-500 mt-1">Nantikan jadwal majelis taklim mendatang.</p>
        </div>
    @endif
</div>
@endsection
