<?php

namespace App\Models;

use App\Traits\BelongsToMosque;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory, HasUuid, BelongsToMosque;

    protected $fillable = [
        'id',
        'mosque_id',
        'category_id',
        'item_code',
        'name',
        'quantity',
        'unit',
        'acquisition_date',
        'acquisition_source',
        'acquisition_cost',
        'room_location',
        'condition',
        'notes',
        'photo_url',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'acquisition_date' => 'date',
            'acquisition_cost' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(MaintenanceRecord::class, 'inventory_id');
    }
}
