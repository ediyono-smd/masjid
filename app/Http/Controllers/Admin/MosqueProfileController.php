<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MosqueFacility;
use App\Services\MosqueService;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MosqueProfileController extends Controller
{
    public function __construct(
        protected TenantManager $tenantManager,
        protected MosqueService $mosqueService
    ) {}

    public function edit(): View
    {
        $mosque = $this->tenantManager->getMosque();
        $facilities = MosqueFacility::where('mosque_id', $mosque->id)->get();

        return view('admin.profile.edit', compact('mosque', 'facilities'));
    }

    public function update(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'kemenag_id' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address_line' => 'required|string',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'village' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'history' => 'nullable|string',
            'vision' => 'nullable|string',
            'capacity' => 'nullable|integer',
            'land_area_sqm' => 'nullable|numeric',
            'building_area_sqm' => 'nullable|numeric',
            'legal_status' => 'nullable|string|max:100',
        ]);

        $this->mosqueService->updateProfile($mosque, $validated);

        return back()->with('success', 'Profil dan informasi masjid berhasil diperbarui.');
    }

    public function storeFacility(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $this->mosqueService->addFacility($mosque, $validated);

        return back()->with('success', 'Fasilitas masjid berhasil ditambahkan.');
    }
}
