<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamp('planned_at');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->double('price');
            $table->string('login')->nullable();
            $table->string('password')->nullable();
            $table->text('image')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('type')->default(0);
            $table->text('comment')->nullable();
            $table->string('map_name')->nullable();
            $table->unsignedTinyInteger('max_players')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('games');
    }
}
