<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('notifications');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBIgInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('message')->nullable();
            $table->boolean('read')->default(false);
            $table->timestamps();
        });
    }
}
