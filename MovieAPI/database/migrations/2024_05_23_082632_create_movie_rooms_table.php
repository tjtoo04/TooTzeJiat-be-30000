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
        Schema::create('movie_rooms', function (Blueprint $table) {
            $table->id('movie_room_ID');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->foreignId('theater_ID')->references('theater_ID')->on('theaters');
            $table->foreignId('movie_ID')->references('movie_ID')->on('movies');
            $table->integer('theater_room_no');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_rooms');
    }
};
