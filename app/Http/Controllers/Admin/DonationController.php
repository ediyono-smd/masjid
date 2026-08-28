<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationCampaign;
use App\Services\DonationService;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function __construct(
        protected TenantManager $tenantManager,
        protected DonationService $donationService
    ) {}

    public function index(Request $request): View
    {
        $mosque = $this->tenantManager->getMosque();
        $status = $request->query('status');

        $donations = Donation::where('mosque_id', $mosque->id)
            ->when($status, fn($q) => $q->where('status', $status))
            ->with(['campaign', 'verifiedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.donations.index', compact('mosque', 'donations', 'status'));
    }

    public function verify(Donation $donation): RedirectResponse
    {
        $this->donationService->verifyDonation($donation, Auth::id());

        return back()->with('success', 'Donasi berhasil diverifikasi dan e-Kwitansi resmi telah diterbitkan!');
    }
}
