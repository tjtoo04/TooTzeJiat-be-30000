<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'movie_ID';
    public $timestamps = false; 

    protected $fillable = [
        'release',
        'title',
        'genre',
        'durationMinutes',
        'mpaa_rating',
        'director',
        'performer',
        'language',
        'description'
    ];
}
