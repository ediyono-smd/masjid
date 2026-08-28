@extends('layouts.admin')

@section('title', 'Zakat & Wakaf (ZISWAF) — ' . $mosque->name)
@section('page_title', 'Pengelolaan Zakat, Infaq & Wakaf')
@section('page_subtitle', 'Kalkulator Zakat Fitrah/Maal, penerimaan setoran, dan penerbitan bukti setor resmi ber-QR.')

@section('content')
<div class="space-y-8" x-data="{ zakatModal: false, zakatType: 'FITRAH_UANG', souls: 1, rateUang: 45000, get totalNominal() { return this.souls * this.rateUang; }, get totalBeras() { return (this.souls * 2.5).toFixed(1); } }">
    <!-- Top Summary Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block">Total Zakat Fitrah (Beras)</span>
            <span class="font-heading font-extrabold text-2xl text-emerald-700 block mt-1">{{ number_format($zakatFitrahTotalBeras, 1) }} Kg</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block">Total Zakat Fitrah (Uang)</span>
            <span class="font-heading font-extrabold text-2xl text-slate-900 block mt-1">Rp{{ number_format($zakatFitrahTotalUang, 0, ',', '.') }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block">Total Penerimaan Zakat Maal</span>
            <span class="font-heading font-extrabold text-2xl text-gold-600 block mt-1">Rp{{ number_format($zakatMaalTotal, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Actions & Table -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-heading font-bold text-base text-slate-900">Buku Penerimaan Setoran Zakat</h3>
            <button @click="zakatModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Catat Penerimaan Zakat</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Nama Muzakki</th>
                        <th class="py-3 px-4">Jenis Zakat</th>
                        <th class="py-3 px-4">Jiwa</th>
                        <th class="py-3 px-4">Jumlah (Beras / Rp)</th>
                        <th class="py-3 px-4">Amil Penerima</th>
                        <th class="py-3 px-4">Kode Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($zakatPayments as $z)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 text-slate-500">{{ $z->payment_date->format('d/m/Y') }}</td>
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $z->muzakki_name }}</td>
                            <td class="py-3 px-4 font-medium text-emerald-800">{{ $z->zakat_type }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $z->souls_count }} Jiwa</td>
                            <td class="py-3 px-4 font-bold text-slate-800">
                                @if($z->zakat_type === 'FITRAH_BERAS')
                                    {{ number_format((float) $z->quantity_kg, 1) }} Kg
                                @else
                                    Rp{{ number_format((float) $z->amount_rupiah, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-500">{{ $z->receivedBy?->name ?? 'Amil' }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('verify', $z->verification_code) }}" target="_blank" class="font-mono text-emerald-700 hover:underline flex items-center space-x-1">
                                    <span>{{ $z->verification_code }}</span>
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400 italic">Belum ada transaksi zakat tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Catat Zakat & Kalkulator -->
    <div x-show="zakatModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="zakatModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Penerimaan & Kalkulator Zakat</h3>
                <button @click="zakatModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.zakat.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Muzakki *</label>
                    <input type="text" name="muzakki_name" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="H. Ahmad...">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Zakat *</label>
                        <select name="zakat_type" x-model="zakatType" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                            <option value="FITRAH_UANG">Fitrah (Uang Tunai)</option>
                            <option value="FITRAH_BERAS">Fitrah (Beras / Makanan)</option>
                            <option value="MAAL">Zakat Maal (Harta / Tabungan)</option>
                            <option value="PROFESI">Zakat Penghasilan / Profesi</option>
                            <option value="FIDYAH">Fidyah</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Jiwa *</label>
                        <input type="number" name="souls_count" x-model="souls" min="1" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                </div>

                <!-- Kalkulasi Dinamis -->
                <div x-show="zakatType === 'FITRAH_UANG'" class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 text-xs">
                    <div class="flex justify-between font-semibold text-emerald-900">
                        <span>Kalkulasi Otomatis (Rp45.000/jiwa):</span>
                        <span class="font-bold font-heading text-sm" x-text="'Rp' + totalNominal.toLocaleString('id-ID')"></span>
                    </div>
                </div>

                <div x-show="zakatType === 'FITRAH_BERAS'" class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 text-xs">
                    <div class="flex justify-between font-semibold text-emerald-900">
                        <span>Total Beras (2.5 Kg/jiwa):</span>
                        <span class="font-bold font-heading text-sm" x-text="totalBeras + ' Kg'"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div x-show="zakatType !== 'FITRAH_BERAS'">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nominal Rupiah</label>
                        <input type="number" name="amount_rupiah" :value="zakatType === 'FITRAH_UANG' ? totalNominal : ''" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Rp...">
                    </div>
                    <div x-show="zakatType === 'FITRAH_BERAS'">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kuantitas Beras (Kg)</label>
                        <input type="number" step="0.1" name="quantity_kg" :value="totalBeras" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Metode Bayar *</label>
                        <select name="payment_method" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                            <option value="CASH">Tunai</option>
                            <option value="TRANSFER">Transfer Bank</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Bayar *</label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="zakatModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-md">Simpan & Terbitkan Bukti QR</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
