@extends('layouts.admin')

@section('title', 'Buku Kas & Keuangan — ' . $mosque->name)
@section('page_title', 'Buku Kas & Transaksi Keuangan')
@section('page_subtitle', 'Pencatatan pemasukan, pengeluaran kas, rekonsiliasi, dan export laporan PDF resmi.')

@section('content')
<div class="space-y-8" x-data="{ modalOpen: false, modalType: 'INCOME' }">
    <!-- Top Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block">Total Saldo Kas Berjalan</span>
            <span class="font-heading font-extrabold text-2xl text-slate-900 block mt-1">Rp{{ number_format($summary['current_balance'], 0, ',', '.') }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-emerald-800 font-semibold block">Pemasukan Bulan Ini</span>
            <span class="font-heading font-extrabold text-2xl text-emerald-700 block mt-1">+Rp{{ number_format($summary['this_month_income'], 0, ',', '.') }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-red-800 font-semibold block">Pengeluaran Bulan Ini</span>
            <span class="font-heading font-extrabold text-2xl text-red-700 block mt-1">-Rp{{ number_format($summary['this_month_expense'], 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Actions & Filter Toolbar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <!-- Add Buttons -->
        <div class="flex items-center space-x-2">
            <button @click="modalOpen = true; modalType = 'INCOME'" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Catat Pemasukan</span>
            </button>
            <button @click="modalOpen = true; modalType = 'EXPENSE'" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 shadow-sm transition">
                <i data-lucide="minus-circle" class="w-4 h-4"></i>
                <span>Catat Pengeluaran</span>
            </button>
        </div>

        <!-- Filter & Export PDF -->
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.finances.export.pdf', ['month' => $month, 'year' => $year]) }}" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 transition">
                <i data-lucide="file-down" class="w-4 h-4"></i>
                <span>Unduh Laporan PDF</span>
            </a>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-heading font-bold text-base text-slate-900">Daftar Jurnal Transaksi Kas</h3>
            <span class="text-xs text-slate-500">Bulan {{ Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">No. Ref</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Tipe</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Uraian / Deskripsi</th>
                        <th class="py-3 px-4">Penerima / Pembayar</th>
                        <th class="py-3 px-4">Metode</th>
                        <th class="py-3 px-4 text-right">Nominal (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-mono font-bold text-slate-600">{{ $tx->reference_number }}</td>
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap">{{ $tx->transaction_date->format('d/m/Y') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $tx->transaction_type->value === 'INCOME' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $tx->transaction_type->value === 'INCOME' ? 'Masuk' : 'Keluar' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-600 font-medium">{{ $tx->incomeCategory?->name ?? $tx->expenseCategory?->name }}</td>
                            <td class="py-3 px-4 font-medium text-slate-800 max-w-xs">{{ $tx->description }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ $tx->recipient_or_payer ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ $tx->payment_channel }}</td>
                            <td class="py-3 px-4 text-right font-bold {{ $tx->transaction_type->value === 'INCOME' ? 'text-emerald-700' : 'text-red-600' }}">
                                {{ $tx->transaction_type->value === 'INCOME' ? '+' : '-' }}Rp{{ number_format((float) $tx->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-400 italic">Belum ada transaksi tercatat pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Modal Form Tambah Transaksi -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-200" @click.outside="modalOpen = false">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <h3 class="font-heading font-bold text-lg text-slate-900" x-text="modalType === 'INCOME' ? 'Catat Pemasukan Kas' : 'Catat Pengeluaran Kas'"></h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.finances.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="transaction_type" :value="modalType">

                <!-- Kategori Selection -->
                <div x-show="modalType === 'INCOME'">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Pemasukan *</label>
                    <select name="income_category_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-700 focus:outline-none">
                        @foreach($incomeCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="modalType === 'EXPENSE'">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Pengeluaran *</label>
                    <select name="expense_category_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-red-600 focus:outline-none">
                        @foreach($expenseCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nominal -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nominal Transaksi (Rp) *</label>
                    <input type="number" name="amount" min="100" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-emerald-700 focus:outline-none" placeholder="1000000">
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Transaksi *</label>
                    <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                </div>

                <!-- Uraian -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Uraian / Keterangan *</label>
                    <textarea name="description" rows="2" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Misal: Infaq Tromol Jumat / Service AC Ruang Shalat"></textarea>
                </div>

                <!-- Penerima / Pembayar -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pihak Terkait (Penyetor / Penerima)</label>
                    <input type="text" name="recipient_or_payer" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Contoh: Toko Listrik / Hamba Allah">
                </div>

                <!-- Saluran Pembayaran -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Metode / Saluran *</label>
                    <select name="payment_channel" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                        <option value="CASH">Tunai (Kas Fisik)</option>
                        <option value="BANK_TRANSFER">Transfer Rekening Bank</option>
                        <option value="QRIS">QRIS Digital</option>
                    </select>
                </div>

                <div class="pt-4 flex justify-end space-x-2">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition">Batal</button>
                    <button type="submit" :class="modalType === 'INCOME' ? 'bg-emerald-700 hover:bg-emerald-800' : 'bg-red-600 hover:bg-red-700'" class="px-6 py-2.5 text-white font-bold rounded-xl text-xs transition shadow-md">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
