<?php

namespace Tests\Feature\Models;

use App\Models\Game;
use App\Models\TelegramUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameTest extends TestCase
{
    /** @test */
    public function gameCanBeCreate()
    {
        $game = Game::factory()->create();

        $this->assertDatabaseHas('games',[
            'telegram_user_id' => $game->telegram_user_id,
            'state' => $game->state,
            'winner' => $game->winner,
            'opponent' => $game->opponent,
            'date' => $game->date
        ]);	
    }

    /** @test */
    public function getLastGame()
    {
        $telegramUser = TelegramUser::factory()->create();

        Game::factory()->count(5)->create([
            'telegram_user_id' => $telegramUser->id,
            'date' => time()
        ]);

        $lastGame = Game::factory()->create([
            'telegram_user_id' => $telegramUser->id,
            'date' => time() + 100
        ]);

        $game = $lastGame->getLastGame($telegramUser);

        $this->assertEquals($lastGame->id, $game->id);
    }

    /** @test */
    public function changeGameStateToFinaled()
    {
        $game = Game::factory()->create();

        $game->changeGameStateToFinaled($game);

        $this->assertEquals($game->state,2);
    }

    /** @test */
    public function changeGameStateToFinaledWithWinner()
    {
        $game = Game::factory()->create();

        $game->changeGameStateToFinaledWithWinner($game,rand(0,1));

        $this->assertNotNull($game->winner);

        $this->assertEquals($game->state,2);
    }

    /** @test */
    public function changeGameState()
    {
        $state = rand(0,1);

        $game = Game::factory()->create();

        $game->changeGameState($game,$state);

        $this->assertEquals($game->state,$state);
    }

    /** @test */
    public function findGame()
    {
        $game = Game::factory()->create();

        $gameFound = $game->findGame($game->id);

        $this->assertEquals($game->id,$gameFound->id);
    }
}
