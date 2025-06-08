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
// database/migrations/YYYY_MM_DD_create_games_table.php
Schema::create('games', function (Blueprint $table) {
    $table->id('game_id');
    $table->foreignId('series_id')->constrained('series', 'series_id');
    $table->integer('team1_score');
    $table->integer('team2_score');
    $table->string('map');
    $table->integer('duration_seconds');
    $table->json('stats')->nullable(); // Detailed game stats
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
