<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKillsToGameUser2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_user', function (Blueprint $table) {
            $table->unsignedInteger('kills1')->nullable();
            $table->unsignedInteger('kills2')->nullable();
            $table->unsignedInteger('kills3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_user', function (Blueprint $table) {
            $table->dropColumn('kills1');
            $table->dropColumn('kills2');
            $table->dropColumn('kills3');
        });
    }
}
