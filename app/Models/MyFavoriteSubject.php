<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MyFavoriteSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'description',
        'category',
        'rating'
    ];
}
