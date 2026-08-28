<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public function log(string $eventType, ?Model $model = null, ?array $oldValues = null, ?array $newValues = null, ?string $mosqueId = null): void
    {
        $user = Auth::user();
        $request = request();

        AuditLog::create([
            'mosque_id' => $mosqueId ?? $user?->mosque_id,
            'user_id' => $user?->id,
            'event_type' => $eventType,
            'auditable_type' => $model ? get_class($model) : null,
            'auditable_id' => $model ? $model->getKey() : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? substr($request->userAgent() ?? '', 0, 255) : null,
        ]);
    }
}
