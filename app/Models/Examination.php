<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Examination extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'examination_type',
        'title',
        'examination_date',
        'examiner_id',
        'checklist_items',
        'overall_result',
        'recommendations',
    ];

    protected function casts(): array
    {
        return [
            'examination_date' => 'date',
            'checklist_items' => 'array',
        ];
    }

    public function examiner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'examiner_id');
    }
}
