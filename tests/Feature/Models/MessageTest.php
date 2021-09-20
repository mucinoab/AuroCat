<?php

namespace Tests\Feature\Models;

use App\Models\Game;
use App\Models\Message;
use App\Models\TelegramUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageTest extends TestCase
{

    /** @test */
    public function createMessage()
    {
        $message = new Message;

        $game = Game::factory()->create();
        $telegramUser = TelegramUser::factory()->create();
        $date = time();
        $text = "Hello world";
        $transmitter = rand(0,1);

        $message->createMessage($game->id,$telegramUser->id,$date,$text,$transmitter,$date);

        $this->assertDatabaseHas('messages', [
            'game_id' => $game->id,
            'chat_id'=> $telegramUser->id,
            'message' => $text
        ]);
    }
}
