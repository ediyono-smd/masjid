<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Kas — {{ $mosque->name }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #1e293b; margin: 20px; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #047857; padding-bottom: 12px; margin-bottom: 20px; }
        .mosque-name { font-size: 16px; font-weight: bold; color: #047857; text-transform: uppercase; }
        .mosque-address { font-size: 10px; color: #64748b; margin-top: 2px; }
        .report-title { font-size: 13px; font-weight: bold; margin-top: 10px; color: #0f172a; text-decoration: underline; }
        .summary-box { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .summary-box td { padding: 8px; border: 1px solid #cbd5e1; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th { background-color: #f1f5f9; color: #334155; padding: 7px 5px; font-size: 10px; border: 1px solid #cbd5e1; text-align: left; }
        .table td { padding: 6px 5px; border: 1px solid #e2e8f0; font-size: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-success { color: #047857; font-weight: bold; }
        .text-danger { color: #dc2626; font-weight: bold; }
        .signatures { width: 100%; margin-top: 40px; }
        .signatures td { width: 50%; text-align: center; font-size: 11px; }
    </style>
</head>
<body>
    <!-- Header Kop Surat -->
    <div class="header">
        <div class="mosque-name">{{ $mosque->name }}</div>
        <div class="mosque-address">{{ $mosque->address_line }}, {{ $mosque->city }}, {{ $mosque->province }} {{ $mosque->postal_code }}</div>
        <div class="mosque-address">Kontak / Sekretariat: {{ $mosque->phone ?? '-' }} • Email: {{ $mosque->email ?? '-' }}</div>
        <div class="report-title">LAPORAN PERTANGGUNGJAWABAN KAS BULANAN</div>
        <div style="font-size: 11px; font-weight: bold; margin-top: 3px;">
            Periode: {{ Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}
        </div>
    </div>

    <!-- Summary Box -->
    <table class="summary-box">
        <tr>
            <td style="background-color: #f8fafc; font-weight: bold;">Total Pemasukan Kas:</td>
            <td class="text-right text-success">Rp{{ number_format($totalIncome, 0, ',', '.') }}</td>
            <td style="background-color: #f8fafc; font-weight: bold;">Total Pengeluaran Kas:</td>
            <td class="text-right text-danger">Rp{{ number_format($totalExpense, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="2" style="background-color: #ecfdf5; font-weight: bold; color: #047857;">Surplus / (Defisit) Kas Periode Ini:</td>
            <td colspan="2" class="text-right" style="background-color: #ecfdf5; font-weight: bold; font-size: 12px; color: #047857;">
                Rp{{ number_format($netBalance, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <!-- Rincian Transaksi -->
    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 14%;">No. Ref</th>
                <th style="width: 18%;">Kategori</th>
                <th style="width: 31%;">Uraian Keterangan</th>
                <th style="width: 20%;" class="text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $idx => $tx)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $tx->transaction_date->format('d/m/Y') }}</td>
                    <td style="font-family: monospace;">{{ $tx->reference_number }}</td>
                    <td>{{ $tx->incomeCategory?->name ?? $tx->expenseCategory?->name }}</td>
                    <td>{{ $tx->description }}</td>
                    <td class="text-right {{ $tx->transaction_type->value === 'INCOME' ? 'text-success' : 'text-danger' }}">
                        {{ $tx->transaction_type->value === 'INCOME' ? '+' : '-' }}Rp{{ number_format((float) $tx->amount, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px; color: #94a3b8;">Tidak ada catatan transaksi kas pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signatures -->
    <table class="signatures">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Ketua Takmir</strong><br><br><br><br>
                ( _________________________ )
            </td>
            <td>
                {{ $mosque->city }}, {{ Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                <strong>Bendahara Masjid</strong><br><br><br><br>
                ( _________________________ )
            </td>
        </tr>
    </table>

    <div style="margin-top: 30px; font-size: 8px; color: #94a3b8; text-align: center;">
        Dokumen ini diterbitkan secara elektronik melalui Platform MASJID INDONESIA pada {{ $generatedAt }}
    </div>
</body>
</html>
