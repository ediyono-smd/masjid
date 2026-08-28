<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaqfDonation extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'wakif_name',
        'wakif_phone',
        'waqf_type',
        'nominal_value',
        'asset_description',
        'pledge_document_url',
        'verification_code',
        'waqf_date',
        'received_by_id',
    ];

    protected function casts(): array
    {
        return [
            'nominal_value' => 'decimal:2',
            'waqf_date' => 'date',
        ];
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by_id');
    }
}
