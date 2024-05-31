<?php

use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('create_movie', [MovieController::class, 'index'])->name('create_movie');
Route::post('create_movie', [MovieController::class, 'create_movie']);