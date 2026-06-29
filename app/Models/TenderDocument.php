<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TenderDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_id',
        'name',
        'type',
        'path',
        'size',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->path, 'http')) {
            return $this->path;
        }

        return Storage::disk('public')->url($this->path);
    }
}
