<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlannedAt2ToGames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->timestamp('planned_at2')->nullable();
            $table->timestamp('planned_at3')->nullable();
            $table->boolean('results_published2')->default(false);
            $table->boolean('results_published3')->default(false);
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
            $table->dropColumn('planned_at2');
            $table->dropColumn('planned_at3');
            $table->dropColumn('results_published2');
            $table->dropColumn('results_published3');
        });
    }
}
