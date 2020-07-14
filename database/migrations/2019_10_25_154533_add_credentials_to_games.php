<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCredentialsToGames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->string('login2')->nullable();
            $table->string('password2')->nullable();
            $table->string('login3')->nullable();
            $table->string('password3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('login2');
            $table->dropColumn('password2');
            $table->dropColumn('login3');
            $table->dropColumn('password3');
        });
    }
}
