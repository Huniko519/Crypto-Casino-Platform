<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGameSlotsTableAddBetAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_slots', function (Blueprint $table) {
            $table->decimal('bet_amount', 10, 2)->after('lines_bet');
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
            $table->dropColumn('bet_amount');
        });
    }
}
