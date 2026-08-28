<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'category_id',
        'book_code',
        'title',
        'author',
        'publisher',
        'year_published',
        'language',
        'copies_total',
        'copies_available',
        'shelf_location',
        'cover_url',
    ];

    protected function casts(): array
    {
        return [
            'year_published' => 'integer',
            'copies_total' => 'integer',
            'copies_available' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(BookLoan::class, 'book_id');
    }
}
