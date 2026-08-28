<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\MosqueStatus;
use App\Http\Controllers\Controller;
use App\Models\Mosque;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MosqueManagementController extends Controller
{
    public function __construct(protected AuditLogService $auditService) {}

    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('q');

        $mosques = Mosque::query()
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, fn($q) => $q->where('name', 'ilike', "%{$search}%")->orWhere('city', 'ilike', "%{$search}%"))
            ->with(['profile', 'subscription.plan'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('superadmin.mosques.index', compact('mosques', 'status', 'search'));
    }

    public function verify(Mosque $mosque): RedirectResponse
    {
        $mosque->update([
            'status' => MosqueStatus::VERIFIED,
            'verified_at' => now(),
        ]);

        $this->auditService->log('VERIFY_MOSQUE', $mosque, null, ['status' => 'VERIFIED']);

        return back()->with('success', "Masjid {$mosque->name} berhasil diverifikasi resmi.");
    }

    public function suspend(Mosque $mosque): RedirectResponse
    {
        $mosque->update([
            'status' => MosqueStatus::SUSPENDED,
        ]);

        $this->auditService->log('SUSPEND_MOSQUE', $mosque, null, ['status' => 'SUSPENDED']);

        return back()->with('warning', "Masjid {$mosque->name} telah ditangguhkan.");
    }

    public function switchContext(Mosque $mosque): RedirectResponse
    {
        session(['active_mosque_id' => $mosque->id]);
        return redirect()->route('admin.dashboard')->with('info', "Beralih ke konteks takmir: {$mosque->name}");
    }
}
