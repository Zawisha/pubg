<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKillsToGameUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_user', function (Blueprint $table) {
            $table->unsignedTinyInteger('kills')->default(0);
            $table->boolean('visit')->default(false);
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
            $table->dropColumn('kills');
            $table->dropColumn('visit');
        });
    }
}
