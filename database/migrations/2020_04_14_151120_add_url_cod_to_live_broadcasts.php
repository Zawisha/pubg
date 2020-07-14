<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrlCodToLiveBroadcasts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('live_broadcasts', function (Blueprint $table) {
            $table->text('url_cod')->nullable();
            $table->text('url_freefire')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('live_broadcasts', function (Blueprint $table) {
            $table->dropColumn('url_cod');
            $table->dropColumn('url_freefire');
        });
    }
}
