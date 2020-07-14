<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTop2PrizeToGames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->double('top2_prize')->default(0);
            $table->double('top3_prize')->default(0);
//            $table->double('top1_prize')->change();
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
            $table->dropColumn('top2_prize');
            $table->dropColumn('top3_prize');
//            $table->unsignedInteger('top1_prize')->change();
        });
    }
}
