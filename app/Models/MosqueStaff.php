<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MosqueStaff extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $table = 'mosque_staff';

    protected $fillable = [
        'id',
        'mosque_id',
        'user_id',
        'name',
        'position',
        'department',
        'period_start',
        'period_end',
        'phone_number',
        'photo_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'integer',
            'period_end' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
