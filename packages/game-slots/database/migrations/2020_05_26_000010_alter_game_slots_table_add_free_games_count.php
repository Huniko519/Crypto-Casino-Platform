<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGameSlotsTableAddFreeGamesCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_slots', function (Blueprint $table) {
            $table->integer('free_games_count')->after('scatters_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_slots', function (Blueprint $table) {
            $table->dropColumn('free_games_count');
        });
    }
}
