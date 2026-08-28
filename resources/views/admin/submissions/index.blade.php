@extends('layouts.admin')

@section('title', 'Pengajuan & Approval — ' . $mosque->name)
@section('page_title', 'Pengajuan Anggaran & Approval Engine')
@section('page_subtitle', 'Alur persetujuan proposal kegiatan, pengadaan sarana, verifikasi bendahara & persetujuan ketua takmir.')

@section('content')
<div class="space-y-8" x-data="{ subModal: false, reviewModal: false, selectedSubId: '', selectedTitle: '' }">
    <div class="flex justify-between items-center">
        <h3 class="font-heading font-bold text-lg text-slate-900">Daftar Pengajuan Anggaran & Proposal</h3>
        <button @click="subModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 shadow-sm transition">
            <i data-lucide="file-plus" class="w-4 h-4"></i>
            <span>Buat Pengajuan Baru</span>
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">No. Pengajuan</th>
                        <th class="py-3 px-4">Judul Usulan / Kegiatan</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Pengaju</th>
                        <th class="py-3 px-4">Usulan Anggaran</th>
                        <th class="py-3 px-4">Tahapan Approval</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($submissions as $sub)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-mono font-bold text-slate-600">{{ $sub->submission_number }}</td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-900 block">{{ $sub->title }}</span>
                                <span class="text-slate-500 text-[11px] block">{{ Str::limit($sub->description, 60) }}</span>
                            </td>
                            <td class="py-3 px-4 text-slate-600">{{ $sub->category->value }}</td>
                            <td class="py-3 px-4 text-slate-700 font-medium">{{ $sub->applicant->name }}</td>
                            <td class="py-3 px-4 font-bold text-slate-900">
                                {{ $sub->proposed_amount ? 'Rp' . number_format((float) $sub->proposed_amount, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $sub->current_stage->value === 'APPROVED' ? 'bg-emerald-100 text-emerald-800' : ($sub->current_stage->value === 'REJECTED' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ $sub->current_stage->value }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                @if(!in_array($sub->current_stage->value, ['APPROVED', 'REJECTED']))
                                    <button @click="reviewModal = true; selectedSubId = '{{ $sub->id }}'; selectedTitle = '{{ $sub->title }}'" class="bg-slate-900 hover:bg-slate-800 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                        Review & Putuskan
                                    </button>
                                @else
                                    <span class="text-slate-400 text-[11px]">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400 italic">Belum ada pengajuan proposal kegiatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $submissions->links() }}
        </div>
    </div>

    <!-- Modal Review & Putusan -->
    <div x-show="reviewModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="reviewModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Review & Persetujuan Pengajuan</h3>
                <button @click="reviewModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <p class="text-xs text-slate-600 font-semibold" x-text="selectedTitle"></p>

            <form :action="'/admin/pengajuan/' + selectedSubId + '/review'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Keputusan Review *</label>
                    <select name="decision" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                        <option value="APPROVE">Setujui (Approve & Lanjutkan)</option>
                        <option value="REVISION_REQUESTED">Minta Perbaikan / Revisi</option>
                        <option value="REJECT">Tolak Pengajuan (Reject)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Pertimbangan / Arahan</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Tuliskan catatan persetujuan atau poin yang perlu direvisi..."></textarea>
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="reviewModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-md">Kirim Keputusan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Buat Pengajuan -->
    <div x-show="subModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="subModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Buat Usulan / Pengajuan Baru</h3>
                <button @click="subModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.submissions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Pengajuan *</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Proposal Pengadaan Karpet Shaf Utama">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori *</label>
                        <select name="category" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                            <option value="ACTIVITY_PROPOSAL">Proposal Kegiatan / Acara</option>
                            <option value="BUDGET_DISBURSEMENT">Pencairan Dana Anggaran</option>
                            <option value="FACILITY_REQUEST">Pengadaan Sarana & Fasilitas</option>
                            <option value="MAINTENANCE_REQUEST">Perbaikan & Pemeliharaan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Estimasi Anggaran (Rp)</label>
                        <input type="number" name="proposed_amount" min="0" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="15000000">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Rincian & Justifikasi Kebutuhan *</label>
                    <textarea name="description" rows="3" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none"></textarea>
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="subModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-md">Ajukan Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
