<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id()->unique();
            $table->foreignId('telegram_user_id')->nullable()->index();
            $table->tinyInteger('state')->default(0);
            $table->tinyInteger('winner')->nullable();
            $table->tinyInteger('opponent')->default(0);
            $table->unsignedInteger('date');
            $table->timestamps();
            $table->index(['opponent','winner']);
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
