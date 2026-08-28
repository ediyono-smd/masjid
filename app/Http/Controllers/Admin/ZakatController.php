<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\QrCode;
use App\Models\WaqfDonation;
use App\Models\ZakatPayment;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ZakatController extends Controller
{
    public function __construct(protected TenantManager $tenantManager) {}

    public function index(): View
    {
        $mosque = $this->tenantManager->getMosque();

        $zakatPayments = ZakatPayment::where('mosque_id', $mosque->id)
            ->with('receivedBy')
            ->orderBy('payment_date', 'desc')
            ->paginate(15);

        $waqfDonations = WaqfDonation::where('mosque_id', $mosque->id)
            ->with('receivedBy')
            ->orderBy('waqf_date', 'desc')
            ->get();

        $zakatFitrahTotalBeras = (float) ZakatPayment::where('mosque_id', $mosque->id)
            ->where('zakat_type', 'FITRAH_BERAS')
            ->sum('quantity_kg');

        $zakatFitrahTotalUang = (float) ZakatPayment::where('mosque_id', $mosque->id)
            ->where('zakat_type', 'FITRAH_UANG')
            ->sum('amount_rupiah');

        $zakatMaalTotal = (float) ZakatPayment::where('mosque_id', $mosque->id)
            ->where('zakat_type', 'MAAL')
            ->sum('amount_rupiah');

        return view('admin.zakat.index', compact(
            'mosque',
            'zakatPayments',
            'waqfDonations',
            'zakatFitrahTotalBeras',
            'zakatFitrahTotalUang',
            'zakatMaalTotal'
        ));
    }

    public function storeZakat(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'muzakki_name' => 'required|string|max:255',
            'muzakki_phone' => 'nullable|string|max:30',
            'zakat_type' => 'required|string',
            'quantity_kg' => 'nullable|numeric|min:0',
            'amount_rupiah' => 'nullable|numeric|min:0',
            'souls_count' => 'required|integer|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $verifCode = 'ZKT-' . strtoupper(Str::random(12));
        $validated['mosque_id'] = $mosque->id;
        $validated['verification_code'] = $verifCode;
        $validated['received_by_id'] = Auth::id();

        $zakat = ZakatPayment::create($validated);

        // Issue official receipt
        Document::create([
            'mosque_id' => $mosque->id,
            'document_number' => 'ZIS/' . date('Y/m') . '/' . strtoupper(substr(uniqid(), -4)),
            'document_type' => DocumentType::ZAKAT_RECEIPT,
            'title' => 'Bukti Setor ' . $zakat->zakat_type,
            'issuer_id' => Auth::id(),
            'verification_code' => $verifCode,
            'issued_at' => now(),
            'payload_snapshot' => [
                'muzakki_name' => $zakat->muzakki_name,
                'zakat_type' => $zakat->zakat_type,
                'amount' => (float) $zakat->amount_rupiah,
                'kg' => (float) $zakat->quantity_kg,
                'souls' => $zakat->souls_count,
                'mosque_name' => $mosque->name,
                'status' => 'VERIFIED',
            ],
        ]);

        QrCode::create([
            'mosque_id' => $mosque->id,
            'code_type' => 'DOCUMENT_VERIFY',
            'target_url' => '/verify/' . $verifCode,
            'token' => $verifCode,
        ]);

        return back()->with('success', 'Pembayaran Zakat berhasil dicatat dan bukti setor ber-QR resmi telah terbit.');
    }
}
