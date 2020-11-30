<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            // columns
            $table->increments('id');
            $table->integer('account_id')->unsigned();
            $table->morphs('gameable', 'game_morph'); // polymorphic relation (index is created automatically)
            $table->decimal('bet', 16, 2);
            $table->decimal('win', 16, 2);
            $table->string('result', 100)->nullable();
            $table->timestamps();
            // foreign keys
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
        Schema::dropIfExists('games');
    }
}
