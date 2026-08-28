<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use App\Models\FinancialTransaction;
use App\Models\IncomeCategory;
use App\Services\FinanceService;
use App\Services\PdfReportService;
use App\Services\TenantManager;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FinanceController extends Controller
{
    public function __construct(
        protected TenantManager $tenantManager,
        protected FinanceService $financeService,
        protected PdfReportService $pdfService
    ) {}

    public function index(Request $request): View
    {
        $mosque = $this->tenantManager->getMosque();
        $summary = $this->financeService->getBalanceSummary($mosque->id);

        $type = $request->query('type');
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);

        $transactions = FinancialTransaction::where('mosque_id', $mosque->id)
            ->when($type, fn($q) => $q->where('transaction_type', $type))
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->with(['incomeCategory', 'expenseCategory', 'recordedBy'])
            ->orderBy('transaction_date', 'desc')
            ->paginate(15);

        $incomeCategories = IncomeCategory::where('mosque_id', $mosque->id)->get();
        $expenseCategories = ExpenseCategory::where('mosque_id', $mosque->id)->get();

        return view('admin.finances.index', compact(
            'mosque',
            'summary',
            'transactions',
            'incomeCategories',
            'expenseCategories',
            'type',
            'month',
            'year'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'transaction_type' => 'required|string',
            'income_category_id' => 'nullable|required_if:transaction_type,INCOME|exists:income_categories,id',
            'expense_category_id' => 'nullable|required_if:transaction_type,EXPENSE|exists:expense_categories,id',
            'amount' => 'required|numeric|min:100',
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:500',
            'recipient_or_payer' => 'nullable|string|max:255',
            'payment_channel' => 'required|string',
        ]);

        $this->financeService->recordTransaction($validated, Auth::id(), $mosque->id);

        return back()->with('success', 'Transaksi kas berhasil dicatat ke buku kas.');
    }

    public function exportPdf(Request $request): Response
    {
        $mosque = $this->tenantManager->getMosque();
        $month = (int) $request->query('month', Carbon::now()->month);
        $year = (int) $request->query('year', Carbon::now()->year);

        $pdf = $this->pdfService->generateFinancialReport($mosque, $month, $year);

        return $pdf->download("Laporan_Kas_{$mosque->slug}_{$year}_{$month}.pdf");
    }
}
