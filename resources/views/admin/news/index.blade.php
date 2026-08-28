@extends('layouts.admin')

@section('title', 'Warta & Berita — ' . $mosque->name)
@section('page_title', 'Warta & Berita Masjid')
@section('page_subtitle', 'Publikasi artikel dakwah, liputan kegiatan ibadah, dan pengumuman kepengurusan.')

@section('content')
<div class="space-y-8" x-data="{ newsModal: false }">
    <div class="flex justify-between items-center">
        <h3 class="font-heading font-bold text-lg text-slate-900">Daftar Warta & Berita</h3>
        <button @click="newsModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 shadow-sm transition">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Tulis Berita Baru</span>
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Judul Berita</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Penulis</th>
                        <th class="py-3 px-4">Tanggal Rilis</th>
                        <th class="py-3 px-4">Status Publikasi</th>
                        <th class="py-3 px-4 text-right">Pembaca</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($newsList as $n)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $n->title }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $n->category?->name ?? 'Umum' }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $n->author->name }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ $n->published_at ? $n->published_at->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $n->is_published ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $n->is_published ? 'Tayang' : 'Draft' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-semibold text-slate-700">{{ $n->views_count }} views</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 italic">Belum ada warta berita ditulis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $newsList->links() }}
        </div>
    </div>

    <!-- Modal Tulis Berita -->
    <div x-show="newsModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="newsModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Tulis Warta Berita Baru</h3>
                <button @click="newsModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.news.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Berita / Warta *</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Judul artikel atau berita masjid...">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                    <select name="news_category_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Ringkasan Singkat</label>
                    <textarea name="summary" rows="2" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Ringkasan 1-2 kalimat..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Konten Lengkap Berita *</label>
                    <textarea name="content" rows="5" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none"></textarea>
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="pub" name="is_published" value="1" checked class="rounded border-slate-300 text-emerald-700">
                    <label for="pub" class="text-xs text-slate-700 font-semibold">Publikasikan langsung ke portal publik</label>
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="newsModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-md">Terbitkan Berita</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
