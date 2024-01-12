<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPlayer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->integer('reaction_time')->comment('1 is the best')->change();
            $table->integer('strengh')->comment('100 is te best')->change();
            // ...
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
            $table->integer('reaction_time')->comment('')->change();
            $table->integer('strengh')->comment('')->change();
            // ...
        });
    }
}
