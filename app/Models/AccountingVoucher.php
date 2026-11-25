<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingVoucher extends Model
{
    use HasFactory;

    protected $table = 'accounting_vouchers';

    protected $fillable = [
        'voucher_no',
        'voucher_type',
        'voucher_date',
        'financial_year',
        'reference_no',
        'payment_mode',
        'payee_payer_name',
        'bank_account_name',
        'cheque_no',
        'cheque_date',
        'total_debit',
        'total_credit',
        'amount_in_words',
        'general_narration',
        'status',
        'attachment',
        'prepared_by',
        'checked_by',
        'approved_by',
        'posted_by',
        'posted_at',
        'is_reversed',
        'reversal_voucher_id',
        'reversal_reason',
    ];

    protected $dates = [
        'voucher_date',
        'cheque_date',
        'posted_at',
    ];

    public function entries()
    {
        return $this->hasMany(VoucherEntry::class, 'voucher_id');
    }

    public function debitEntries()
    {
        return $this->hasMany(VoucherEntry::class, 'voucher_id')->where('entry_type', 'debit');
    }

    public function creditEntries()
    {
        return $this->hasMany(VoucherEntry::class, 'voucher_id')->where('entry_type', 'credit');
    }

    public function billAllocations()
    {
        return $this->hasMany(BillPaymentAllocation::class, 'voucher_id');
    }

    public function ledgerEntries()
    {
        return $this->hasMany(GeneralLedgerEntry::class, 'voucher_id');
    }

    public function preparedByUser()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function checkedByUser()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function postedByUser()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function reversalVoucher()
    {
        return $this->belongsTo(AccountingVoucher::class, 'reversal_voucher_id');
    }

    public function voucherTypeBangla()
    {
        $types = [
            'payment' => 'পেমেন্ট ভাউচার (Payment Voucher)',
            'receipt' => 'রিসিপ্ট ভাউচার (Receipt Voucher)',
            'cash_payment' => 'ক্যাশ পেমেন্ট ভাউচার (CPV)',
            'bank_payment' => 'ব্যাংক পেমেন্ট ভাউচার (BPV)',
            'cash_receipt' => 'ক্যাশ রিসিপ্ট ভাউচার (CRV)',
            'bank_receipt' => 'ব্যাংক রিসিপ্ট ভাউচার (BRV)',
            'journal' => 'জার্নাল ভাউচার (Journal Voucher)',
            'contra' => 'কন্ট্রা ভাউচার (Contra Voucher)',
            'expense' => 'খরচ ভাউচার (Expense Voucher)',
            'income' => 'আয় ভাউচার (Income Voucher)',
            'debit_note' => 'ডেবিট নোট (Debit Note)',
            'credit_note' => 'ক্রেডিট নোট (Credit Note)',
        ];

        return $types[$this->voucher_type] ?? ucfirst(str_replace('_', ' ', $this->voucher_type));
    }

    public function statusBadge()
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary rounded-pill px-3 py-1 fs-11">খসড়া (Draft)</span>',
            'submitted' => '<span class="badge bg-info text-white rounded-pill px-3 py-1 fs-11">পর্যালোচনায় (Submitted)</span>',
            'approved' => '<span class="badge bg-primary rounded-pill px-3 py-1 fs-11">অনুমোদিত (Approved)</span>',
            'posted' => '<span class="badge bg-success rounded-pill px-3 py-1 fs-11"><i class="fa fa-check-circle me-1"></i>পোস্টেড (Posted)</span>',
            'reversed' => '<span class="badge bg-warning text-dark rounded-pill px-3 py-1 fs-11"><i class="fa fa-undo me-1"></i>রিভার্সড (Reversed)</span>',
            'cancelled' => '<span class="badge bg-danger rounded-pill px-3 py-1 fs-11">বাতিল (Cancelled)</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-light text-dark">' . ucfirst($this->status) . '</span>';
    }

    public static function numberToWords($num)
    {
        $num = (float) $num;
        $ones = [
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
            6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
            11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen'
        ];
        $tens = [
            2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty',
            6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
        ];

        if ($num == 0) {
            return 'Zero Taka Only';
        }

        $integerPart = floor($num);
        $decimalPart = round(($num - $integerPart) * 100);

        $words = self::convertIntegerToWords($integerPart, $ones, $tens) . ' Taka';
        if ($decimalPart > 0) {
            $words .= ' and ' . self::convertIntegerToWords($decimalPart, $ones, $tens) . ' Paisa';
        }

        return $words . ' Only';
    }

    private static function convertIntegerToWords($number, $ones, $tens)
    {
        if ($number < 20) {
            return $ones[$number];
        }
        if ($number < 100) {
            return $tens[floor($number / 10)] . ($number % 10 ? ' ' . $ones[$number % 10] : '');
        }
        if ($number < 1000) {
            return $ones[floor($number / 100)] . ' Hundred' . ($number % 100 ? ' and ' . self::convertIntegerToWords($number % 100, $ones, $tens) : '');
        }
        if ($number < 100000) {
            return self::convertIntegerToWords(floor($number / 1000), $ones, $tens) . ' Thousand' . ($number % 1000 ? ' ' . self::convertIntegerToWords($number % 1000, $ones, $tens) : '');
        }
        if ($number < 10000000) {
            return self::convertIntegerToWords(floor($number / 100000), $ones, $tens) . ' Lac' . ($number % 100000 ? ' ' . self::convertIntegerToWords($number % 100000, $ones, $tens) : '');
        }
        return self::convertIntegerToWords(floor($number / 10000000), $ones, $tens) . ' Crore' . ($number % 10000000 ? ' ' . self::convertIntegerToWords($number % 10000000, $ones, $tens) : '');
    }
}

