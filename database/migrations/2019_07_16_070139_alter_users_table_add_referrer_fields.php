<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTableAddReferrerFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('referee_sign_up_credits')->nullable()->unsigned()->after('totp_secret');
            $table->integer('referrer_sign_up_credits')->nullable()->unsigned()->after('referee_sign_up_credits');
            $table->decimal('referrer_game_bet_pct', 8, 2)->nullable()->unsigned()->after('referrer_sign_up_credits');
            $table->decimal('referrer_deposit_pct', 8, 2)->nullable()->unsigned()->after('referrer_game_bet_pct');
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
            $table->dropColumn('referee_sign_up_credits');
            $table->dropColumn('referrer_sign_up_credits');
            $table->dropColumn('referrer_deposit_pct');
            $table->dropColumn('referrer_game_bet_pct');
        });
    }
}
