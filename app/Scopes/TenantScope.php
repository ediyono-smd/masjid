<?php

namespace App\Scopes;

use App\Services\TenantManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\App;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        /** @var TenantManager $tenantManager */
        $tenantManager = App::make(TenantManager::class);

        // Super Admin bypasses tenant scoping unless an explicit mosque context is requested
        if ($tenantManager->isSuperAdmin()) {
            return;
        }

        $mosqueId = $tenantManager->getMosqueId();

        if ($mosqueId) {
            $builder->where($model->getTable() . '.mosque_id', $mosqueId);
        }
    }
}
