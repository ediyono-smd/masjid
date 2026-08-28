@extends('layouts.admin')

@section('title', 'Inventaris & Aset — ' . $mosque->name)
@section('page_title', 'Aset Inventaris & Riwayat Maintenance')
@section('page_subtitle', 'Pencatatan sarana ibadah, audio visual, AC, genset, kondisi fisik, dan log pemeliharaan berkala.')

@section('content')
<div class="space-y-8" x-data="{ itemModal: false, maintenanceModal: false, selectedItemId: '' }">
    <div class="flex justify-between items-center">
        <h3 class="font-heading font-bold text-lg text-slate-900">Daftar Inventaris Masjid</h3>
        <button @click="itemModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 shadow-sm transition">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Tambah Barang Inventaris</span>
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Kode Aset</th>
                        <th class="py-3 px-4">Nama Barang</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Jumlah & Unit</th>
                        <th class="py-3 px-4">Lokasi Ruang</th>
                        <th class="py-3 px-4">Kondisi Fisik</th>
                        <th class="py-3 px-4">Sumber Perolehan</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inventories as $inv)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-mono font-bold text-slate-600">{{ $inv->item_code }}</td>
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $inv->name }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $inv->category?->name ?? 'Umum' }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-800">{{ $inv->quantity }} {{ $inv->unit }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $inv->room_location ?? 'Ruang Utama' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $inv->condition === 'GOOD' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $inv->condition }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-500">{{ $inv->acquisition_source }}</td>
                            <td class="py-3 px-4 text-right">
                                <button @click="maintenanceModal = true; selectedItemId = '{{ $inv->id }}'" class="text-xs text-emerald-700 hover:text-emerald-800 font-semibold">
                                    Catat Servis
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-400 italic">Belum ada data barang inventaris.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Inventaris -->
    <div x-show="itemModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="itemModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Tambah Barang Inventaris</h3>
                <button @click="itemModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.inventory.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Barang / Aset *</label>
                        <input type="text" name="item_code" value="INV-{{ date('Y') }}-{{ rand(100, 999) }}" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                        <select name="category_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Barang *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Sound System / Karpet Turki / AC Split 2PK">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah *</label>
                        <input type="number" name="quantity" value="1" min="1" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Satuan Unit *</label>
                        <input type="text" name="unit" value="Unit" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Unit / Roll / Set">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Sumber Perolehan *</label>
                        <select name="acquisition_source" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                            <option value="PURCHASE">Pembelian Kas Masjid</option>
                            <option value="WAKAF">Wakaf / Hibah Jamaah</option>
                            <option value="GOVERNMENT_GRANT">Bantuan Pemerintah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kondisi Fisik *</label>
                        <select name="condition" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                            <option value="GOOD">Baik & Normal</option>
                            <option value="MINOR_DAMAGE">Rusak Ringan</option>
                            <option value="MAJOR_DAMAGE">Rusak Berat</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Lokasi Penempatan Ruangan</label>
                    <input type="text" name="room_location" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Ruang Shalat Utama / Ruang Sound">
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="itemModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-md">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
