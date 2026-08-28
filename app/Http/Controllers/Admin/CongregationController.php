<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Congregation;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CongregationController extends Controller
{
    public function __construct(protected TenantManager $tenantManager) {}

    public function index(): View
    {
        $mosque = $this->tenantManager->getMosque();
        $congregations = Congregation::where('mosque_id', $mosque->id)
            ->orderBy('name')
            ->paginate(15);

        return view('admin.congregations.index', compact('mosque', 'congregations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'gender' => 'required|in:L,P',
            'address' => 'nullable|string',
            'rt_rw' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:100',
            'is_head_of_family' => 'nullable|boolean',
            'family_members_count' => 'required|integer|min:1',
            'is_mustahiq' => 'nullable|boolean',
        ]);

        $validated['mosque_id'] = $mosque->id;
        $validated['is_head_of_family'] = $request->boolean('is_head_of_family');
        $validated['is_mustahiq'] = $request->boolean('is_mustahiq');

        Congregation::create($validated);

        return back()->with('success', 'Data jamaah berhasil ditambahkan.');
    }
}
