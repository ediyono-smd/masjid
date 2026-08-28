<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Models\MaintenanceRecord;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __construct(protected TenantManager $tenantManager) {}

    public function index(): View
    {
        $mosque = $this->tenantManager->getMosque();

        $inventories = Inventory::where('mosque_id', $mosque->id)
            ->with(['category', 'maintenanceRecords'])
            ->orderBy('name')
            ->paginate(15);

        $categories = InventoryCategory::where('mosque_id', $mosque->id)->get();

        return view('admin.inventory.index', compact('mosque', 'inventories', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'category_id' => 'nullable|exists:inventory_categories,id',
            'item_code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:30',
            'acquisition_date' => 'nullable|date',
            'acquisition_source' => 'required|string',
            'acquisition_cost' => 'nullable|numeric|min:0',
            'room_location' => 'nullable|string|max:100',
            'condition' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $validated['mosque_id'] = $mosque->id;

        Inventory::create($validated);

        return back()->with('success', 'Barang inventaris baru berhasil ditambahkan.');
    }

    public function storeMaintenance(Request $request, Inventory $inventory): RedirectResponse
    {
        $validated = $request->validate([
            'maintenance_date' => 'required|date',
            'issue_description' => 'required|string',
            'action_taken' => 'nullable|string',
            'vendor_name' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'required|string',
        ]);

        $validated['inventory_id'] = $inventory->id;
        $validated['recorded_by_id'] = Auth::id();

        MaintenanceRecord::create($validated);

        return back()->with('success', 'Catatan maintenance / perbaikan berhasil disimpan.');
    }
}
