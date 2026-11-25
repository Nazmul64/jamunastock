<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'summary',
        'content',
        'image',
        'author_name',
        'views_count',
        'is_featured',
        'status',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path('uploads/blogs/' . $this->image))) {
            return asset('uploads/blogs/' . $this->image);
        }
        return asset('assets/images/placeholder-blog.jpg');
    }
}
