<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreVoucher extends Model
{
    use HasFactory;

    protected $table = 'store_vouchers';
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'debit_voucher' => 'ডেবিট ভাউচার (Debit Voucher)',
            'credit_voucher' => 'ক্রেডিট ভাউচার (Credit Voucher)',
            'cash_memo' => 'ক্যাশ মেমো (Cash Memo)',
            'money_receipt' => 'মানি রিসিট (Money Receipt)',
            'bill' => 'বিল (Bill)',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    public function getTypeBadgeAttribute()
    {
        return match($this->type) {
            'debit_voucher' => '<span class="badge bg-danger">ডেবিট ভাউচার</span>',
            'credit_voucher' => '<span class="badge bg-success">ক্রেডিট ভাউচার</span>',
            'cash_memo' => '<span class="badge bg-primary">ক্যাশ মেমো</span>',
            'money_receipt' => '<span class="badge bg-info text-dark">মানি রিসিট</span>',
            'bill' => '<span class="badge bg-warning text-dark">বিল</span>',
            default => '<span class="badge bg-secondary">' . $this->type . '</span>',
        };
    }

    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'furniture' => 'আসবাবপত্র ক্রয়/মেরামত',
            'vehicle_maintenance' => 'যানবাহন ও ফুয়েল খরচ',
            'stationery' => 'খাতাপত্র ও অফিস স্টেশনারি',
            'office_expense' => 'সাধারণ অফিস খরচ',
            'store_purchase' => 'স্টোর সামগ্রী ক্রয়',
            'repair' => 'মেরামত ও রক্ষণাবেক্ষণ',
            'general_utility' => 'ইউটিলিটি ও অন্যান্য বিল',
            default => 'অন্যান্য ব্যয়',
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'paid' => '<span class="badge bg-success">পরিশোধিত (Paid)</span>',
            'approved' => '<span class="badge bg-info text-dark">অনুমোদিত (Approved)</span>',
            'pending' => '<span class="badge bg-warning text-dark">অপেক্ষমাণ (Pending)</span>',
            'rejected' => '<span class="badge bg-danger">প্রত্যাখ্যাত (Rejected)</span>',
            default => '<span class="badge bg-light text-dark">' . $this->status . '</span>',
        };
    }
}

