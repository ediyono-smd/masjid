<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\FinancialTransaction;
use App\Models\IncomeCategory;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    public function getBalanceSummary(?string $mosqueId = null): array
    {
        $query = FinancialTransaction::query()->where('status', 'APPROVED');
        if ($mosqueId) {
            $query->where('mosque_id', $mosqueId);
        }

        $totalIncome = (float) (clone $query)->where('transaction_type', TransactionType::INCOME)->sum('amount');
        $totalExpense = (float) (clone $query)->where('transaction_type', TransactionType::EXPENSE)->sum('amount');
        $currentBalance = $totalIncome - $totalExpense;

        $thisMonthIncome = (float) (clone $query)->where('transaction_type', TransactionType::INCOME)
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->sum('amount');

        $thisMonthExpense = (float) (clone $query)->where('transaction_type', TransactionType::EXPENSE)
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->sum('amount');

        return [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'current_balance' => $currentBalance,
            'this_month_income' => $thisMonthIncome,
            'this_month_expense' => $thisMonthExpense,
            'this_month_net' => $thisMonthIncome - $thisMonthExpense,
        ];
    }

    public function getMonthlyFlowChartData(?string $mosqueId = null, int $months = 6): array
    {
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->translatedFormat('M Y');

            $inc = (float) FinancialTransaction::query()
                ->when($mosqueId, fn($q) => $q->where('mosque_id', $mosqueId))
                ->where('status', 'APPROVED')
                ->where('transaction_type', TransactionType::INCOME)
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');

            $exp = (float) FinancialTransaction::query()
                ->when($mosqueId, fn($q) => $q->where('mosque_id', $mosqueId))
                ->where('status', 'APPROVED')
                ->where('transaction_type', TransactionType::EXPENSE)
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');

            $incomeData[] = $inc;
            $expenseData[] = $exp;
        }

        return [
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }

    public function recordTransaction(array $data, string $userId, ?string $mosqueId = null): FinancialTransaction
    {
        $data['recorded_by_id'] = $userId;
        if ($mosqueId && empty($data['mosque_id'])) {
            $data['mosque_id'] = $mosqueId;
        }

        if (empty($data['reference_number'])) {
            $prefix = $data['transaction_type'] === TransactionType::INCOME->value ? 'KAS-IN' : 'KAS-OUT';
            $data['reference_number'] = $prefix . '-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        }

        return FinancialTransaction::create($data);
    }
}
