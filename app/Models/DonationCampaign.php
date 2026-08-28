<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DonationCampaign extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'title',
        'slug',
        'category',
        'target_amount',
        'collected_amount',
        'donor_count',
        'start_date',
        'end_date',
        'cover_image_url',
        'description',
        'is_featured',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'collected_amount' => 'decimal:2',
            'donor_count' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'campaign_id');
    }

    public function getProgressPercentageAttribute(): float
    {
        if (!$this->target_amount || (float) $this->target_amount <= 0) {
            return 100.0;
        }

        return min(100.0, round(((float) $this->collected_amount / (float) $this->target_amount) * 100, 1));
    }
}
