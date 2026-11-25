<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpDamageLossDisposal extends Model
{
    use HasFactory;

    protected $table = 'erp_damage_loss_disposals';

    protected $fillable = [
        'record_no',
        'type',
        'asset_id',
        'item_id',
        'quantity',
        'incident_date',
        'reason',
        'estimated_loss',
        'original_book_value',
        'scrap_selling_price',
        'net_gain_loss',
        'responsible_person',
        'disposal_method',
        'status',
        'approved_by',
        'notes',
    ];

    public function typeBadge()
    {
        $types = [
            'damage' => '<span class="badge bg-warning text-dark">ক্ষতিগ্রস্ত (Damage)</span>',
            'lost' => '<span class="badge bg-danger">হারানো সম্পদ (Lost)</span>',
            'disposal' => '<span class="badge bg-secondary">বাতিল/নিষ্কাশন (Disposal)</span>',
            'write_off' => '<span class="badge bg-dark">রাইট-অফ (Write-off)</span>',
        ];
        return $types[$this->type] ?? ucfirst($this->type);
    }
}

