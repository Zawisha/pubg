<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailingUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailing_user', function (Blueprint $table) {
            $table->integer('mailing_id')->unsigned()->index();
//            $table->foreign('mailing_id')->references('id')->on('mailing')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
//            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->primary(['mailing_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mailing_user');
    }
}
