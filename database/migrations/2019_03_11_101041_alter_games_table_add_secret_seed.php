<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGamesTableAddSecretSeed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->string('secret', 300)->after('status'); // initial state of the related game, i.e. reels position, shuffled deck of cards
            $table->string('server_seed', 32)->after('secret'); // server seed
            $table->string('client_seed', 32)->after('server_seed')->nullable(); // client seed
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
            $table->dropColumn(['secret', 'server_seed', 'client_seed']);
        });
    }
}
