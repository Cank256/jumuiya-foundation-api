<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    public const UPDATED_AT = null; // single-timestamp table

    protected $fillable = [
        'type',
        'path',
        'title',
        'button_name',
        'section',
        'form_name',
        'success',
        'ip',
        'user_agent',
        'referer',
        'session_id',
        'occurred_at',
    ];

    protected $casts = [
        'success'     => 'boolean',
        'occurred_at' => 'datetime',
        'created_at'  => 'datetime',
    ];
}
