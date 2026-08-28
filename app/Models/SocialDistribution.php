<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialDistribution extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'id',
        'program_id',
        'recipient_id',
        'distribution_date',
        'package_description',
        'nominal_value',
        'proof_photo_url',
        'distributed_by_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'distribution_date' => 'date',
            'nominal_value' => 'decimal:2',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(SocialProgram::class, 'program_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(SocialRecipient::class, 'recipient_id');
    }

    public function distributedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'distributed_by_id');
    }
}
