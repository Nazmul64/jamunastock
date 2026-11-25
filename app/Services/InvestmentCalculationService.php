<?php

namespace App\Services;

use App\Models\InvestmentSector;
use App\Models\Investment\EnterpriseInvestment;
use App\Models\Investment\InvestmentReceipt;
use App\Models\Investment\InvestmentLedger;
use App\Models\Investment\InvestmentProfitSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvestmentCalculationService
{
    /**
     * Generate unique Receipt Number with sector prefix (e.g. LIVE-RCP-2027-00001)
     */
    public static function generateReceiptNumber(InvestmentSector $sector): string
    {
        $prefix = $sector->receipt_prefix ?: ($sector->code ? $sector->code . '-RCP' : 'INV-RCP');
        $year = date('Y');
        $pattern = "{$prefix}-{$year}-%";

        $lastReceipt = DB::table('investment_receipts')
            ->where('receipt_number', 'LIKE', $pattern)
            ->orderByDesc('id')
            ->first();

        $seq = 1;
        if ($lastReceipt) {
            $parts = explode('-', $lastReceipt->receipt_number);
            $lastNum = (int) end($parts);
            $seq = $lastNum + 1;
        }

        return sprintf('%s-%s-%05d', $prefix, $year, $seq);
    }

    /**
     * Generate unique Investment Code
     */
    public static function generateInvestmentCode(InvestmentSector $sector): string
    {
        $year = date('Y');
        $count = DB::table('enterprise_investments')->whereYear('created_at', $year)->count() + 1;
        return sprintf('INV-%s-%06d', $year, $count);
    }

    /**
     * Generate unique Agreement Number
     */
    public static function generateAgreementNumber(InvestmentSector $sector): string
    {
        $year = date('Y');
        $code = $sector->code ?: 'GEN';
        $count = DB::table('enterprise_investments')->where('sector_id', $sector->id)->count() + 1;
        return sprintf('AGR-%s-%s-%04d', $year, $code, $count);
    }

    /**
     * Generate unique Customer Code (CUS-000125)
     */
    public static function generateCustomerCode(): string
    {
        $last = DB::table('investment_customers')->orderByDesc('id')->first();
        $next = ($last ? $last->id : 0) + 1;
        return sprintf('CUS-%06d', $next);
    }

    /**
     * Generate unique Inquiry Code (INQ-2027-000125)
     */
    public static function generateInquiryCode(): string
    {
        $year = date('Y');
        $count = DB::table('investment_inquiries')->whereYear('created_at', $year)->count() + 1;
        return sprintf('INQ-%s-%06d', $year, $count);
    }

    /**
     * Convert Amount to Words (English)
     */
    public static function amountToWords(float $number): string
    {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = [];
        $words = [
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
            6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
            11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty',
            30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
            80 => 'Eighty', 90 => 'Ninety'
        ];
        $digits = ['', 'Hundred', 'Thousand', 'Lakh', 'Crore'];

        // South Asian numbering scheme (Lakh, Crore)
        if ($no == 0) return 'Zero Taka Only';

        $crore = floor($no / 10000000);
        $no %= 10000000;
        $lakh = floor($no / 100000);
        $no %= 100000;
        $thousand = floor($no / 1000);
        $no %= 1000;
        $hundred = floor($no / 100);
        $no %= 100;

        $res = '';
        if ($crore > 0) $res .= self::convertTwoDigits($crore, $words) . ' Crore ';
        if ($lakh > 0) $res .= self::convertTwoDigits($lakh, $words) . ' Lakh ';
        if ($thousand > 0) $res .= self::convertTwoDigits($thousand, $words) . ' Thousand ';
        if ($hundred > 0) $res .= $words[$hundred] . ' Hundred ';
        if ($no > 0) $res .= self::convertTwoDigits($no, $words) . ' ';

        return trim($res) . ' Taka Only';
    }

    private static function convertTwoDigits($n, $words)
    {
        if ($n < 20) return $words[$n];
        return $words[10 * floor($n / 10)] . ($n % 10 > 0 ? ' ' . $words[$n % 10] : '');
    }

    /**
     * Generate Profit Installment Schedules
     */
    public static function createProfitSchedules(EnterpriseInvestment $investment): array
    {
        $schedules = [];
        $startDate = Carbon::parse($investment->start_date);
        $durationYears = $investment->duration_years;
        $amount = (float) $investment->amount;
        $rate = (float) $investment->profit_rate;
        $frequency = $investment->profit_frequency;

        if ($frequency === 'at_maturity') {
            $expected = $amount * ($rate / 100) * $durationYears;
            $schedules[] = InvestmentProfitSchedule::create([
                'investment_id' => $investment->id,
                'installment_no' => 1,
                'period_label' => "Maturity Settlement ({$durationYears} Years)",
                'due_date' => Carbon::parse($investment->maturity_date),
                'expected_profit' => $expected,
                'paid_amount' => 0,
                'due_amount' => $expected,
                'status' => 'upcoming',
            ]);
        } elseif ($frequency === 'yearly') {
            $yearlyProfit = $amount * ($rate / 100);
            for ($year = 1; $year <= $durationYears; $year++) {
                $dueDate = (clone $startDate)->addYears($year);
                $schedules[] = InvestmentProfitSchedule::create([
                    'investment_id' => $investment->id,
                    'installment_no' => $year,
                    'period_label' => "Year {$year}",
                    'due_date' => $dueDate,
                    'expected_profit' => $yearlyProfit,
                    'paid_amount' => 0,
                    'due_amount' => $yearlyProfit,
                    'status' => $year === 1 && $dueDate->isPast() ? 'due' : 'upcoming',
                ]);
            }
        } elseif ($frequency === 'monthly') {
            $totalMonths = $durationYears * 12;
            $monthlyProfit = ($amount * ($rate / 100)) / 12;
            for ($month = 1; $month <= $totalMonths; $month++) {
                $dueDate = (clone $startDate)->addMonths($month);
                $schedules[] = InvestmentProfitSchedule::create([
                    'investment_id' => $investment->id,
                    'installment_no' => $month,
                    'period_label' => "Month {$month}",
                    'due_date' => $dueDate,
                    'expected_profit' => $monthlyProfit,
                    'paid_amount' => 0,
                    'due_amount' => $monthlyProfit,
                    'status' => $dueDate->isPast() ? 'due' : 'upcoming',
                ]);
            }
        } elseif ($frequency === 'quarterly') {
            $totalQuarters = $durationYears * 4;
            $quarterlyProfit = ($amount * ($rate / 100)) / 4;
            for ($q = 1; $q <= $totalQuarters; $q++) {
                $dueDate = (clone $startDate)->addMonths($q * 3);
                $schedules[] = InvestmentProfitSchedule::create([
                    'investment_id' => $investment->id,
                    'installment_no' => $q,
                    'period_label' => "Quarter {$q}",
                    'due_date' => $dueDate,
                    'expected_profit' => $quarterlyProfit,
                    'paid_amount' => 0,
                    'due_amount' => $quarterlyProfit,
                    'status' => $dueDate->isPast() ? 'due' : 'upcoming',
                ]);
            }
        }

        return $schedules;
    }

    /**
     * Calculate Early Withdrawal Settlement
     */
    public static function calculateEarlySettlement(EnterpriseInvestment $investment, Carbon $settlementDate, string $policy = 'no_profit'): array
    {
        $startDate = Carbon::parse($investment->start_date);
        $daysElapsed = $startDate->diffInDays($settlementDate);
        $originalAmount = (float) $investment->amount;
        $totalProfitPaid = (float) $investment->paid_profit_total;

        $penalty = 0;
        $explanation = '';

        if ($policy === 'no_profit') {
            // Early exit forfeits all profit. Any already paid profit must be deducted from principal.
            $penalty = $totalProfitPaid;
            $explanation = "চুক্তি অনুসারে মেয়াদের পূর্বে উত্তোলন করায় সকল মুনাফা বাতিলযোগ্য। ইতোপূর্বে গৃহীত মোট লাভ ৳" . number_format($totalProfitPaid, 2) . " মূলধন থেকে কর্তন করা হয়েছে।";
        } elseif ($policy === 'penalty_5_percent') {
            $penalty = $totalProfitPaid + ($originalAmount * 0.05);
            $explanation = "ইতোপূর্বে গৃহীত লাভ ৳" . number_format($totalProfitPaid, 2) . " এবং ৫% প্রারম্ভিক উত্তোলন চার্জ ৳" . number_format($originalAmount * 0.05, 2) . " কর্তন করা হয়েছে।";
        }

        $netRefund = max(0, $originalAmount - $penalty);

        return [
            'days_elapsed' => $daysElapsed,
            'original_amount' => $originalAmount,
            'total_profit_paid' => $totalProfitPaid,
            'penalty_or_deduction' => $penalty,
            'net_refund_amount' => $netRefund,
            'explanation' => $explanation,
        ];
    }

    /**
     * Record a Double-Entry Ledger Transaction
     */
    public static function recordLedger(
        int $sectorId,
        int $customerId,
        ?int $investmentId,
        ?int $receiptId,
        string $transactionType,
        float $credit,
        float $debit,
        string $description,
        ?string $receiptNumber = null,
        ?string $paymentMethod = 'Cash',
        ?int $entryBy = null
    ): InvestmentLedger {
        // Calculate new balance for this sector
        $lastEntry = InvestmentLedger::where('sector_id', $sectorId)->orderByDesc('id')->first();
        $prevBalance = $lastEntry ? (float) $lastEntry->balance : 0;
        $newBalance = $prevBalance + $credit - $debit;

        return InvestmentLedger::create([
            'sector_id' => $sectorId,
            'customer_id' => $customerId,
            'investment_id' => $investmentId,
            'receipt_id' => $receiptId,
            'transaction_type' => $transactionType,
            'transaction_date' => now()->toDateString(),
            'receipt_number' => $receiptNumber,
            'payment_method' => $paymentMethod,
            'credit' => $credit,
            'debit' => $debit,
            'balance' => $newBalance,
            'description' => $description,
            'entry_by' => $entryBy ?: auth()->id(),
        ]);
    }
}
