<?php

namespace Tests\Unit;

use App\Models\State;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StateTest extends TestCase
{
    
    /** @test */
    public function AStateCanBeCreated()
    {
        $state = new State;

        $game = Game::factory()->create();
        $date = time();
        $board_state = rand(0,2);
        $transmitter = rand(0,1);
        $turn = rand(0,1);

        $state->createState($game->id,$board_state,$transmitter,$turn,$date);
        $state = $state->getAState($game->id);

        $this->assertDatabaseHas('states', [
            'game_id' => $game->id,
            'board_state' => $state->board_state,
            'transmitter' => $state->transmitter,
            'turn' => $state->turn,
            'date' => $state->date
        ]);

    }

    /** @test */
    public function WeCanGetAState()
    {
        $state = new State;

        $game = Game::factory()->create();
        $date = time();
        $board_state = rand(0,2);
        $transmitter = rand(0,1);
        $turn = rand(0,1);

        $state->createState($game->id,$board_state,$transmitter,$turn,$date);
        $response = $state->getAState($game->id);

        $this->assertJson($response);

    }
    
    /** @test */
    public function WeCanUpdateAState()
    {
        $state = new State;

        $game = Game::factory()->create();
        $date = time();
        $board_state = 0;
        $transmitter = 1;
        $turn = 1;
        $state->createState($game->id,$board_state,$transmitter,$turn,$date);

        $board_state = 1;
        $transmitter = 0;
        $state->updateState($game->id,$board_state,$transmitter);
        $state = $state->getAState($game->id);

        $this->assertEquals(!$transmitter,$state->turn);
        $this->assertEquals(1,$state->board_state);
        $this->assertEquals(0,$state->transmitter);
    }

}