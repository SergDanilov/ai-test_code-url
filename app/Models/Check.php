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
        'tz',
        'response',
        'status_code',
        'success'
    ];

    protected $casts = [
        'success' => 'boolean',
    ];
}