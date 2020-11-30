<?php

use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Bonus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReferralBonusesTableAddAccountIdForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // update all account transactions and replace the class name from ReferralBonus to Bonus
        AccountTransaction::where('transactionable_type', 'App\Models\ReferralBonus')->update(['transactionable_type' => Bonus::class]);

        // drop the foreign key on users table first (otherwise it won't be possible to drop it after renaming the table)
        Schema::table('referral_bonuses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // rename the table
        Schema::rename('referral_bonuses', 'bonuses');

        // add account_id column
        Schema::table('bonuses', function (Blueprint $table) {
            $table->integer('account_id')->unsigned()->after('id');
        });

        // update account_id from attached user relationship
        Bonus::all()->each(function($bonus) {
            $account_id = Account::where('user_id', $bonus->user_id)->value('id');
            if ($account_id) {
                $bonus->account_id = Account::where('user_id', $bonus->user_id)->value('id');
                $bonus->save();
            }
        });

        // Drop user_id column and create a new foreign key on accounts table
        Schema::table('bonuses', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // there is no way back
    }
}
