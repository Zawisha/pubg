<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('pubg_id')->nullable();
            $table->unsignedInteger('games')->default(0);
            $table->unsignedInteger('kills')->default(0);
            $table->double('balance')->default(0);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedTinyInteger('rank_id');
            $table->boolean('active')->default(true);
            $table->rememberToken();
            $table->timestamps();

            $table->unique('pubg_id');
            $table->index('active');
            $table->index('rank_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
