<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MosqueProfile extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'history',
        'vision',
        'mission',
        'capacity',
        'land_area_sqm',
        'building_area_sqm',
        'legal_status',
        'social_media',
    ];

    protected function casts(): array
    {
        return [
            'mission' => 'array',
            'social_media' => 'array',
            'capacity' => 'integer',
            'land_area_sqm' => 'decimal:2',
            'building_area_sqm' => 'decimal:2',
        ];
    }
}
