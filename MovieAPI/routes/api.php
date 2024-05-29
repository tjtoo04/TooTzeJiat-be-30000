<?php

use App\Http\Controllers\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Routes
//GET
//Genre, Timeslot, specific music theater, searchperformer, new_movie


//POST
//give_rating, add_movie
Route::get('movies', [MovieController::class, 'index']);

Route::get('new_movies', [MovieController::class, 'new_movies']);
Route::get('genre', [MovieController::class, 'genres']);
Route::get('specific_movie_theater', [MovieController::class, 'specific_movie_theater']);
Route::get('timeslot', [MovieController::class, 'timeslot']);
Route::get('search_performer', [MovieController::class, 'search_performer']);

Route::post('give_rating', [MovieController::class, 'store_rating']);
Route::post('add_movie', [MovieController::class, 'create_movie']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
