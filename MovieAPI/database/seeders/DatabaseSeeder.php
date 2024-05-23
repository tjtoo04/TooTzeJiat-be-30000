<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\MovieRoom;
use App\Models\Rating;
use App\Models\Theater;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Views;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Movie::factory(10)->create();
        Rating::factory(10)->create();
        Views::factory(10)->create();
        Theater::factory(10)->create();
        MovieRoom::factory(10)->create();


    }
}
