<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePlayersTablev2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->integer('strengh')->after('skill')->nullable();
            $table->integer('reaction_time')->after('skill')->nullable();
            $table->integer('travel_speed')->after('skill')->nullable();
            $table->integer('good_look')->after('skill');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['strengh', 'reaction_time', 'travel_speed','good_look']);
        });
    }
}
