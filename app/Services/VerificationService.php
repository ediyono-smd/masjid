<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Donation;
use App\Models\QrCode;
use App\Models\QrScan;
use App\Models\ZakatPayment;
use Illuminate\Http\Request;

class VerificationService
{
    public function verifyCode(string $code, ?Request $request = null): array
    {
        // 1. Check in documents table
        $document = Document::where('verification_code', $code)
            ->with(['mosque', 'issuer'])
            ->first();

        // 2. Check in donations
        $donation = Donation::where('verification_code', $code)
            ->with(['mosque', 'campaign', 'verifiedBy'])
            ->first();

        // 3. Check in zakat
        $zakat = ZakatPayment::where('verification_code', $code)
            ->with(['mosque', 'receivedBy'])
            ->first();

        // Record scan analytics
        $qr = QrCode::where('token', $code)->first();
        if ($qr) {
            $qr->increment('scan_count');
            $qr->update(['last_scanned_at' => now()]);

            if ($request) {
                QrScan::create([
                    'qr_code_id' => $qr->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => substr($request->userAgent() ?? '', 0, 255),
                    'scanned_at' => now(),
                ]);
            }
        }

        if ($document) {
            return [
                'is_valid' => !$document->is_revoked,
                'status_label' => $document->is_revoked ? 'DOKUMEN DICABUT / TIDAK BERLAKU' : 'TERVERIFIKASI RESMI & SAH',
                'status_color' => $document->is_revoked ? 'red' : 'emerald',
                'document_number' => $document->document_number,
                'document_type' => $document->document_type->label(),
                'title' => $document->title,
                'mosque_name' => $document->mosque->name,
                'mosque_city' => $document->mosque->city,
                'issued_at' => $document->issued_at->translatedFormat('d F Y H:i WIB'),
                'issuer_name' => $document->issuer->name,
                'payload' => $this->sanitizePayload($document->payload_snapshot ?? []),
                'source' => 'document',
            ];
        }

        if ($donation) {
            $isValid = in_array($donation->status, ['PAID', 'VERIFIED']);
            return [
                'is_valid' => $isValid,
                'status_label' => $isValid ? 'BUKTI DONASI TERVERIFIKASI' : 'MENUNGGU VERIFIKASI PEMBAYARAN',
                'status_color' => $isValid ? 'emerald' : 'amber',
                'document_number' => 'DON-' . strtoupper(substr($donation->verification_code, -8)),
                'document_type' => 'e-Kwitansi Donasi Digital',
                'title' => 'Infaq / Donasi ' . ($donation->campaign->title ?? 'Kas Masjid'),
                'mosque_name' => $donation->mosque->name,
                'mosque_city' => $donation->mosque->city,
                'issued_at' => ($donation->verified_at ?? $donation->created_at)->translatedFormat('d F Y H:i WIB'),
                'issuer_name' => $donation->verifiedBy->name ?? 'Sistem Otomatis',
                'payload' => [
                    'Nama Donatur' => $this->maskName($donation->display_name),
                    'Nominal' => 'Rp' . number_format((float) $donation->amount, 0, ',', '.'),
                    'Metode Pembayaran' => $donation->payment_method,
                    'Program' => $donation->campaign->title ?? 'Infaq Umum',
                ],
                'source' => 'donation',
            ];
        }

        if ($zakat) {
            return [
                'is_valid' => true,
                'status_label' => 'BUKTI SETOR ZISWAF SAH',
                'status_color' => 'emerald',
                'document_number' => 'ZIS-' . strtoupper(substr($zakat->verification_code, -8)),
                'document_type' => 'Tanda Terima Setor ZISWAF',
                'title' => 'Penerimaan ' . $zakat->zakat_type,
                'mosque_name' => $zakat->mosque->name,
                'mosque_city' => $zakat->mosque->city,
                'issued_at' => $zakat->created_at->translatedFormat('d F Y H:i WIB'),
                'issuer_name' => $zakat->receivedBy->name ?? 'Amil Zakat Masjid',
                'payload' => [
                    'Nama Muzakki' => $this->maskName($zakat->muzakki_name),
                    'Jenis Zakat' => $zakat->zakat_type,
                    'Nominal / Beras' => $zakat->amount_rupiah > 0 ? 'Rp' . number_format((float) $zakat->amount_rupiah, 0, ',', '.') : $zakat->quantity_kg . ' Kg Beras',
                ],
                'source' => 'zakat',
            ];
        }

        return [
            'is_valid' => false,
            'status_label' => 'DOKUMEN TIDAK DITEMUKAN / KODE TIDAK VALID',
            'status_color' => 'red',
            'document_number' => '-',
            'document_type' => 'Tidak Diketahui',
            'title' => 'Peringatan Keamanan',
            'mosque_name' => '-',
            'mosque_city' => '-',
            'issued_at' => '-',
            'issuer_name' => '-',
            'payload' => [],
            'source' => 'unknown',
        ];
    }

    protected function sanitizePayload(array $payload): array
    {
        $sanitized = [];
        foreach ($payload as $key => $val) {
            if (is_string($val) && preg_match('/(phone|hp|nik|rekening|account)/i', $key)) {
                $sanitized[$key] = substr($val, 0, 4) . '********';
            } else {
                $sanitized[$key] = $val;
            }
        }
        return $sanitized;
    }

    protected function maskName(string $name): string
    {
        if (strtolower($name) === 'hamba allah') {
            return $name;
        }

        $parts = explode(' ', $name);
        if (count($parts) === 1) {
            return substr($name, 0, 3) . '***';
        }

        return $parts[0] . ' ' . substr($parts[1], 0, 1) . '***';
    }
}
