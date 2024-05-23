<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\Theater;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MovieRoom>
 */
class MovieRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $theater_ID = Theater::pluck('theater_ID')->random();
        $movie_ID = Movie::pluck('movie_ID')->random();
        $start_date = $this->faker->dateTimeBetween('-3 months', '+3 months')->format('Y-m-d H:i:s');
        $end_date = $this->faker->dateTimeBetween($start_date.'+1 hour', $start_date."+3 hour");
        return [
            'start_time'=> $start_date,
            'end_time'=> $end_date,
            'theater_ID'=> $theater_ID,
            'movie_ID'=> $movie_ID,
            'theater_room_no'=> fake()-> numberBetween(1, 15)
        ];
    }
}
