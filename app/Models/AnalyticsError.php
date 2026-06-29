<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsError extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'message',
        'context',
        'ip',
        'user_agent',
        'occurred_at',
    ];

    protected $casts = [
        'context'     => 'array',
        'occurred_at' => 'datetime',
        'created_at'  => 'datetime',
    ];
}
