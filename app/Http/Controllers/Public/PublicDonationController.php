<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DonationCampaign;
use App\Models\Mosque;
use App\Services\DonationService;
use App\Services\MosqueService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicDonationController extends Controller
{
    public function __construct(
        protected MosqueService $mosqueService,
        protected DonationService $donationService
    ) {}

    public function index(string $slug): View
    {
        $mosque = $this->mosqueService->findBySlug($slug);
        $campaigns = DonationCampaign::where('mosque_id', $mosque->id)
            ->where('status', 'ACTIVE')
            ->paginate(9);

        return view('public.donations.index', compact('mosque', 'campaigns'));
    }

    public function show(string $slug, string $campaignSlug): View
    {
        $mosque = $this->mosqueService->findBySlug($slug);
        $campaign = DonationCampaign::where('mosque_id', $mosque->id)
            ->where('slug', $campaignSlug)
            ->with(['donations' => fn($q) => $q->where('status', 'VERIFIED')->orderBy('created_at', 'desc')->take(10)])
            ->firstOrFail();

        return view('public.donations.show', compact('mosque', 'campaign'));
    }

    public function store(Request $request, string $slug): RedirectResponse
    {
        $mosque = $this->mosqueService->findBySlug($slug);

        $validated = $request->validate([
            'campaign_id' => 'nullable|exists:donation_campaigns,id',
            'donor_name' => 'required_without:is_anonymous|nullable|string|max:255',
            'donor_phone' => 'nullable|string|max:30',
            'donor_email' => 'nullable|email|max:255',
            'is_anonymous' => 'nullable|boolean',
            'amount' => 'required|numeric|min:10000',
            'doa_message' => 'nullable|string|max:500',
            'payment_method' => 'required|string',
        ]);

        $donation = $this->donationService->createPublicDonation($validated, $mosque->id);

        return redirect()->route('public.donations.success', [
            'slug' => $mosque->slug,
            'code' => $donation->verification_code,
        ])->with('success', 'Niat donasi Anda telah tercatat! Silakan lakukan pembayaran sesuai petunjuk.');
    }

    public function success(string $slug, string $code): View
    {
        $mosque = $this->mosqueService->findBySlug($slug);
        return view('public.donations.success', compact('mosque', 'code'));
    }
}
