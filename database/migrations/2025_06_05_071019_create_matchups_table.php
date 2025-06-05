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
Schema::create('matchups', function (Blueprint $table) {
    $table->id('match_id');
    $table->unsignedBigInteger('teamA');
    $table->unsignedBigInteger('teamB');
    $table->integer('teamA_score')->default(0);
    $table->integer('teamB_score')->default(0);
    $table->string('teamA_logo')->nullable();
    $table->string('teamB_logo')->nullable();
    $table->timestamps();

    $table->foreign('teamA')->references('team_id')->on('teams')->onDelete('cascade');
    $table->foreign('teamB')->references('team_id')->on('teams')->onDelete('cascade');
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matchups');
    }
};
