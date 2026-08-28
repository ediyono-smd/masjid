<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'event_category_id',
        'title',
        'slug',
        'speaker_name',
        'speaker_title',
        'start_datetime',
        'end_datetime',
        'location',
        'livestream_url',
        'description',
        'banner_url',
        'max_participants',
        'registered_participants',
        'is_registration_open',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
            'is_registration_open' => 'boolean',
            'max_participants' => 'integer',
            'registered_participants' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class, 'event_id');
    }
}
