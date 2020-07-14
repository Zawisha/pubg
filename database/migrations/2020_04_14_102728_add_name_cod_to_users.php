<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameCodToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('pubg_id')->nullable()->change();
            $table->string('name_cod')->nullable();
            $table->string('name_freefire')->nullable();
            $table->string('cod_id')->nullable();
            $table->string('freefire_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name_cod');
            $table->dropColumn('name_freefire');
            $table->dropColumn('cod_id');
            $table->dropColumn('freefire_id');
        });
    }
}
