@extends('layouts.admin')

@section('title', 'Program Sosial & Mustahiq — ' . $mosque->name)
@section('page_title', 'Program Sosial & Penyaluran Bantuan')
@section('page_subtitle', 'Kelola data mustahiq (asnaf 8), program santunan, dan dokumentasi penyaluran.')

@section('content')
<div class="space-y-8" x-data="{ recipientModal: false, programModal: false, distributeModal: false, selectedProgramId: '', selectedRecipientId: '' }">
    <!-- Action Header -->
    <div class="flex flex-wrap justify-between items-center gap-3">
        <h3 class="font-heading font-bold text-lg text-slate-900">Program Sosial & Santunan Aktif</h3>
        <div class="flex space-x-2">
            <button @click="recipientModal = true" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 transition">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>Tambah Mustahiq</span>
            </button>
            <button @click="programModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Buat Program Sosial</span>
            </button>
        </div>
    </div>

    <!-- Active Social Programs Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($programs as $prog)
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between space-y-4">
                <div class="space-y-2">
                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase">{{ $prog->category }}</span>
                    <h4 class="font-heading font-bold text-base text-slate-900">{{ $prog->name }}</h4>
                    <p class="text-xs text-slate-500 line-clamp-2">{{ $prog->description }}</p>

                    <div class="pt-2 text-xs space-y-1">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Anggaran:</span>
                            <span class="font-bold text-slate-800">Rp{{ number_format((float) $prog->budget, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Realisasi:</span>
                            <span class="font-bold text-emerald-700">Rp{{ number_format((float) $prog->realized_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Penerima Manfaat:</span>
                            <span class="font-bold text-slate-800">{{ $prog->actual_recipients_count }} / {{ $prog->target_recipients_count }} Jiwa</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                    <span class="text-[11px] text-slate-400">Distribusi: {{ $prog->distributions_count }} kali</span>
                    <button @click="distributeModal = true; selectedProgramId = '{{ $prog->id }}'" class="bg-emerald-50 hover:bg-emerald-700 text-emerald-800 hover:text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        Salurkan Bantuan
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Mustahiq Database Table -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-heading font-bold text-base text-slate-900">Database Mustahiq & Penerima Manfaat</h3>
            <span class="text-xs text-slate-500">Terverifikasi Takmir</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Kategori Asnaf</th>
                        <th class="py-3 px-4">Alamat Lingkungan</th>
                        <th class="py-3 px-4">Tanggungan</th>
                        <th class="py-3 px-4">No. HP</th>
                        <th class="py-3 px-4">Catatan Kondisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recipients as $rec)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $rec->full_name }}</td>
                            <td class="py-3 px-4">
                                <span class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded text-[10px] font-bold uppercase">{{ $rec->asnaf_category }}</span>
                            </td>
                            <td class="py-3 px-4 text-slate-600">{{ $rec->address }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $rec->dependents_count }} Orang</td>
                            <td class="py-3 px-4 text-slate-600">{{ $rec->phone ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-500 max-w-xs truncate">{{ $rec->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Mustahiq -->
    <div x-show="recipientModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="recipientModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Tambah Data Mustahiq</h3>
                <button @click="recipientModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.social.recipient.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="full_name" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Asnaf *</label>
                        <select name="asnaf_category" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                            <option value="FAKIR">Fakir</option>
                            <option value="MISKIN">Miskin</option>
                            <option value="YATIM">Anak Yatim</option>
                            <option value="DHUAFA">Dhuafa</option>
                            <option value="GHARIMIN">Gharimin</option>
                            <option value="MUALLAF">Muallaf</option>
                            <option value="FISABILILLAH">Fisabilillah</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Tanggungan *</label>
                        <input type="number" name="dependents_count" value="1" min="0" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap *</label>
                    <textarea name="address" rows="2" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">No. Kontak / WhatsApp</label>
                    <input type="text" name="phone" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="08xxxxxxxx">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Kondisi / Kebutuhan</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Contoh: Sakit menahun, janda lansia..."></textarea>
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="recipientModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs shadow-md">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Salurkan Bantuan -->
    <div x-show="distributeModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="distributeModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Catat Penyaluran Bantuan</h3>
                <button @click="distributeModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.social.distribute.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Program Sosial *</label>
                    <select name="program_id" x-model="selectedProgramId" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                        @foreach($programs as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Penerima Mustahiq *</label>
                    <select name="recipient_id" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                        @foreach($recipients as $r)
                            <option value="{{ $r->id }}">{{ $r->full_name }} ({{ $r->asnaf_category }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal *</label>
                        <input type="date" name="distribution_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nilai Bantuan (Rp) *</label>
                        <input type="number" name="nominal_value" required min="0" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="500000">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Rincian Paket / Keterangan *</label>
                    <textarea name="package_description" rows="2" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Paket Sembako Beras 10kg + Uang Tunai..."></textarea>
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="distributeModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-md">Simpan Penyaluran</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Program Baru -->
    <div x-show="programModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="programModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Buat Program Sosial Baru</h3>
                <button @click="programModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.social.program.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Program *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Santunan Rutin Yatim">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori *</label>
                        <select name="category" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                            <option value="SANTUNAN">Santunan</option>
                            <option value="SEMBAKO">Sembako</option>
                            <option value="BEASISWA">Beasiswa Santri</option>
                            <option value="KESEHATAN">Layanan Kesehatan</option>
                            <option value="TANGGAP_BENCANA">Tanggap Bencana</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Anggaran (Rp) *</label>
                        <input type="number" name="budget" required min="0" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Target Jiwa *</label>
                        <input type="number" name="target_recipients_count" value="50" min="1" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Mulai *</label>
                        <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi *</label>
                    <textarea name="description" rows="2" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none"></textarea>
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="programModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-md">Simpan Program</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
