<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Congregation;
use App\Models\Donation;
use App\Models\Event;
use App\Models\FinancialTransaction;
use App\Models\Submission;
use App\Services\FinanceService;
use App\Services\TenantManager;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected TenantManager $tenantManager,
        protected FinanceService $financeService
    ) {}

    public function index(): View
    {
        $mosque = $this->tenantManager->getMosque();
        if (!$mosque) {
            abort(404, 'Masjid aktif tidak ditemukan.');
        }

        $financeSummary = $this->financeService->getBalanceSummary($mosque->id);
        $chartData = $this->financeService->getMonthlyFlowChartData($mosque->id, 6);

        $stats = [
            'current_balance' => $financeSummary['current_balance'],
            'this_month_income' => $financeSummary['this_month_income'],
            'this_month_expense' => $financeSummary['this_month_expense'],
            'total_jamaah' => Congregation::where('mosque_id', $mosque->id)->count(),
            'active_campaigns_count' => $mosque->donationCampaigns()->where('status', 'ACTIVE')->count(),
            'upcoming_events_count' => Event::where('mosque_id', $mosque->id)->where('status', 'UPCOMING')->count(),
        ];

        $recentTransactions = FinancialTransaction::where('mosque_id', $mosque->id)
            ->where('status', 'APPROVED')
            ->with(['incomeCategory', 'expenseCategory', 'recordedBy'])
            ->orderBy('transaction_date', 'desc')
            ->take(5)
            ->get();

        $pendingSubmissions = Submission::where('mosque_id', $mosque->id)
            ->whereNotIn('current_stage', ['APPROVED', 'REJECTED', 'COMPLETED'])
            ->with('applicant')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentDonations = Donation::where('mosque_id', $mosque->id)
            ->with('campaign')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'mosque',
            'stats',
            'chartData',
            'recentTransactions',
            'pendingSubmissions',
            'recentDonations'
        ));
    }
}
