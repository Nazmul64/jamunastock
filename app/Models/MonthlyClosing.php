<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyClosing extends Model
{
    use HasFactory;

    protected $table = 'monthly_closings';
    protected $guarded = [];

    public function closer()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
