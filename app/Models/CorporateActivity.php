<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'project_code',
        'category', // 'ongoing', 'completed', 'planned'
        'sector_type',
        'location',
        'progress_percentage',
        'budget',
        'description',
        'field_report',
        'featured_image',
        'gallery_images',
        'video_url',
        'start_date',
        'completion_date',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'progress_percentage' => 'integer',
        'budget' => 'decimal:2',
        'gallery_images' => 'array',
        'start_date' => 'date',
        'completion_date' => 'date',
    ];
}

