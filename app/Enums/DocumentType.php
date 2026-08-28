<?php

namespace App\Enums;

enum DocumentType: string
{
    case DONATION_RECEIPT = 'DONATION_RECEIPT';
    case ZAKAT_RECEIPT = 'ZAKAT_RECEIPT';
    case WAQF_RECEIPT = 'WAQF_RECEIPT';
    case ACTIVITY_LETTER = 'ACTIVITY_LETTER';
    case RECOMMENDATION_LETTER = 'RECOMMENDATION_LETTER';
    case FINANCIAL_REPORT = 'FINANCIAL_REPORT';

    public function label(): string
    {
        return match($this) {
            self::DONATION_RECEIPT => 'e-Kwitansi Infaq / Donasi Resmi',
            self::ZAKAT_RECEIPT => 'Bukti Setor ZISWAF Resmi',
            self::WAQF_RECEIPT => 'Tanda Terima Wakaf',
            self::ACTIVITY_LETTER => 'Surat Keterangan Kegiatan Masjid',
            self::RECOMMENDATION_LETTER => 'Surat Rekomendasi Takmir Masjid',
            self::FINANCIAL_REPORT => 'Laporan Akuntabilitas Kas Masjid',
        };
    }
}
