@extends('layouts.admin')

@section('title', 'Kajian & Agenda — ' . $mosque->name)
@section('page_title', 'Agenda Kajian & Acara Masjid')
@section('page_subtitle', 'Kelola jadwal majelis taklim, penceramah, lokasi, dan formulir pendaftaran jamaah.')

@section('content')
<div class="space-y-8" x-data="{ eventModal: false }">
    <div class="flex justify-between items-center">
        <h3 class="font-heading font-bold text-lg text-slate-900">Daftar Agenda Kajian & PHBI</h3>
        <button @click="eventModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 shadow-sm transition">
            <i data-lucide="calendar-plus" class="w-4 h-4"></i>
            <span>Tambah Agenda Baru</span>
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Judul Kegiatan</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Narasumber / Ustadz</th>
                        <th class="py-3 px-4">Waktu Mulai</th>
                        <th class="py-3 px-4">Lokasi</th>
                        <th class="py-3 px-4">Peserta Terdaftar</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($events as $evt)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $evt->title }}</td>
                            <td class="py-3 px-4">
                                <span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded text-[10px] font-bold uppercase">{{ $evt->category?->name ?? 'Kajian' }}</span>
                            </td>
                            <td class="py-3 px-4 font-medium text-slate-800">{{ $evt->speaker_name ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">{{ $evt->start_datetime->translatedFormat('d M Y H:i') }} WIB</td>
                            <td class="py-3 px-4 text-slate-600">{{ $evt->location }}</td>
                            <td class="py-3 px-4 font-semibold text-emerald-700">{{ $evt->registered_participants }} Jamaah</td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <a href="{{ route('public.events.show', [$mosque->slug, $evt->slug]) }}" target="_blank" class="text-slate-600 hover:text-emerald-700 font-semibold">Lihat</a>
                                <form action="{{ route('admin.events.destroy', $evt->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kegiatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400 italic">Belum ada agenda kajian yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $events->links() }}
        </div>
    </div>

    <!-- Modal Tambah Agenda -->
    <div x-show="eventModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="eventModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Tambah Agenda Kajian / Kegiatan</h3>
                <button @click="eventModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.events.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Kegiatan *</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Contoh: Kajian Tafsir Juz 'Amma">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                        <select name="event_category_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Lokasi Ruangan *</label>
                        <input type="text" name="location" value="Ruang Utama Masjid" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Penceramah / Ustadz</label>
                        <input type="text" name="speaker_name" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Ustadz...">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Gelar / Keahlian</label>
                        <input type="text" name="speaker_title" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Pakar Hadits">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Waktu Mulai *</label>
                        <input type="datetime-local" name="start_datetime" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Waktu Selesai</label>
                        <input type="datetime-local" name="end_datetime" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi & Pokok Bahasan *</label>
                    <textarea name="description" rows="2" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none"></textarea>
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="reg_open" name="is_registration_open" value="1" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600">
                    <label for="reg_open" class="text-xs text-slate-700 font-medium">Buka pendaftaran RSVP jamaah online</label>
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="eventModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-md">Simpan Agenda</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
