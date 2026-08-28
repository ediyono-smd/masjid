<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZakatPayment extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'muzakki_name',
        'muzakki_phone',
        'muzakki_email',
        'zakat_type',
        'quantity_kg',
        'amount_rupiah',
        'souls_count',
        'payment_date',
        'payment_method',
        'verification_code',
        'received_by_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity_kg' => 'decimal:2',
            'amount_rupiah' => 'decimal:2',
            'souls_count' => 'integer',
            'payment_date' => 'date',
        ];
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by_id');
    }
}
