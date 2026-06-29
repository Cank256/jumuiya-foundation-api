<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnershipEnquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'organisation',
        'email',
        'partnership_type',
        'message',
        'status',
        'ip_address',
        'user_agent',
    ];
}
