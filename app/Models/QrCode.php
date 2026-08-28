<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QrCode extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'code_type',
        'target_url',
        'token',
        'scan_count',
        'last_scanned_at',
    ];

    protected function casts(): array
    {
        return [
            'scan_count' => 'integer',
            'last_scanned_at' => 'datetime',
        ];
    }

    public function scans(): HasMany
    {
        return $this->hasMany(QrScan::class, 'qr_code_id');
    }
}
