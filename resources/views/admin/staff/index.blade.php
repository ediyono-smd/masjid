@extends('layouts.admin')

@section('title', 'Struktur Pengurus Takmir — ' . $mosque->name)
@section('page_title', 'Struktur Kepengurusan Takmir')
@section('page_subtitle', 'Kelola data penanggung jawab, bidang kerja, dan masa bakti periode kepengurusan.')

@section('content')
<div class="space-y-8" x-data="{ staffModal: false }">
    <div class="flex justify-between items-center">
        <h3 class="font-heading font-bold text-lg text-slate-900">Daftar Pengurus Masjid</h3>
        <button @click="staffModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 shadow-sm transition">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Tambah Pengurus</span>
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Nama Pengurus</th>
                        <th class="py-3 px-4">Jabatan</th>
                        <th class="py-3 px-4">Bidang / Seksi</th>
                        <th class="py-3 px-4">Periode Bakti</th>
                        <th class="py-3 px-4">Kontak WhatsApp</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($staffList as $stf)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $stf->name }}</td>
                            <td class="py-3 px-4 font-medium text-emerald-800">{{ $stf->position }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $stf->department ?? 'Umum' }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $stf->period_start }} - {{ $stf->period_end }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $stf->phone_number ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <form action="{{ route('admin.staff.destroy', $stf->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data pengurus ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Pengurus -->
    <div x-show="staffModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="staffModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Tambah Pengurus Takmir</h3>
                <button @click="staffModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Ustadz / Bapak...">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jabatan *</label>
                    <input type="text" name="position" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Ketua Takmir / Bendahara / Seksi Dakwah">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Bidang / Departemen</label>
                    <input type="text" name="department" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Idarah / Imarah / Ri'ayah">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun Mulai *</label>
                        <input type="number" name="period_start" value="{{ date('Y') }}" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun Selesai *</label>
                        <input type="number" name="period_end" value="{{ date('Y') + 5 }}" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">No. WhatsApp</label>
                    <input type="text" name="phone_number" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="0812xxxxxxxx">
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="staffModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-md">Simpan Pengurus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
