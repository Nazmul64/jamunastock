<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'galleries';

    protected $fillable = [
        'title',
        'image',
        'category',
        'is_active',
        'sort_order',
        'uploaded_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope for active gallery items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Accessor for full image URL
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('uploads/gallery/' . $this->image))) {
            return asset('uploads/gallery/' . $this->image);
        }

        // Check fallback in upload/gallery or upload/stock_images
        if ($this->image && file_exists(public_path('upload/stock_images/' . $this->image))) {
            return asset('upload/stock_images/' . $this->image);
        }

        return asset('assets/images/placeholder-image.png');
    }

    /**
     * User who uploaded the image
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
