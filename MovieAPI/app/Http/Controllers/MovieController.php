<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Rating;
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
        return view('add_movie');
    }

    public function new_movies(Request $request) {
        //sanitizes the query
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
        $genre = strip_tags($request->query('genre'));
        $available_genres = DB::table('movies')->select('genre', 'movie_ID')->get();
        $data = [];
        $wantedGenresMovieID = [];

        foreach ($available_genres as $key => $value) {
            $value->genre = explode(',', $value->genre);
            array_push($data, $value);
        }

        foreach ($data as $key => $value) {
            if (in_array($genre, $value->genre, true)){
                array_push($wantedGenresMovieID, $value->movie_ID);
            }
        }

        if ($genre !== ''){
            $ratingsSub = DB::table('ratings')
            ->select(DB::raw('AVG(ratings.rating) AS rating'), 'ratings.movie_ID')
            ->whereIn('ratings.movie_ID', $wantedGenresMovieID)
            ->groupBy('movie_ID');

            $movies = DB::table('movies')
                ->joinSub($ratingsSub, 'ratings', function (JoinClause $join){
                    $join->on('movies.movie_ID','=', 'ratings.movie_ID');
                })
                ->select('movies.movie_ID', 'movies.title', 'movies.genre', DB::raw('(SEC_TO_TIME(movies.durationMinutes * 60)) AS duration'), 'movies.view_count', 'movies.poster', 'ratings.rating', 'movies.description')
                ->whereIn('movies.movie_ID', $wantedGenresMovieID)
                ->get();
    
            $moviesNoRatings = DB::table('movies')
                ->select('movies.movie_ID', 'movies.title', 'movies.genre', DB::raw('(SEC_TO_TIME(movies.durationMinutes * 60)) AS duration'), 'movies.view_count', 'movies.poster', 'movies.description')
                ->whereIn('movies.movie_ID', $wantedGenresMovieID)
                ->get();

            //combines movies with rating and no ratings
            foreach ($moviesNoRatings as $key => $value) {
                if (!in_array($value->movie_ID, $movies->pluck('movie_ID')->toArray())){
                    $movies->push($value);

                }
            }
    
            if (!$movies->isEmpty()){
                return response()->json($movies);

            }else {
                return response()->json(['Data not found'], 400);
            }
            
        }
        else {
            return response()->json(['Invalid parameters'], 400);
        }

    }


    public function specific_movie_theater(Request $request) {
        $theaterName = strip_tags($request->query('theater_name'));
        $date = strip_tags($request->query('d_date')) . ' 00:00:00';

        $ratingsSub = DB::table('ratings')
        ->select(DB::raw('AVG(ratings.rating) AS rating'), 'ratings.movie_ID')
        ->groupBy('movie_ID');

        $movie_rooms = DB::table('movie_rooms')
            ->join('theaters', 'theaters.theater_ID', '=', 'movie_rooms.theater_ID')
            ->select('movie_rooms.movie_ID', 'theaters.theater_ID', 'theaters.name AS theater_name', 'movie_rooms.start_time', 'movie_rooms.end_time', 'movie_rooms.theater_room_no');

        if ($theaterName != '' && $date != " 00:00:00") {
            $movies = DB::table('movies')
                ->joinSub($ratingsSub, 'ratings', function (JoinClause $join){
                    $join->on('movies.movie_ID','=', 'ratings.movie_ID');
                })
                ->joinSub($movie_rooms, 'movie_rooms', function(JoinClause $join){
                    $join->on('movie_rooms.movie_ID', '=', 'movies.movie_ID');
                })
                ->select('movies.movie_ID', 'movies.title','movies.view_count', 'movies.genre', DB::raw('(SEC_TO_TIME(movies.durationMinutes * 60)) AS duration'), 'movies.poster', 'ratings.rating', 'movies.description', 'movie_rooms.theater_name', 'movie_rooms.start_time', 'movie_rooms.end_time', 'movie_rooms.theater_room_no')
                ->where('movie_rooms.theater_name', '=', $theaterName)
                ->where('movie_rooms.start_time', ">", $date) 
                ->get();
                
            if ($movies->isNotEmpty()){
                return response()->json($movies);

            }else {
                return response()->json(['Data not found'], 400);
            }

            
    } else if ($theaterName != '') {
        //returns movies regardless of date
        $movies = DB::table('movies')
                ->joinSub($ratingsSub, 'ratings', function (JoinClause $join){
                    $join->on('movies.movie_ID','=', 'ratings.movie_ID');
                })
                ->joinSub($movie_rooms, 'movie_rooms', function(JoinClause $join){
                    $join->on('movie_rooms.movie_ID', '=', 'movies.movie_ID');
                })
                ->select('movies.movie_ID', 'movies.title','movies.view_count', 'movies.genre', DB::raw('(SEC_TO_TIME(movies.durationMinutes * 60)) AS duration'), 'movies.poster', 'ratings.rating', 'movies.description', 'movie_rooms.theater_name', 'movie_rooms.start_time', 'movie_rooms.end_time', 'movie_rooms.theater_room_no')
                ->where('movie_rooms.theater_name', '=', $theaterName) 
                ->get();
                
            if ($movies->isNotEmpty()){
                return response()->json($movies);

            }else {
                return response()->json(['Data not found'], 400);
            }
    }else if ($date != " 00:00:00") {
        //returns movies regardless of theater
        $movies = DB::table('movies')
                ->joinSub($ratingsSub, 'ratings', function (JoinClause $join){
                    $join->on('movies.movie_ID','=', 'ratings.movie_ID');
                })
                ->joinSub($movie_rooms, 'movie_rooms', function(JoinClause $join){
                    $join->on('movie_rooms.movie_ID', '=', 'movies.movie_ID');
                })
                ->select('movies.movie_ID', 'movies.title','movies.view_count', 'movies.genre', DB::raw('(SEC_TO_TIME(movies.durationMinutes * 60)) AS duration'), 'movies.poster', 'ratings.rating', 'movies.description', 'movie_rooms.theater_name', 'movie_rooms.start_time', 'movie_rooms.end_time', 'movie_rooms.theater_room_no')
                ->where('movie_rooms.start_time', ">", $date) 
                ->get();
                
            if ($movies->isNotEmpty()){
                return response()->json($movies);

            }else {
                return response()->json(['Data not found'], 400);
            }
    }
    else {
        return response()->json(['Invalid parameters'], 400);
    }
    
}

    public function timeslot(Request $request) {
        $theaterName = strip_tags($request->query('theater_name'));
        $time_start = strip_tags($request->query('time_start'));
        $time_end = strip_tags($request->query('time_end'));

        $ratingsSub = DB::table('ratings')
        ->select(DB::raw('AVG(ratings.rating) AS rating'), 'ratings.movie_ID')
        ->groupBy('movie_ID');

        $movie_rooms = DB::table('movie_rooms')
            ->join('theaters', 'theaters.theater_ID', '=', 'movie_rooms.theater_ID')
            ->select('movie_rooms.movie_ID', 'theaters.theater_ID', 'theaters.name AS theater_name', 'movie_rooms.start_time', 'movie_rooms.end_time', 'movie_rooms.theater_room_no');

        if ($theaterName != '' && $time_start != "" && $time_end != "") {
            $movies = DB::table('movies')
                ->joinSub($ratingsSub, 'ratings', function (JoinClause $join){
                    $join->on('movies.movie_ID','=', 'ratings.movie_ID');
                })
                ->joinSub($movie_rooms, 'movie_rooms', function(JoinClause $join){
                    $join->on('movie_rooms.movie_ID', '=', 'movies.movie_ID');
                })
                ->select('movies.movie_ID', 'movies.title','movies.view_count', 'movies.genre', DB::raw('(SEC_TO_TIME(movies.durationMinutes * 60)) AS duration'), 'movies.poster', 'ratings.rating', 'movies.description', 'movie_rooms.theater_name', 'movie_rooms.start_time', 'movie_rooms.end_time', 'movie_rooms.theater_room_no')
                ->where('movie_rooms.theater_name', '=', $theaterName)
                ->where('movie_rooms.start_time', ">", $time_start)
                ->where('movie_rooms.end_time', "<", $time_end) 
                ->get();
                
            if ($movies->isNotEmpty()){
                return response()->json($movies);

            }else {
                return response()->json(['Data not found'], 400);
            }

        }else {
            return response()->json(['Invalid parameters'], 400);
        }

    }

    public function search_performer(Request $request) {
        $performer = $request->query('performer_name');   
        $movie_performers = DB::table('movies')->select('performer', 'movie_ID')->get();
        
        $data = [];
        $wantedPerformerMovieID = [];
        
        foreach ($movie_performers as $key => $value) {
            $value->performer = explode(',', $value->performer);
           array_push($data, $value);
        }

        foreach ($data as $key => $value) {
            if (in_array($performer, $data[$key]->performer, true)){
                array_push($wantedPerformerMovieID, $data[$key]->movie_ID); 
                
            }
        }

        if ($performer != ''){
            $ratingsSub = DB::table('ratings')
                ->select(DB::raw('AVG(ratings.rating) AS rating'), 'ratings.movie_ID')
                ->whereIn('movie_ID', $wantedPerformerMovieID)
                ->groupBy('movie_ID');

            $movies = DB::table('movies')
                ->joinSub($ratingsSub, 'ratings', function (JoinClause $join){
                    $join->on('movies.movie_ID','=', 'ratings.movie_ID');
                })
                ->whereIn('movies.movie_ID', $wantedPerformerMovieID)
                ->select('movies.movie_ID', 'movies.title', 'movies.genre', DB::raw('(SEC_TO_TIME(movies.durationMinutes * 60)) AS duration'), 'movies.view_count','ratings.rating', 'movies.poster', 'movies.description')
                ->get();
    
            $moviesNoRatings = DB::table('movies')
                ->whereIn('movies.movie_ID', $wantedPerformerMovieID)
                ->select('movies.movie_ID', 'movies.title', 'movies.genre', DB::raw('(SEC_TO_TIME(movies.durationMinutes * 60)) AS duration'), 'movies.view_count', 'movies.poster', 'movies.description')
                ->get();

            //also combines movies with rating and no rating
            foreach ($moviesNoRatings as $key => $value) {
                if(!in_array($value->movie_ID, $movies->pluck('movie_ID')->toArray()))
                $movies->push($value);
            }

            if (!$movies->isEmpty()){
                return response()->json($movies);

            }else {
                return response()->json(['Data not found'], 400);
            }

        }
        
        else {
            return response()->json(['Invalid parameters'], 400);
            }
    }

    public function store_rating(Request $request) {
        $rating = strip_tags($request->query('rating'));
        $movie_title = strip_tags($request->query('movie_title'));
        $username = strip_tags($request->query('username'));
        $r_description = strip_tags($request->query('r_description'));

        if ($username != '' && $movie_title != '' && $rating != '' && $r_description != ''){
            $user_ID = DB::table('users')
                ->select('user_ID')
                ->where('username', "=", $username)
                ->first();

            $movie_ID = DB::table('movies')
            ->select('movie_ID')
            ->where('title', "=", $movie_title)
            ->first();

            if ($user_ID == null || $movie_ID == null) {
                return response()->json(['Invalid parameter input'], 400);
            }else{
                
                Rating::create([
                    'movie_ID' => $movie_ID->movie_ID,
                    'rating' => $rating,
                    'user_ID' => $user_ID->user_ID,
                    'r_description' => $r_description
                ]);

                return response()->json(['message'=> "Successfully added review for '$movie_title' by user: $username", 'success'=> true]);
            }
        
    }else {
        return response()->json(['Invalid parameters'], 400);
    }

}
    public function create_movie(Request $request){

        $title = $request->input('title');
        $release = $request->input('release');
        $duration = $request->input('duration');
        $description = $request->input('description');
        $mpaa_rating = $request->input('mpaa_rating');
        $director = $request->input('director');
        $language = $request->input('language');

        $genre = $request->input('genre');
        $genre = join(",", array_filter($genre));
        $performer = $request->input('performer');
        $performer = join(",", array_filter($performer));

        Movie::create([
            'title'=> $title,
            'release'=> $release,
            'durationMinutes'=> $duration,
            'description'=> $description,
            'mpaa_rating'=> $mpaa_rating,
            'director'=> $director,
            'language'=> $language,
            'genre'=> $genre,
            'view_count'=> 0,
            'performer'=> $performer,
            'poster' => null,
        ]);

        return to_route('create_movie');
    }
}