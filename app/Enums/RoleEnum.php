<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = 'SUPER_ADMIN';
    case MOSQUE_ADMIN = 'MOSQUE_ADMIN';
    case CHAIRMAN = 'CHAIRMAN';
    case SECRETARY = 'SECRETARY';
    case TREASURER = 'TREASURER';
    case OPERATOR = 'OPERATOR';
    case IMAM = 'IMAM';
    case KHATIB = 'KHATIB';
    case MUADZIN = 'MUADZIN';
    case JAMAAH = 'JAMAAH';
    case DONOR = 'DONOR';
    case VOLUNTEER = 'VOLUNTEER';

    public function label(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'Super Admin Platform',
            self::MOSQUE_ADMIN => 'Admin Masjid',
            self::CHAIRMAN => 'Ketua Takmir',
            self::SECRETARY => 'Sekretaris',
            self::TREASURER => 'Bendahara',
            self::OPERATOR => 'Operator & Multimedia',
            self::IMAM => 'Imam Rawatib',
            self::KHATIB => 'Khatib Jumat',
            self::MUADZIN => 'Muadzin',
            self::JAMAAH => 'Jamaah',
            self::DONOR => 'Donatur',
            self::VOLUNTEER => 'Relawan',
        };
    }
}
