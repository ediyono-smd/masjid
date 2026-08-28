<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Congregation extends Model
{
    use HasFactory, HasUuid, BelongsToMosque, SoftDeletes;

    protected $fillable = [
        'id',
        'mosque_id',
        'user_id',
        'name',
        'nik_masked',
        'phone',
        'email',
        'gender',
        'address',
        'rt_rw',
        'occupation',
        'blood_type',
        'special_skills',
        'is_head_of_family',
        'family_members_count',
        'is_mustahiq',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_head_of_family' => 'boolean',
            'is_mustahiq' => 'boolean',
            'family_members_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
