<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTableModifyReferrerFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('referrer_game_bet_pct');
            $table->decimal('referrer_game_loss_pct', 8, 2)->nullable()->unsigned()->after('referrer_sign_up_credits');
            $table->decimal('referrer_game_win_pct', 8, 2)->nullable()->unsigned()->after('referrer_game_loss_pct');
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
            $table->dropColumn('referrer_game_loss_pct');
            $table->dropColumn('referrer_game_win_pct');
            $table->decimal('referrer_game_bet_pct', 8, 2)->nullable()->unsigned()->after('referrer_sign_up_credits');
        });
    }
}
