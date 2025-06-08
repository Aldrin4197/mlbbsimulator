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
// database/migrations/YYYY_MM_DD_create_matches_table.php
Schema::create('matches', function (Blueprint $table) {
    $table->id('match_id');
    $table->foreignId('bracket_id')->constrained('brackets', 'bracket_id');
    $table->foreignId('team1_id')->nullable()->constrained('teams', 'team_id');
    $table->foreignId('team2_id')->nullable()->constrained('teams', 'team_id');
    $table->integer('team1_score')->nullable();
    $table->integer('team2_score')->nullable();
    $table->enum('status', ['pending', 'ready', 'ongoing', 'completed', 'canceled']);
    $table->dateTime('scheduled_time')->nullable();
    $table->integer('next_match_id')->nullable();
    $table->enum('next_match_slot', ['team1', 'team2'])->nullable();
    $table->integer('round')->default(1);
    $table->integer('position'); // Position in bracket visualization
    $table->json('meta')->nullable(); // For any additional data
    $table->timestamps();
    
    $table->index(['bracket_id', 'status']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
