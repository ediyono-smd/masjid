<?php

namespace App\Models;

use App\Enums\TransactionType;
use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialTransaction extends Model
{
    use HasFactory, HasUuid, BelongsToMosque, SoftDeletes;

    protected $fillable = [
        'id',
        'mosque_id',
        'transaction_type',
        'income_category_id',
        'expense_category_id',
        'donation_id',
        'amount',
        'transaction_date',
        'reference_number',
        'description',
        'recipient_or_payer',
        'payment_channel',
        'proof_attachment_url',
        'recorded_by_id',
        'verified_by_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'transaction_type' => TransactionType::class,
            'amount' => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }

    public function incomeCategory(): BelongsTo
    {
        return $this->belongsTo(IncomeCategory::class, 'income_category_id');
    }

    public function expenseCategory(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class, 'donation_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_id');
    }
}
