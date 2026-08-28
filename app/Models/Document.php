<?php

namespace App\Models;

use App\Enums\DocumentType;
use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'document_number',
        'document_type',
        'title',
        'file_path',
        'issuer_id',
        'verification_code',
        'issued_at',
        'expires_at',
        'is_revoked',
        'revocation_reason',
        'payload_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'document_type' => DocumentType::class,
            'issued_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_revoked' => 'boolean',
            'payload_snapshot' => 'array',
        ];
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issuer_id');
    }
}
