<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialBill extends Model
{
    use HasFactory;

    protected $table = 'financial_bills';

    protected $fillable = [
        'bill_no',
        'reference_no',
        'bill_type',
        'bill_date',
        'due_date',
        'financial_year',
        'party_type',
        'party_id',
        'party_name',
        'party_phone',
        'party_address',
        'subtotal',
        'discount_amount',
        'tax_vat_amount',
        'additional_charges',
        'grand_total',
        'paid_amount',
        'outstanding_amount',
        'payment_status',
        'approval_status',
        'notes',
        'attachment',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $dates = ['bill_date', 'due_date', 'approved_at'];

    public function items()
    {
        return $this->hasMany(FinancialBillItem::class, 'bill_id');
    }

    public function paymentAllocations()
    {
        return $this->hasMany(BillPaymentAllocation::class, 'bill_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function billTypeBangla()
    {
        $types = [
            'purchase' => 'ক্রয় বিল (Purchase)',
            'sales' => 'বিক্রয় বিল (Sales)',
            'service' => 'সার্ভিস বিল (Service)',
            'utility' => 'ইউটিলিটি বিল (Utility)',
            'electricity' => 'বিদ্যুৎ বিল (Electricity)',
            'gas' => 'গ্যাস বিল (Gas)',
            'water' => 'পানি বিল (Water)',
            'internet' => 'ইন্টারনেট বিল (Internet)',
            'rent' => 'অফিস ভাড়া বিল (Rent)',
            'transport' => 'যাতায়াত ও গাড়ি বিল',
            'maintenance' => 'মেরামত ও রক্ষণাবেক্ষণ',
            'salary' => 'বেতন বিল (Salary)',
            'other' => 'অন্যান্য বিল (Other)',
        ];
        return $types[$this->bill_type] ?? ucfirst($this->bill_type);
    }

    public function paymentStatusBadge()
    {
        $badges = [
            'unpaid' => '<span class="badge bg-danger rounded-pill px-3 py-1 fw-bold fs-11">পরিশোধহীন (Unpaid)</span>',
            'partially_paid' => '<span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold fs-11">আংশিক পরিশোধ (Partial)</span>',
            'paid' => '<span class="badge bg-success rounded-pill px-3 py-1 fw-bold fs-11">সম্পূর্ণ পরিশোধিত (Paid)</span>',
            'overdue' => '<span class="badge bg-danger rounded-pill px-3 py-1 fw-bold fs-11">মেয়াদোত্তীর্ণ (Overdue)</span>',
            'cancelled' => '<span class="badge bg-secondary rounded-pill px-3 py-1 fw-bold fs-11">বাতিল (Cancelled)</span>',
        ];
        return $badges[$this->payment_status] ?? '<span class="badge bg-light text-dark">' . ucfirst($this->payment_status) . '</span>';
    }

    public function approvalStatusBadge()
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary-light text-secondary rounded-pill px-2 py-1 fs-11">খসড়া (Draft)</span>',
            'submitted' => '<span class="badge bg-info-light text-info rounded-pill px-2 py-1 fs-11">পর্যালোচনাধীন (Submitted)</span>',
            'under_review' => '<span class="badge bg-warning-light text-warning rounded-pill px-2 py-1 fs-11">যাচাইকরণ (Under Review)</span>',
            'approved' => '<span class="badge bg-success-light text-success rounded-pill px-2 py-1 fs-11">অনুমোদিত (Approved)</span>',
            'rejected' => '<span class="badge bg-danger-light text-danger rounded-pill px-2 py-1 fs-11">প্রত্যাখ্যাত (Rejected)</span>',
            'cancelled' => '<span class="badge bg-dark-light text-dark rounded-pill px-2 py-1 fs-11">বাতিল (Cancelled)</span>',
        ];
        return $badges[$this->approval_status] ?? ucfirst($this->approval_status);
    }
}

