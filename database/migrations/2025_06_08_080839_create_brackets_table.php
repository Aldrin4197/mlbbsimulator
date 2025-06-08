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
Schema::create('brackets', function (Blueprint $table) {
    $table->id('bracket_id');
    $table->foreignId('tournament_id')->constrained('tournaments', 'tournament_id');
    $table->enum('type', ['upper', 'lower', 'final'])->default('upper');
    $table->integer('round');
    $table->string('name'); // e.g., "Quarter Finals", "Semi Finals"
    $table->boolean('is_consolation')->default(false);
    $table->timestamps();
    
    $table->index(['tournament_id', 'type', 'round']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brackets');
    }
};
