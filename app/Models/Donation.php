<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Donation extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'campaign_id',
        'donor_id',
        'donor_name',
        'donor_phone',
        'donor_email',
        'is_anonymous',
        'amount',
        'doa_message',
        'payment_method',
        'payment_channel',
        'status',
        'verification_code',
        'paid_at',
        'verified_at',
        'verified_by_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_anonymous' => 'boolean',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(DonationCampaign::class, 'campaign_id');
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class, 'donor_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(DonationPayment::class, 'donation_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_id');
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Hamba Allah';
        }

        return $this->donor_name;
    }
}
