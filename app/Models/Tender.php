<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Tender extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'status',
        'reference_number',
        'description',
        'requirements',
        'deadline',
        'document_url',
        'has_rfp_document',
        'rfp_path',
        'rfp_document_name',
        'rfp_document_size',
        'has_tor_document',
        'tor_path',
        'tor_document_name',
        'tor_document_size',
    ];

    protected $casts = [
        'deadline'         => 'datetime',
        'has_rfp_document' => 'boolean',
        'has_tor_document' => 'boolean',
    ];

    public function tenderDocuments(): HasMany
    {
        return $this->hasMany(TenderDocument::class);
    }

    public function getRfpDownloadUrlAttribute(): ?string
    {
        if (! $this->rfp_path) {
            return null;
        }

        return str_starts_with($this->rfp_path, 'http')
            ? $this->rfp_path
            : Storage::disk('public')->url($this->rfp_path);
    }

    public function getTorDownloadUrlAttribute(): ?string
    {
        if (! $this->tor_path) {
            return null;
        }

        return str_starts_with($this->tor_path, 'http')
            ? $this->tor_path
            : Storage::disk('public')->url($this->tor_path);
    }
}
