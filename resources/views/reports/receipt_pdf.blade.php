<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran / Kwitansi Resmi</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #1e293b; margin: 15px; }
        .receipt-card { border: 2px solid #047857; border-radius: 8px; padding: 15px; }
        .header { border-bottom: 1px dashed #cbd5e1; padding-bottom: 10px; margin-bottom: 12px; }
        .title { font-size: 14px; font-weight: bold; color: #047857; text-align: center; }
        .subtitle { font-size: 9px; color: #64748b; text-align: center; }
        .grid-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .grid-table td { padding: 5px 4px; font-size: 10px; }
        .label { color: #64748b; width: 30%; }
        .val { font-weight: bold; color: #0f172a; }
        .amount-box { background-color: #ecfdf5; border: 1px solid #a7f3d0; padding: 8px; text-align: center; margin: 12px 0; border-radius: 6px; }
        .amount-text { font-size: 16px; font-weight: bold; color: #047857; }
        .footer { font-size: 8px; color: #94a3b8; text-align: center; margin-top: 15px; border-top: 1px dashed #cbd5e1; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="receipt-card">
        <div class="header">
            <div class="title">BUKTI PENERIMAAN INFAQ & ZISWAF RESMI</div>
            <div class="subtitle">Platform Manajemen Digital MASJID INDONESIA</div>
        </div>

        <table class="grid-table">
            <tr>
                <td class="label">Nomor Bukti / Validasi:</td>
                <td class="val" style="font-family: monospace;">{{ $data['document_number'] ?? $data['verification_code'] ?? 'DOC-123' }}</td>
            </tr>
            <tr>
                <td class="label">Telah Diterima Dari:</td>
                <td class="val">{{ $data['donor_name'] ?? $data['muzakki_name'] ?? 'Hamba Allah' }}</td>
            </tr>
            <tr>
                <td class="label">Masjid Penerima:</td>
                <td class="val">{{ $data['mosque_name'] ?? 'Masjid Indonesia' }}</td>
            </tr>
            <tr>
                <td class="label">Peruntukan / Program:</td>
                <td class="val">{{ $data['campaign_title'] ?? $data['zakat_type'] ?? 'Infaq Operasional' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Diterima:</td>
                <td class="val">{{ $data['transaction_date'] ?? date('d/m/Y') }}</td>
            </tr>
        </table>

        <div class="amount-box">
            <span style="font-size: 9px; color: #065f46; display: block;">Jumlah Nominal Terverifikasi:</span>
            <span class="amount-text">Rp{{ number_format((float) ($data['amount'] ?? 0), 0, ',', '.') }}</span>
        </div>

        <div class="footer">
            Kode Verifikasi Digital: <strong>{{ $data['verification_code'] ?? '-' }}</strong><br>
            Cek keabsahan dokumen resmi ini di: <strong>{{ url('/verify/' . ($data['verification_code'] ?? '')) }}</strong><br>
            Diterbitkan secara sistem pada {{ $generatedAt }}
        </div>
    </div>
</body>
</html>
