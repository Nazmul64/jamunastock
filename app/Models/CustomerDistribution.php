<?php

namespace App\Models;

use App\Models\HRM\HrmBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDistribution extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const TYPE_LABELS = [
        'free' => ['label' => 'বিনামূল্যে বিতরণ (Free)', 'badge' => 'success'],
        'paid' => ['label' => 'নগদ বিক্রয় (Paid)', 'badge' => 'primary'],
        'subsidized' => ['label' => 'ভর্তুকি মূল্য (Subsidized)', 'badge' => 'info'],
        'credit' => ['label' => 'বাকিতে বিতরণ (Credit)', 'badge' => 'warning'],
    ];

    public const PAYMENT_LABELS = [
        'paid' => ['label' => 'Paid', 'badge' => 'success'],
        'partial' => ['label' => 'Partial', 'badge' => 'warning'],
        'unpaid' => ['label' => 'Unpaid', 'badge' => 'danger'],
        'not_applicable' => ['label' => 'N/A (Free)', 'badge' => 'secondary'],
    ];

    public static function generateCode(): string
    {
        $year = date('Y');
        $prefix = "DST-{$year}-";
        $last = self::where('distribution_code', 'like', $prefix . '%')->orderByDesc('id')->value('distribution_code');
        $next = $last ? ((int) explode('-', $last)[2]) + 1 : (self::count() + 1);
        return $prefix . str_pad((string) $next, 5, '0', STR_PAD_LEFT);
    }

    public function items()
    {
        return $this->hasMany(CustomerDistributionItem::class, 'distribution_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function agentPoint()
    {
        return $this->belongsTo(AgentPoint::class, 'agent_point_id');
    }

    public function branch()
    {
        return $this->belongsTo(HrmBranch::class, 'branch_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
