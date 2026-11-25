<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoGallery extends Model
{
    use HasFactory;

    protected $table = 'video_galleries';

    protected $fillable = [
        'video_url',
        'youtube_id',
        'title',
        'is_active',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope active videos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Relationship with Creator/Uploader
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Extract YouTube Video ID from any format URL
     * Supports:
     * - https://www.youtube.com/watch?v=XXXX
     * - https://youtu.be/XXXX
     * - https://www.youtube.com/embed/XXXX
     * - https://www.youtube.com/shorts/XXXX
     * - https://youtube.com/live/XXXX
     */
    public static function extractYoutubeId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);

        // Pattern 1: youtu.be/ID
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_\-]{11})/', $url, $matches)) {
            return $matches[1];
        }

        // Pattern 2: youtube.com/shorts/ID or embed/ID or v/ID or live/ID
        if (preg_match('/youtube\.com\/(?:embed|shorts|v|live)\/([a-zA-Z0-9_\-]{11})/', $url, $matches)) {
            return $matches[1];
        }

        // Pattern 3: youtube.com/watch?v=ID or other query string
        if (preg_match('/[?&]v=([a-zA-Z0-9_\-]{11})/', $url, $matches)) {
            return $matches[1];
        }

        // Pattern 4: Bare 11-char ID
        if (preg_match('/^[a-zA-Z0-9_\-]{11}$/', $url)) {
            return $url;
        }

        return null;
    }

    /**
     * Get Embed URL
     */
    public function getYoutubeEmbedUrlAttribute(): string
    {
        $id = $this->youtube_id ?: self::extractYoutubeId($this->video_url);
        return $id ? "https://www.youtube.com/embed/{$id}?rel=0&modestbranding=1" : '';
    }

    /**
     * Get High Quality Thumbnail URL
     */
    public function getYoutubeThumbnailUrlAttribute(): string
    {
        $id = $this->youtube_id ?: self::extractYoutubeId($this->video_url);
        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : asset('frontend/images/video-placeholder.jpg');
    }

    /**
     * Automatically extract youtube_id upon saving if not set
     */
    protected static function booted()
    {
        static::saving(function ($model) {
            if (empty($model->youtube_id) && !empty($model->video_url)) {
                $model->youtube_id = self::extractYoutubeId($model->video_url);
            }
        });
    }
}
