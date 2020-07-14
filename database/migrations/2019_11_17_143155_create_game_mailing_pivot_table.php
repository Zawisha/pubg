<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameMailingPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_mailing', function (Blueprint $table) {
            $table->integer('game_id')->unsigned()->index();
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->integer('mailing_id')->unsigned()->index();
            $table->foreign('mailing_id')->references('id')->on('mailings')->onDelete('cascade');
            $table->primary(['game_id', 'mailing_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_mailing');
    }
}
