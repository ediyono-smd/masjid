<?php

namespace App\Models;

use App\Enums\SubmissionCategory;
use App\Enums\SubmissionStage;
use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    use HasFactory, HasUuid, BelongsToMosque, SoftDeletes;

    protected $fillable = [
        'id',
        'mosque_id',
        'submission_number',
        'category',
        'title',
        'proposed_amount',
        'description',
        'attachment_url',
        'applicant_id',
        'current_stage',
    ];

    protected function casts(): array
    {
        return [
            'category' => SubmissionCategory::class,
            'current_stage' => SubmissionStage::class,
            'proposed_amount' => 'decimal:2',
        ];
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(SubmissionReview::class, 'submission_id')->orderBy('created_at', 'desc');
    }
}
