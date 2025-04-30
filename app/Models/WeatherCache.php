<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeatherCache extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'weather_data',
        'expires_at'
    ];

    protected $casts = [
        'weather_data' => 'array',
        'expires_at' => 'datetime'
    ];
}
