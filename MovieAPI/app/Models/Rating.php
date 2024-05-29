<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $primaryKey = 'rating_ID';

    protected $fillable = [
        'movie_ID',
        'user_ID',
        'rating',
        'r_description',
    ];
}
