<?php

use App\Http\Controllers\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Routes
//GET
//Genre, Timeslot, specific music theater, searchperformer, new_movie


//POST
//give_rating, add_movie

Route::get('new_movies', [MovieController::class, 'new_movies']);
Route::get('movies', [MovieController::class, 'index']);
Route::get('genre', [MovieController::class, 'genres']);
Route::get('specific_movie_theater', [MovieController::class, 'specific_movie_theater']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
