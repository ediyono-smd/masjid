<?php

namespace App\Enums;

enum SubmissionCategory: string
{
    case KEGIATAN = 'KEGIATAN';
    case DANA = 'DANA';
    case PEMBELIAN = 'PEMBELIAN';
    case SOSIAL = 'SOSIAL';
    case MAINTENANCE = 'MAINTENANCE';

    public function label(): string
    {
        return match($this) {
            self::KEGIATAN => 'Pengajuan Agenda / Kegiatan',
            self::DANA => 'Pengajuan Pencairan Anggaran',
            self::PEMBELIAN => 'Pengajuan Pengadaan Barang / Aset',
            self::SOSIAL => 'Pengajuan Bantuan Sosial / Mustahiq',
            self::MAINTENANCE => 'Pengajuan Perbaikan Sarana & Prasarana',
        };
    }
}
