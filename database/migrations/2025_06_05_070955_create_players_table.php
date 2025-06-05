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
Schema::create('players', function (Blueprint $table) {
    $table->id('player_id');
    $table->string('player_ign');
    $table->string('player_role');
    $table->string('player_photo')->nullable();
    $table->unsignedBigInteger('team_id');
    $table->enum('status', ['starter', 'bench']);
    $table->timestamps();

    $table->foreign('team_id')->references('team_id')->on('teams')->onDelete('cascade');
});
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
