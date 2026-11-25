<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'notice_type', // 'general', 'shareholder', 'investor', 'employee', 'urgent'
        'content',
        'attachment_file',
        'is_pinned',
        'is_active',
        'publish_date',
        'expiry_date',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_active' => 'boolean',
        'publish_date' => 'date',
        'expiry_date' => 'date',
    ];
}

