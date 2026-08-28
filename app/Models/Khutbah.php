<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Khutbah extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'title',
        'preacher_name',
        'delivery_date',
        'theme',
        'summary',
        'content',
        'audio_video_url',
        'pdf_attachment_url',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'delivery_date' => 'date',
            'is_published' => 'boolean',
        ];
    }
}
