<?php

namespace App\Services;

use App\Models\FinancialTransaction;
use App\Models\Mosque;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfReportService
{
    public function generateFinancialReport(Mosque $mosque, int $month, int $year): \Barryvdh\DomPDF\PDF
    {
        $transactions = FinancialTransaction::where('mosque_id', $mosque->id)
            ->where('status', 'APPROVED')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->with(['incomeCategory', 'expenseCategory', 'recordedBy'])
            ->orderBy('transaction_date')
            ->get();

        $totalIncome = $transactions->where('transaction_type.value', 'INCOME')->sum('amount');
        $totalExpense = $transactions->where('transaction_type.value', 'EXPENSE')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        $pdf = Pdf::loadView('reports.financial_pdf', [
            'mosque' => $mosque,
            'month' => $month,
            'year' => $year,
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $netBalance,
            'generatedAt' => now()->translatedFormat('d F Y H:i WIB'),
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf;
    }

    public function generateDonationReceiptPdf(array $receiptData): \Barryvdh\DomPDF\PDF
    {
        $pdf = Pdf::loadView('reports.receipt_pdf', [
            'data' => $receiptData,
            'generatedAt' => now()->translatedFormat('d F Y H:i WIB'),
        ]);

        $pdf->setPaper('A5', 'landscape');

        return $pdf;
    }
}
