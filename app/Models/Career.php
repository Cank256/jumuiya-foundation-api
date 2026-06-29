<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Career extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'status',
        'department',
        'employment_type',
        'location',
        'salary_range',
        'application_deadline',
        'reports_to',
        'supervises_who',
        'description',
        'purpose_of_role',
        'responsibilities',
        'requirements',
        'core_competencies',
        'application_requirements',
        'application_process',
        'disclaimer',
        'apply_here',
        'has_document',
        'document_path',
        'document_name',
        'document_size',
    ];

    protected $casts = [
        'application_deadline' => 'date',
        'has_document'         => 'boolean',
    ];

    public function getDocumentDownloadUrlAttribute(): ?string
    {
        if (! $this->document_path) {
            return null;
        }

        if (str_starts_with($this->document_path, 'http')) {
            return $this->document_path;
        }

        return Storage::disk('public')->url($this->document_path);
    }

    public function getFormattedFileSizeAttribute(): ?string
    {
        if (! $this->document_size) {
            return null;
        }

        return self::formatBytes($this->document_size);
    }

    public static function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 1) . ' ' . $units[$i];
    }
}
