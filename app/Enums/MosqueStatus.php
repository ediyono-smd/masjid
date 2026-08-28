<?php

namespace App\Enums;

enum MosqueStatus: string
{
    case PENDING = 'PENDING';
    case VERIFIED = 'VERIFIED';
    case SUSPENDED = 'SUSPENDED';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Menunggu Verifikasi',
            self::VERIFIED => 'Terverifikasi Resmi',
            self::SUSPENDED => 'Ditangguhkan',
        };
    }
}
