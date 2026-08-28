<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationCampaign;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DonationCampaignController extends Controller
{
    public function __construct(protected TenantManager $tenantManager) {}

    public function index(): View
    {
        $mosque = $this->tenantManager->getMosque();
        $campaigns = DonationCampaign::where('mosque_id', $mosque->id)
            ->withCount('donations')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.donations.campaigns', compact('mosque', 'campaigns'));
    }

    public function store(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'target_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'required|string',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['mosque_id'] = $mosque->id;
        $validated['slug'] = Str::slug($validated['title']) . '-' . strtolower(Str::random(4));
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['status'] = 'ACTIVE';

        DonationCampaign::create($validated);

        return back()->with('success', 'Program penggalangan donasi berhasil dibuat.');
    }
}
