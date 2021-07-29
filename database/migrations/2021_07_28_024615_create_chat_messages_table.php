<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('chat_id');
            $table->unsignedBigInteger('message_id');
            
            $table->timestamps();

            // FK
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');

            // softDeletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
}
