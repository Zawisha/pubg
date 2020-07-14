<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_user', function (Blueprint $table) {
            $table->integer('game_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->index('game_id', 'games_idx');
            $table->index('user_id', 'users_idx');
            $table->primary(['game_id', 'user_id'], 'game_user_idx');
            $table->foreign('game_id', 'games_frn')
                ->references('id')
                ->on('games')
                ->onDelete('cascade');
            $table->foreign('user_id', 'users_frn')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('game_user');
    }
}
