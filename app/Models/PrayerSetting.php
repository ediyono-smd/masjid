<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrayerSetting extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'calculation_method',
        'fajr_angle',
        'isha_angle',
        'fajr_offset_minutes',
        'dhuhr_offset_minutes',
        'asr_offset_minutes',
        'maghrib_offset_minutes',
        'isha_offset_minutes',
        'iqamah_delay_minutes',
    ];

    protected function casts(): array
    {
        return [
            'fajr_angle' => 'decimal:2',
            'isha_angle' => 'decimal:2',
            'fajr_offset_minutes' => 'integer',
            'dhuhr_offset_minutes' => 'integer',
            'asr_offset_minutes' => 'integer',
            'maghrib_offset_minutes' => 'integer',
            'isha_offset_minutes' => 'integer',
            'iqamah_delay_minutes' => 'array',
        ];
    }
}
