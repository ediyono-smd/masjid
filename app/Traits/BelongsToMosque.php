<?php

namespace App\Traits;

use App\Models\Mosque;
use App\Scopes\TenantScope;
use App\Services\TenantManager;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;

trait BelongsToMosque
{
    protected static function bootBelongsToMosque(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            if (empty($model->mosque_id)) {
                /** @var TenantManager $tenantManager */
                $tenantManager = App::make(TenantManager::class);
                $mosqueId = $tenantManager->getMosqueId();
                if ($mosqueId) {
                    $model->mosque_id = $mosqueId;
                }
            }
        });
    }

    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class, 'mosque_id');
    }
}
