<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MosqueStaff;
use App\Models\User;
use App\Services\MosqueService;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MosqueStaffController extends Controller
{
    public function __construct(
        protected TenantManager $tenantManager,
        protected MosqueService $mosqueService
    ) {}

    public function index(): View
    {
        $mosque = $this->tenantManager->getMosque();
        $staffList = MosqueStaff::where('mosque_id', $mosque->id)
            ->with('user')
            ->orderBy('period_start', 'desc')
            ->orderBy('position')
            ->paginate(15);

        return view('admin.staff.index', compact('mosque', 'staffList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:100',
            'department' => 'nullable|string|max:100',
            'period_start' => 'required|integer|min:2000|max:2100',
            'period_end' => 'required|integer|min:2000|max:2100',
            'phone_number' => 'nullable|string|max:30',
        ]);

        $this->mosqueService->addStaff($mosque, $validated);

        return back()->with('success', 'Pengurus takmir berhasil didaftarkan.');
    }

    public function destroy(MosqueStaff $staff): RedirectResponse
    {
        $staff->delete();
        return back()->with('success', 'Data pengurus berhasil dihapus.');
    }
}
