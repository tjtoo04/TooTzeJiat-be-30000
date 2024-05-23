<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Views>
 */
class ViewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $movie_ID = Movie::pluck('movie_ID')->random();
        return [
            'movie_ID'=> $movie_ID,
            'view_count'=> fake()-> randomNumber()            
        ];
    }
}
