<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerActivity extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'id',
        'volunteer_id',
        'activity_name',
        'activity_date',
        'hours_spent',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'hours_spent' => 'integer',
        ];
    }

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class, 'volunteer_id');
    }
}
