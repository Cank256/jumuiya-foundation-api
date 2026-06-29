<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category',
        'location',
        'start_date',
        'end_date',
        'time',
        'seats',
        'status',
        'featured',
        'featured_image',
        'registration_url',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'featured'   => 'boolean',
    ];

    /**
     * Return the absolute URL for the featured image.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (! $this->featured_image) {
            return null;
        }

        // If already an absolute URL (e.g. external), return as-is
        if (str_starts_with($this->featured_image, 'http')) {
            return $this->featured_image;
        }

        return Storage::disk('public')->url($this->featured_image);
    }
}
