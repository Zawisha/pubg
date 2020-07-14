<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKillReward2ToRanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ranks', function (Blueprint $table) {
            $table->float('kill_reward2')->default(0);
            $table->float('kill_reward')->change();
        });

        \Illuminate\Support\Facades\DB::table('ranks')
            ->update(['kill_reward2' => DB::raw('kill_reward')]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ranks', function (Blueprint $table) {
            $table->dropColumn('kill_reward2');
            $table->unsignedTinyInteger('kill_reward')->change();
        });
    }
}
