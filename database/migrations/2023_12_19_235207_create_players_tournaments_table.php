<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_1_id');
            $table->unsignedBigInteger('player_2_id');
            $table->unsignedBigInteger('player_win_id')->nullable();
            $table->unsignedBigInteger('tournament_id');
            $table->string('resultado')->nullable();
            $table->timestamps();

            $table->foreign('player_1_id')->references('id')->on('players');
            $table->foreign('player_2_id')->references('id')->on('players');
            $table->foreign('player_win_id')->references('id')->on('players');
            $table->foreign('tournament_id')->references('id')->on('tournaments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players_tournaments');
    }
}
