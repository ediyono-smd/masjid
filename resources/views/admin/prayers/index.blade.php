@extends('layouts.admin')

@section('title', 'Jadwal Shalat & Petugas — ' . $mosque->name)
@section('page_title', 'Jadwal Shalat & Petugas Ibadah')
@section('page_subtitle', 'Penyesuaian offset waktu adzan (ihtiyat), penjadwalan Khatib Jumat, dan Imam Rawatib.')

@section('content')
<div class="space-y-8" x-data="{ khatibModal: false }">
    <!-- Hisab Settings Card -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
        <h3 class="font-heading font-bold text-base text-slate-900 border-b border-slate-100 pb-3">Pengaturan Hisab & Offset Waktu Adzan (Menit)</h3>

        <form action="{{ route('admin.prayers.settings') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-2 sm:grid-cols-6 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Metode</label>
                    <select name="calculation_method" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <option value="KEMENAG">Kemenag RI</option>
                        <option value="MWL">Muslim World League</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Subuh (+m)</label>
                    <input type="number" name="fajr_offset_minutes" value="{{ $setting->fajr_offset_minutes }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-center font-bold">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Dzuhur (+m)</label>
                    <input type="number" name="dhuhr_offset_minutes" value="{{ $setting->dhuhr_offset_minutes }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-center font-bold">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Ashar (+m)</label>
                    <input type="number" name="asr_offset_minutes" value="{{ $setting->asr_offset_minutes }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-center font-bold">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Maghrib (+m)</label>
                    <input type="number" name="maghrib_offset_minutes" value="{{ $setting->maghrib_offset_minutes }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-center font-bold">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Isya (+m)</label>
                    <input type="number" name="isha_offset_minutes" value="{{ $setting->isha_offset_minutes }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-center font-bold">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-5 py-2 rounded-xl text-xs shadow-md">Simpan Pengaturan Hisab</button>
            </div>
        </form>
    </div>

    <!-- Khatib Scheduling Section -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex justify-between items-center pb-3 border-b border-slate-100">
            <h3 class="font-heading font-bold text-base text-slate-900">Jadwal Khatib & Muadzin Jumat</h3>
            <button @click="khatibModal = true" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold px-4 py-2 rounded-xl text-xs flex items-center space-x-1.5 transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Jadwalkan Khatib</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Tanggal Jumat</th>
                        <th class="py-3 px-4">Nama Khatib</th>
                        <th class="py-3 px-4">Tema / Judul Khutbah</th>
                        <th class="py-3 px-4">Muadzin & Bilal</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($khatibs as $k)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-bold text-emerald-800">{{ $k->schedule_date->translatedFormat('l, d F Y') }}</td>
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $k->assigned_name }}</td>
                            <td class="py-3 px-4 text-slate-600 italic">"{{ $k->title_or_theme ?? '-' }}"</td>
                            <td class="py-3 px-4 text-slate-600">{{ $k->muadzin_name ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded">{{ $k->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400 italic">Belum ada jadwal khatib terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Jadwalkan Khatib -->
    <div x-show="khatibModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="khatibModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Jadwalkan Khatib Jumat</h3>
                <button @click="khatibModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.prayers.khatib.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Jumat *</label>
                    <input type="date" name="schedule_date" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Khatib *</label>
                    <input type="text" name="assigned_name" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Prof. Dr. KH. ...">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul / Tema Khutbah</label>
                    <input type="text" name="title_or_theme" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Tema keutamaan...">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Petugas Muadzin</label>
                        <input type="text" name="muadzin_name" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Nama muadzin">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">No. Kontak Khatib</label>
                        <input type="text" name="phone" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="0812xxxxxxxx">
                    </div>
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="khatibModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-md">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
