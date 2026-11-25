<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'position',
        'address',
        'resume_file',
        'original_filename',
        'notes',
        'status',
        'admin_notes',
    ];

    /**
     * Get candidate full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get candidate resume public URL.
     */
    public function getResumeUrlAttribute(): string
    {
        return asset($this->resume_file);
    }
}

