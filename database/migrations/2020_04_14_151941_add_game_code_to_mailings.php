<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGameCodeToMailings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mailings', function (Blueprint $table) {
            $table->unsignedTinyInteger('game_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mailings', function (Blueprint $table) {
            $table->dropColumn('game_code');
        });
    }
}
