<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialRecipient extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'full_name',
        'nik_masked',
        'phone',
        'address',
        'asnaf_category',
        'dependents_count',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'dependents_count' => 'integer',
        ];
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(SocialDistribution::class, 'recipient_id');
    }
}
