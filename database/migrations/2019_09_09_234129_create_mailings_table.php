<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('message')->nullable();
            $table->text('short_message')->nullable();
            $table->text('image')->nullable();
            $table->boolean('to_email')->default(false);
            $table->boolean('to_bot')->default(false);
            $table->boolean('to_lk')->default(false);
            $table->timestamp('mailed_at')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
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
        Schema::dropIfExists('mailings');
    }
}
