<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'release' => fake()->date(),
            'title' => fake()->realText(30),
            'genre' => fake()-> randomElement(['action', 'comedy', 'horror', 'adventure']),
            'poster' => fake()-> url(),
            'durationMinutes' =>fake()-> numberBetween(90, 180),
            'view_count' =>fake()->randomNumber(),
            'mpaa_rating' =>fake()-> randomElement(['G', 'PG', 'PG-13', 'R', 'NC-17', 'X', 'GP', 'M', 'M/PG']),
            'director' =>fake()-> name(),
            'performer' =>fake()-> name(),
            'language' =>fake()-> randomElement(['English', 'Chinese', 'Malay', 'Tamil']),
            'description' =>fake()-> realText()
            
        ];
    }
}
