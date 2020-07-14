<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminAuthHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_auth_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('admin_id');
            $table->string('ip', 20)->nullable();
            $table->text('agent')->nullable();
            $table->timestamp('stamp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_auth_history');
    }
}
