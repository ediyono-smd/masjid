@extends('layouts.admin')

@section('title', 'Pengumuman — ' . $mosque->name)
@section('page_title', 'Pengumuman & Pemberitahuan')
@section('page_subtitle', 'Kelola banner maklumat, prioritas penting, dan sematan pengumuman di atas portal publik.')

@section('content')
<div class="space-y-8" x-data="{ annModal: false }">
    <div class="flex justify-between items-center">
        <h3 class="font-heading font-bold text-lg text-slate-900">Daftar Pengumuman Aktif</h3>
        <button @click="annModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 shadow-sm transition">
            <i data-lucide="bell-plus" class="w-4 h-4"></i>
            <span>Buat Pengumuman</span>
        </button>
    </div>

    <div class="space-y-4">
        @forelse($announcements as $ann)
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex justify-between items-start">
                <div class="space-y-2 max-w-2xl">
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $ann->priority === 'URGENT' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $ann->priority }}
                        </span>
                        @if($ann->is_pinned)
                            <span class="bg-gold-100 text-gold-900 text-[10px] font-bold px-2 py-0.5 rounded flex items-center space-x-1">
                                <i data-lucide="pin" class="w-3 h-3"></i>
                                <span>Disematkan di Atas Website</span>
                            </span>
                        @endif
                    </div>
                    <h4 class="font-heading font-bold text-base text-slate-900">{{ $ann->title }}</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $ann->body }}</p>
                </div>
                <span class="text-[11px] text-slate-400 whitespace-nowrap">{{ $ann->created_at->diffForHumans() }}</span>
            </div>
        @empty
            <div class="bg-white p-8 rounded-3xl border border-slate-200 text-center text-xs text-slate-400 italic">
                Belum ada pengumuman yang dipublikasikan.
            </div>
        @endforelse
    </div>

    <!-- Modal Buat Pengumuman -->
    <div x-show="annModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="annModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Buat Pengumuman Baru</h3>
                <button @click="annModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.announcements.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Pengumuman *</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Contoh: Jadwal Shalat Tarawih & Imam">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tingkat Prioritas *</label>
                    <select name="priority" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                        <option value="NORMAL">Normal</option>
                        <option value="HIGH">Tinggi (Penting)</option>
                        <option value="URGENT">Mendesak (Darurat / Khusus)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Isi Pengumuman *</label>
                    <textarea name="body" rows="3" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none"></textarea>
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="pin" name="is_pinned" value="1" class="rounded border-slate-300 text-emerald-700">
                    <label for="pin" class="text-xs text-slate-700 font-semibold">Sematkan sebagai bar pengumuman di atas portal website</label>
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="annModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-md">Simpan Pengumuman</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
