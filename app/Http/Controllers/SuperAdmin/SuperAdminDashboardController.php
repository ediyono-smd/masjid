<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\MosqueStatus;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Donation;
use App\Models\Mosque;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\View\View;

class SuperAdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_mosques' => Mosque::count(),
            'verified_mosques' => Mosque::where('status', MosqueStatus::VERIFIED)->count(),
            'pending_mosques' => Mosque::where('status', MosqueStatus::PENDING)->count(),
            'total_users' => User::count(),
            'total_donations' => (float) Donation::where('status', 'VERIFIED')->sum('amount'),
            'total_provinces' => Mosque::distinct('province')->count('province'),
        ];

        $recentMosques = Mosque::with('profile')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentLogs = AuditLog::with(['user', 'mosque'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('superadmin.dashboard', compact('stats', 'recentMosques', 'recentLogs'));
    }
}
