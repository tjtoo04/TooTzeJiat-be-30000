<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $movie_ID = Movie::pluck('movie_ID')->random();
        $user_ID = User::pluck('user_ID')->random();
        return [
            'movie_ID' => $movie_ID,
            'user_ID'=> $user_ID,
            'rating' => fake()-> numberBetween(0, 10),
            'r_description'=> fake()-> realText()
        ];
    }
}
