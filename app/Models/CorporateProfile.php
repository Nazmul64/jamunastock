<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_key', // e.g. 'history', 'vision_mission', 'objectives', 'future_plans', 'achievements'
        'title_bn',
        'title_en',
        'content_bn',
        'content_en',
        'featured_image',
        'extra_points',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'extra_points' => 'array',
    ];
}

