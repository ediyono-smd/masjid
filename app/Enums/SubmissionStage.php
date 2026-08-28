<?php

namespace App\Enums;

enum SubmissionStage: string
{
    case DRAFT = 'DRAFT';
    case SUBMITTED = 'SUBMITTED';
    case OPERATOR_REVIEW = 'OPERATOR_REVIEW';
    case TREASURER_REVIEW = 'TREASURER_REVIEW';
    case CHAIRMAN_REVIEW = 'CHAIRMAN_REVIEW';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
    case COMPLETED = 'COMPLETED';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft Konsep',
            self::SUBMITTED => 'Diajukan / Menunggu Review',
            self::OPERATOR_REVIEW => 'Verifikasi Operator / Staf',
            self::TREASURER_REVIEW => 'Pemeriksaan Bendahara',
            self::CHAIRMAN_REVIEW => 'Persetujuan Ketua Takmir',
            self::APPROVED => 'Disetujui',
            self::REJECTED => 'Ditolak',
            self::COMPLETED => 'Selesai / Terlaksana',
        };
    }
}
