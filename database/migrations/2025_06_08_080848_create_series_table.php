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
// database/migrations/YYYY_MM_DD_create_series_table.php
Schema::create('series', function (Blueprint $table) {
    $table->id('series_id');
    $table->foreignId('match_id')->constrained('matches', 'match_id');
    $table->integer('games_required'); // Best of 3, 5, etc.
    $table->integer('team1_wins')->default(0);
    $table->integer('team2_wins')->default(0);
    $table->enum('status', ['pending', 'ongoing', 'completed']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
