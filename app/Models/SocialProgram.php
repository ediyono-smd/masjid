<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialProgram extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'name',
        'slug',
        'category',
        'description',
        'budget',
        'realized_amount',
        'target_recipients_count',
        'actual_recipients_count',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
            'realized_amount' => 'decimal:2',
            'target_recipients_count' => 'integer',
            'actual_recipients_count' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(SocialDistribution::class, 'program_id');
    }
}
