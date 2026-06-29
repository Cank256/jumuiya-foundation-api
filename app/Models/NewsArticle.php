<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'news_articles';

    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'content',
        'category',
        'featured_image',
        'published_at',
        'featured',
        'author_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured'     => 'boolean',
    ];

    /**
     * Auto-generate slug from title if not provided.
     */
    protected static function booted(): void
    {
        static::creating(function (self $article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (! $this->featured_image) {
            return null;
        }

        if (str_starts_with($this->featured_image, 'http')) {
            return $this->featured_image;
        }

        return Storage::disk('public')->url($this->featured_image);
    }
}
