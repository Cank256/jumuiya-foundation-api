<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class AnnualReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'label',
        'title',
        'year',
        'file_path',
        'href',
        'file_size',
        'sort_order',
    ];

    protected $casts = [
        'file_size'  => 'integer',
        'sort_order' => 'integer',
    ];

    public function getDownloadUrlAttribute(): ?string
    {
        if ($this->file_path) {
            if (str_starts_with($this->file_path, 'http')) {
                return $this->file_path;
            }

            return Storage::disk('public')->url($this->file_path);
        }

        return $this->href;
    }

    public function getFormattedFileSizeAttribute(): ?string
    {
        if (! $this->file_size) {
            return null;
        }

        $mb = round($this->file_size / 1048576, 1);

        return "PDF · {$mb} MB";
    }
}
