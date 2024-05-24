<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id('movie_ID');
            $table->date('release');
            $table->string('title');
            $table->string('genre');
            $table->string('poster');
            $table->integer('durationMinutes');
            $table->integer('view_count');
            $table->enum('mpaa_rating', ['G', 'PG', 'PG-13', 'R', 'NC-17', 'X', 'GP', 'M', 'M/PG']);
            $table->string('director');
            $table->string('performer');
            $table->string('language');
            $table->string('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
