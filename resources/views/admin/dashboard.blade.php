@extends('layouts.admin')

@section('title', 'Dashboard Utama Takmir — ' . $mosque->name)
@section('page_title', 'Dashboard Takmir ' . $mosque->name)
@section('page_subtitle', 'Ringkasan posisi keuangan, donasi, kegiatan ibadah, dan pengajuan anggaran.')

@section('content')
<div class="space-y-8">
    <!-- Top 4 Summary Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Saldo Kas -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-xs font-semibold">Total Saldo Kas Berjalan</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="font-heading font-extrabold text-2xl text-slate-900">
                Rp{{ number_format($stats['current_balance'], 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-500 flex items-center space-x-1 pt-1">
                <span class="text-emerald-700 font-bold">+Rp{{ number_format($stats['this_month_income'], 0, ',', '.') }}</span>
                <span>pemasukan bulan ini</span>
            </div>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-xs font-semibold">Pengeluaran Bulan Ini</span>
                <div class="w-8 h-8 rounded-xl bg-red-100 text-red-700 flex items-center justify-center">
                    <i data-lucide="trending-down" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="font-heading font-extrabold text-2xl text-slate-900">
                Rp{{ number_format($stats['this_month_expense'], 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-500">
                Operasional & program sosial
            </div>
        </div>

        <!-- Total Jamaah -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-xs font-semibold">Database Jamaah</span>
                <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="font-heading font-extrabold text-2xl text-slate-900">
                {{ number_format($stats['total_jamaah']) }}
            </div>
            <div class="text-[11px] text-slate-500">
                Warga lingkungan terdata
            </div>
        </div>

        <!-- Agenda & Program Aktif -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-xs font-semibold">Program Donasi Aktif</span>
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                    <i data-lucide="heart-handshake" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="font-heading font-extrabold text-2xl text-slate-900">
                {{ $stats['active_campaigns_count'] }} Program
            </div>
            <div class="text-[11px] text-slate-500">
                {{ $stats['upcoming_events_count'] }} agenda kajian mendatang
            </div>
        </div>
    </div>

    <!-- Chart & Approvals Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- 6-Month Cashflow Chart -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-bold text-base text-slate-900">Tren Arus Kas Masjid (6 Bulan Terakhir)</h3>
                    <p class="text-xs text-slate-500">Perbandingan pemasukan vs pengeluaran kas operasional.</p>
                </div>
            </div>

            <div class="h-64 sm:h-72">
                <canvas id="cashflowChart"></canvas>
            </div>
        </div>

        <!-- Pending Proposals / Submissions -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-heading font-bold text-base text-slate-900">Pengajuan Butuh Review</h3>
                <a href="{{ route('admin.submissions.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                @forelse($pendingSubmissions as $sub)
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 space-y-1.5 text-xs">
                        <div class="flex justify-between items-start">
                            <span class="font-bold text-slate-900 line-clamp-1">{{ $sub->title }}</span>
                            <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase flex-shrink-0">
                                {{ $sub->current_stage->value }}
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-500 flex justify-between">
                            <span>Diajukan: {{ $sub->applicant->name }}</span>
                            @if($sub->proposed_amount)
                                <span class="font-bold text-slate-800">Rp{{ number_format((float) $sub->proposed_amount, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 italic text-center py-6">Tidak ada pengajuan yang menunggu persetujuan.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Transactions & Donations Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Cash Ledger -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-heading font-bold text-base text-slate-900">Transaksi Kas Terbaru</h3>
                <a href="{{ route('admin.finances.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">Kelola Kas</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-100 font-semibold">
                            <th class="pb-2">Tanggal</th>
                            <th class="pb-2">Keterangan</th>
                            <th class="pb-2 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentTransactions as $tx)
                            <tr>
                                <td class="py-2.5 text-slate-500">{{ $tx->transaction_date->format('d/m/Y') }}</td>
                                <td class="py-2.5 font-medium text-slate-800">{{ $tx->description }}</td>
                                <td class="py-2.5 text-right font-bold {{ $tx->transaction_type->value === 'INCOME' ? 'text-emerald-700' : 'text-red-600' }}">
                                    {{ $tx->transaction_type->value === 'INCOME' ? '+' : '-' }}Rp{{ number_format((float) $tx->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Online Donations -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-heading font-bold text-base text-slate-900">Donasi & Infaq Masuk</h3>
                <a href="{{ route('admin.donations.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">Semua Donasi</a>
            </div>

            <div class="space-y-3">
                @foreach($recentDonations as $don)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-2xl border border-slate-100 text-xs">
                        <div>
                            <span class="font-bold text-slate-900 block">{{ $don->display_name }}</span>
                            <span class="text-[11px] text-slate-500 block">{{ $don->campaign->title ?? 'Infaq Operasional' }} • {{ $don->payment_method }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-emerald-700 block">Rp{{ number_format((float) $don->amount, 0, ',', '.') }}</span>
                            <span class="text-[10px] uppercase font-bold {{ $don->status === 'VERIFIED' ? 'text-emerald-600' : 'text-amber-600' }}">{{ $don->status }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('cashflowChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [
                    {
                        label: 'Pemasukan Kas',
                        data: {!! json_encode($chartData['income']) !!},
                        backgroundColor: '#047857',
                        borderRadius: 6,
                    },
                    {
                        label: 'Pengeluaran Kas',
                        data: {!! json_encode($chartData['expense']) !!},
                        backgroundColor: '#ef4444',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { family: 'Inter', size: 11 } }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return 'Rp' + (value / 1000000) + 'Jt';
                            },
                            font: { family: 'Inter', size: 10 }
                        }
                    },
                    x: {
                        ticks: { font: { family: 'Inter', size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endpush
