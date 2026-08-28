@extends('layouts.admin')

@section('title', 'Data Jamaah — ' . $mosque->name)
@section('page_title', 'Database Jamaah & Warga')
@section('page_subtitle', 'Pendataan demografi jamaah sekitar, kepala keluarga, dan penandaan kategori mustahiq.')

@section('content')
<div class="space-y-8" x-data="{ jamaahModal: false }">
    <div class="flex justify-between items-center">
        <h3 class="font-heading font-bold text-lg text-slate-900">Daftar Jamaah Terdaftar</h3>
        <button @click="jamaahModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 shadow-sm transition">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Tambah Data Jamaah</span>
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Nama Jamaah</th>
                        <th class="py-3 px-4">Gender</th>
                        <th class="py-3 px-4">No. Kontak / WA</th>
                        <th class="py-3 px-4">RT / RW</th>
                        <th class="py-3 px-4">Pekerjaan</th>
                        <th class="py-3 px-4">Anggota Keluarga</th>
                        <th class="py-3 px-4">Status Khusus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($congregations as $j)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-bold text-slate-900">
                                {{ $j->name }}
                                @if($j->is_head_of_family)
                                    <span class="text-[10px] bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded ml-1">Kepala Keluarga</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-600">{{ $j->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $j->phone ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $j->rt_rw ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $j->occupation ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $j->family_members_count }} Jiwa</td>
                            <td class="py-3 px-4">
                                @if($j->is_mustahiq)
                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded">Mustahiq</span>
                                @else
                                    <span class="text-slate-400 text-[11px]">Muzakki / Reguler</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400 italic">Belum ada data jamaah tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $congregations->links() }}
        </div>
    </div>

    <!-- Modal Tambah Jamaah -->
    <div x-show="jamaahModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="jamaahModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Tambah Data Jamaah</h3>
                <button @click="jamaahModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.congregations.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Kelamin *</label>
                        <select name="gender" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">No. WhatsApp</label>
                        <input type="text" name="phone" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="08xxxxxxxx">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">RT / RW</label>
                        <input type="text" name="rt_rw" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="RT 02 / RW 05">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pekerjaan</label>
                        <input type="text" name="occupation" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Anggota Keluarga *</label>
                    <input type="number" name="family_members_count" value="1" min="1" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                </div>

                <div class="space-y-2 pt-1">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="kk" name="is_head_of_family" value="1" class="rounded border-slate-300 text-emerald-700">
                        <label for="kk" class="text-xs text-slate-700">Kepala Keluarga</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="mustahiq" name="is_mustahiq" value="1" class="rounded border-slate-300 text-amber-600">
                        <label for="mustahiq" class="text-xs text-slate-700">Tandai sebagai Mustahiq penerima santunan</label>
                    </div>
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="jamaahModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-md">Simpan Jamaah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
