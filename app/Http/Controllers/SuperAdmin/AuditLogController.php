<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $eventType = $request->query('event');

        $logs = AuditLog::query()
            ->when($eventType, fn($q) => $q->where('event_type', $eventType))
            ->with(['user', 'mosque'])
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('superadmin.audit.index', compact('logs', 'eventType'));
    }
}
