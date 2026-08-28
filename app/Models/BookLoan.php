<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookLoan extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'id',
        'book_id',
        'congregation_id',
        'borrower_name',
        'borrower_phone',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'notes',
        'processed_by_id',
    ];

    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'due_date' => 'date',
            'return_date' => 'date',
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function congregation(): BelongsTo
    {
        return $this->belongsTo(Congregation::class, 'congregation_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by_id');
    }
}
