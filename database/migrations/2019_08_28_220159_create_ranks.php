<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->text('requirements')->nullable();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('rq_battles')->default(0);
            $table->unsignedTinyInteger('rq_kills')->default(0);
            $table->unsignedTinyInteger('cashback')->default(0);
            $table->unsignedTinyInteger('kill_reward')->default(0);
            $table->text('image')->nullable();
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
        Schema::drop('ranks');
    }
}
