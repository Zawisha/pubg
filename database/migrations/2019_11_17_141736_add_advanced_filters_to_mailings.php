<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdvancedFiltersToMailings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mailings', function (Blueprint $table) {
            $table->unsignedInteger('min_balance')->default(0);
            $table->unsignedTinyInteger('membership_type')->default(0);
//            $table->text('games')->nullable();
            $table->timestamp('created_from')->nullable();
            $table->timestamp('created_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mailings', function (Blueprint $table) {
            $table->dropColumn('min_balance');
            $table->dropColumn('membership_type');
//            $table->dropColumn('games');
            $table->dropColumn('created_from');
            $table->dropColumn('created_to');
        });
    }
}
