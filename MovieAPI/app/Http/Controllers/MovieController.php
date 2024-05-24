<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = DB::table('movies')->get();
        return response()->json($movies);
    }

    public function new_movies(Request $request) {
        $r_date = strip_tags($request->query('r_date'));
        if ($r_date != "") {
            $new_movies = DB::table('movies')
            ->where('release', "<=", $r_date)
            ->get();
            
            return response()->json($new_movies);
        }else {
            $currentTime = Carbon::now()->format("Y-m-d");
            $new_movies = DB::table('movies')
            ->where('release', "<=", $currentTime)
            ->get();

            return response()->json($new_movies);
        }

    }

    public function genres(Request $request) {
        $available_genres = ['action', 'comedy', 'horror', 'adventure'];

        $genre = strip_tags($request->query('genre'));

        if ($genre !== '' && in_array($genre, $available_genres, true)){
            $ratingsSub = DB::table('ratings')
                ->select(DB::raw('AVG(ratings.rating) AS rating'), 'ratings.movie_ID')
                ->groupBy('movie_ID');
    
            $movies = DB::table('movies')
                ->joinSub($ratingsSub, 'ratings', function (JoinClause $join){
                    $join->on('movies.movie_ID','=', 'ratings.movie_ID');
                })
                ->select('movies.movie_ID', 'movies.title', 'movies.genre', DB::raw('(SEC_TO_TIME(movies.durationMinutes * 60)) AS duration'), 'movies.view_count', 'movies.poster', 'ratings.rating', 'movies.description')
                ->where('movies.genre', '=', $genre)
                ->get();
    
    
            return response()->json($movies);
        }else {
            return response()->json(['data not found'], 400);
        }

    }


    public function specific_movie_theater(Request $request) {
        $theaterName = strip_tags($request->query('theater_name'));

        $availableTheatersSub = DB::table('theaters')
            ->select('*');

        $ratingsSub = DB::table('ratings')
        ->select(DB::raw('AVG(ratings.rating) AS rating'), 'ratings.movie_ID')
        ->groupBy('movie_ID');

        $movie_rooms = DB::table('movie_rooms')
            ->join('theaters', 'theaters.theater_ID', '=', 'movie_rooms.theater_ID')
            ->select('movie_rooms.movie_ID', 'theaters.name AS theater_name', 'movie_rooms.start_time', 'movie_rooms.end_time', 'movie_rooms.theater_room_no');

        if ($theaterName != '') {
            $movies = DB::table('movies')
                ->joinSub($ratingsSub, 'ratings', function (JoinClause $join){
                    $join->on('movies.movie_ID','=', 'ratings.movie_ID');
                })
                ->joinSub($movie_rooms, 'movie_rooms', function(JoinClause $join){
                    $join->on('movie_rooms.movie_ID', '=', 'movies.movie_ID');
                })
                ->select('movies.movie_ID', 'movies.title', 'movies.genre', DB::raw('(SEC_TO_TIME(movies.durationMinutes * 60)) AS duration'), 'movies.poster', 'ratings.rating', 'movies.description', 'movie_rooms.theater_name', 'movie_rooms.start_time', 'movie_rooms.end_time', 'movie_rooms.theater_room_no')
                ->where('movie_rooms.theater_name', '=', $theaterName)
                ->get();
                
            if ($movies->isNotEmpty()){
                return response()->json($movies);

            }else {
                return response()->json(['data not found'], 400);
            }

            
    } else {
        return response()->json(['data not found'], 400);
    }
    
}
}