@extends('layouts.admin')

@section('title', 'Program Donasi & Campaign — ' . $mosque->name)
@section('page_title', 'Program Donasi & Infaq')
@section('page_subtitle', 'Kelola program penggalangan dana, target nominal, dan publikasi ke website publik.')

@section('content')
<div class="space-y-8" x-data="{ modalOpen: false }">
    <div class="flex justify-between items-center">
        <h3 class="font-heading font-bold text-lg text-slate-900">Daftar Program Donasi Aktif</h3>
        <button @click="modalOpen = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 shadow-sm transition">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Buat Program Baru</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($campaigns as $camp)
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="flex justify-between items-start">
                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-0.5 rounded uppercase">
                            {{ $camp->category }}
                        </span>
                        <span class="text-[11px] text-slate-400 font-semibold">{{ $camp->donations_count }} Donatur</span>
                    </div>

                    <h4 class="font-heading font-bold text-base text-slate-900">{{ $camp->title }}</h4>
                    <p class="text-xs text-slate-500 line-clamp-2">{{ $camp->description }}</p>

                    <div class="space-y-1.5 pt-2">
                        <div class="flex justify-between text-xs font-semibold text-slate-700">
                            <span>Rp{{ number_format((float) $camp->collected_amount, 0, ',', '.') }}</span>
                            <span class="text-emerald-700">{{ $camp->progress_percentage }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $camp->progress_percentage }}%"></div>
                        </div>
                        @if($camp->target_amount)
                            <span class="text-[10px] text-slate-400 block">Target: Rp{{ number_format((float) $camp->target_amount, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-between items-center text-xs">
                    <span class="text-slate-400">Mulai: {{ $camp->start_date->format('d/m/Y') }}</span>
                    <a href="{{ route('public.donations.show', [$mosque->slug, $camp->slug]) }}" target="_blank" class="text-emerald-700 font-semibold hover:underline flex items-center space-x-1">
                        <span>Lihat Publik</span>
                        <i data-lucide="external-link" class="w-3 h-3"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Create Campaign -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-200" @click.outside="modalOpen = false">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <h3 class="font-heading font-bold text-lg text-slate-900">Buat Program Donasi Baru</h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.campaigns.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Program *</label>
                    <input type="text" name="title" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-700 focus:outline-none" placeholder="Contoh: Renovasi Menara & Karpet Masjid">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori *</label>
                        <select name="category" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                            <option value="INFAQ">Infaq & Sedekah</option>
                            <option value="WAKAF">Wakaf Pembangunan</option>
                            <option value="YATIM">Santunan Yatim & Dhuafa</option>
                            <option value="RENOVASI">Renovasi Sarana</option>
                            <option value="RAMADHAN">Program Ramadhan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Target Nominal (Rp)</label>
                        <input type="number" name="target_amount" min="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="50000000">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Mulai *</label>
                        <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Batas Akhir (Opsional)</label>
                        <input type="date" name="end_date" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi & Rincian Kebutuhan *</label>
                    <textarea name="description" rows="3" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Jelaskan tujuan penggalangan dan manfaat bagi umat..."></textarea>
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="is_feat" name="is_featured" value="1" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600">
                    <label for="is_feat" class="text-xs text-slate-700 font-medium">Tampilkan sebagai program unggulan di beranda</label>
                </div>

                <div class="pt-4 flex justify-end space-x-2">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs transition shadow-md">Publikasikan Program</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
