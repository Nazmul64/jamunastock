<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $table = 'chart_of_accounts';

    protected $fillable = [
        'code',
        'name',
        'type',
        'category',
        'parent_id',
        'opening_balance',
        'current_balance',
        'currency',
        'is_reconciled',
        'status',
        'description',
    ];

    public function parent()
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    public function ledgerEntries()
    {
        return $this->hasMany(GeneralLedgerEntry::class, 'account_id');
    }

    public function typeBadge()
    {
        $badges = [
            'asset' => '<span class="badge bg-success-light text-success fw-bold px-2 py-1">সম্পদ (Asset)</span>',
            'liability' => '<span class="badge bg-danger-light text-danger fw-bold px-2 py-1">দায় (Liability)</span>',
            'equity' => '<span class="badge bg-primary-light text-primary fw-bold px-2 py-1">মালিকানা স্বত্ব (Equity)</span>',
            'income' => '<span class="badge bg-info-light text-info fw-bold px-2 py-1">আয় (Revenue)</span>',
            'expense' => '<span class="badge bg-warning-light text-warning fw-bold px-2 py-1">ব্যয় (Expense)</span>',
        ];
        return $badges[$this->type] ?? '<span class="badge bg-secondary">' . ucfirst($this->type) . '</span>';
    }
}

