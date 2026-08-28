<?php

namespace App\Enums;

enum MosqueType: string
{
    case RAYA = 'RAYA';
    case AGUNG = 'AGUNG';
    case BESAR = 'BESAR';
    case JAMI = 'JAMI';
    case MUSHOLLA = 'MUSHOLLA';

    public function label(): string
    {
        return match($this) {
            self::RAYA => 'Masjid Raya (Provinsi)',
            self::AGUNG => 'Masjid Agung (Kabupaten/Kota)',
            self::BESAR => 'Masjid Besar (Kecamatan)',
            self::JAMI => "Masjid Jami' (Kelurahan/Desa)",
            self::MUSHOLLA => 'Musholla / Langgar',
        };
    }
}
