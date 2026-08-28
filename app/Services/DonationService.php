<?php

namespace App\Services;

use App\Enums\DocumentType;
use App\Enums\TransactionType;
use App\Models\Document;
use App\Models\Donation;
use App\Models\DonationCampaign;
use App\Models\DonationPayment;
use App\Models\Donor;
use App\Models\FinancialTransaction;
use App\Models\IncomeCategory;
use App\Models\QrCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonationService
{
    public function createPublicDonation(array $data, string $mosqueId): Donation
    {
        return DB::transaction(function () use ($data, $mosqueId) {
            $verificationCode = 'DON-' . strtoupper(Str::random(12));

            $donation = Donation::create([
                'mosque_id' => $mosqueId,
                'campaign_id' => $data['campaign_id'] ?? null,
                'donor_name' => $data['donor_name'] ?? 'Hamba Allah',
                'donor_phone' => $data['donor_phone'] ?? null,
                'donor_email' => $data['donor_email'] ?? null,
                'is_anonymous' => !empty($data['is_anonymous']),
                'amount' => $data['amount'],
                'doa_message' => $data['doa_message'] ?? null,
                'payment_method' => $data['payment_method'] ?? 'QRIS',
                'payment_channel' => $data['payment_channel'] ?? 'QRIS Digital',
                'status' => 'PENDING',
                'verification_code' => $verificationCode,
            ]);

            DonationPayment::create([
                'donation_id' => $donation->id,
                'payment_gateway' => 'QRIS_PAYMENT',
                'transaction_ref' => 'PAY-' . strtoupper(Str::random(10)),
                'amount' => $donation->amount,
                'paid_at' => now(),
            ]);

            return $donation;
        });
    }

    public function verifyDonation(Donation $donation, string $verifierId): Donation
    {
        return DB::transaction(function () use ($donation, $verifierId) {
            $donation->update([
                'status' => 'VERIFIED',
                'verified_at' => now(),
                'paid_at' => $donation->paid_at ?? now(),
                'verified_by_id' => $verifierId,
            ]);

            if ($donation->campaign_id) {
                $campaign = DonationCampaign::find($donation->campaign_id);
                if ($campaign) {
                    $campaign->increment('collected_amount', (float) $donation->amount);
                    $campaign->increment('donor_count');
                }
            }

            // Sync with Income in Kas
            $incomeCat = IncomeCategory::where('mosque_id', $donation->mosque_id)->first();
            FinancialTransaction::create([
                'mosque_id' => $donation->mosque_id,
                'transaction_type' => TransactionType::INCOME,
                'income_category_id' => $incomeCat?->id,
                'donation_id' => $donation->id,
                'amount' => $donation->amount,
                'transaction_date' => now()->toDateString(),
                'reference_number' => 'KAS-DON-' . strtoupper(substr($donation->verification_code, -6)),
                'description' => 'Penerimaan Donasi ' . ($donation->campaign?->title ?? 'Infaq Masjid') . ' a.n ' . $donation->display_name,
                'recipient_or_payer' => $donation->display_name,
                'payment_channel' => $donation->payment_method,
                'recorded_by_id' => $verifierId,
                'verified_by_id' => $verifierId,
                'status' => 'APPROVED',
            ]);

            // Create or update digital document
            Document::updateOrCreate(
                ['verification_code' => $donation->verification_code],
                [
                    'mosque_id' => $donation->mosque_id,
                    'document_number' => 'KWIT/' . date('Y/m') . '/' . strtoupper(substr(uniqid(), -5)),
                    'document_type' => DocumentType::DONATION_RECEIPT,
                    'title' => 'e-Kwitansi Donasi Resmi ' . ($donation->campaign?->title ?? 'Infaq Masjid'),
                    'issuer_id' => $verifierId,
                    'issued_at' => now(),
                    'payload_snapshot' => [
                        'donor_name' => $donation->display_name,
                        'amount' => (float) $donation->amount,
                        'campaign_title' => $donation->campaign?->title ?? 'Infaq Operasional',
                        'mosque_name' => $donation->mosque?->name ?? 'Masjid Indonesia',
                        'status' => 'VERIFIED',
                        'date' => now()->translatedFormat('d F Y H:i'),
                    ],
                ]
            );

            // Register QR Code
            QrCode::firstOrCreate(
                ['token' => $donation->verification_code],
                [
                    'mosque_id' => $donation->mosque_id,
                    'code_type' => 'DOCUMENT_VERIFY',
                    'target_url' => '/verify/' . $donation->verification_code,
                ]
            );

            return $donation->fresh(['campaign', 'verifiedBy']);
        });
    }
}
