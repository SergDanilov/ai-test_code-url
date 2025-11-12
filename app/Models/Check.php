<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'site',
        'tz',
        'response',
        'status_code',
        'success',
        'rating'
    ];

    protected $casts = [
        'success' => 'boolean',
    ];
}