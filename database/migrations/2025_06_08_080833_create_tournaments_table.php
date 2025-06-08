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
Schema::create('tournaments', function (Blueprint $table) {
    $table->id('tournament_id');
    $table->string('name');
    $table->string('slug')->unique();
    $table->enum('format', ['single_elimination', 'double_elimination', 'round_robin']);
    $table->integer('team_count');
    $table->dateTime('registration_open');
    $table->dateTime('registration_close');
    $table->dateTime('start_date');
    $table->text('rules')->nullable();
    $table->enum('status', ['upcoming', 'registration', 'ongoing', 'completed']);
    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
