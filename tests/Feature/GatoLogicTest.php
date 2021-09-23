<?php

namespace Tests\Feature;

use App\Services\Gato;
use App\Services\GatoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GatoLogicTest extends TestCase
{
    public function testCreateAnAgentGame()
    {
        $practice = false;
        $board_id = "123456789";
        $message_id = "123456789";

        $gato = new Gato(0, 0, $practice, $board_id, $message_id);

        $status = $gato->status();

        $this->assertEquals(3,$status);
    }
    
    public function testValidateUserMovent(){
        $practice = false;
        $board_id = "123456789";
        $message_id = "123456789";
        $move_by = true;

        $gato = new Gato(0, 0, $practice, $board_id, $message_id);

        $board_state = $gato->state_to_json();
        $move = $movement = explode(',',$board_state[0][0]['callback_data'])[6];

        $result = $gato->move(0,$move_by);

        $board_state = $gato->state_to_json();
        $gato->turn = 'user';
        $movement = explode(',',$board_state[0][0]['callback_data'])[0];

        $this->assertEquals(false,$move_by == True && $move == 'user' && !$practice_game);
        $this->assertEquals(true, $result);
        $this->assertEquals('X', $movement);
    }

    public function testValidateAgentMovent(){
        $practice = false;
        $board_id = "123456789";
        $message_id = "123456789";
        $move_by = false;

        $gato = new Gato(0, 0, $practice, $board_id, $message_id);
        $gato->turn = 'user';

        $board_state = $gato->state_to_json();
        $move = $movement = explode(',',$board_state[0][0]['callback_data'])[6];

        $result = $gato->move(0,$move_by);

        $gato->turn = 'user';
        $board_state = $gato->state_to_json();
        $movement = explode(',',$board_state[0][0]['callback_data'])[0];

        $this->assertEquals(false,$move_by == False && $move == 'agent');
        $this->assertEquals(true, $result);
        $this->assertEquals('O', $movement);
    }

    public function testUserCantThrowTwice(){
        $practice = false;
        $board_id = "123456789";
        $message_id = "123456789";
        $move_by = true;

        $gato = new Gato(0, 0, $practice, $board_id, $message_id);

        $board_state = $gato->state_to_json();
        $move = $movement = explode(',',$board_state[0][0]['callback_data'])[6];

        $gato->move(0,$move_by);

        $gato->turn = 'user';
        $board_state = $gato->state_to_json();
        
        $move = $movement = explode(',',$board_state[0][0]['callback_data'])[6];
        $gato->move(1,$move_by);

        $this->assertEquals(true,$move_by == True && $move == 'user' && !$practice);
    }

    public function testAgentCantThrowTwice(){
        $practice = false;
        $board_id = "123456789";
        $message_id = "123456789";
        $move_by = false;

        $gato = new Gato(0, 0, $practice, $board_id, $message_id);

        $gato->turn = 'user';
        $board_state = $gato->state_to_json();
        $move = $movement = explode(',',$board_state[0][0]['callback_data'])[6];

        $gato->move(0,$move_by);

        $gato->turn = 'agent';
        $board_state = $gato->state_to_json();
        
        $move = $movement = explode(',',$board_state[0][0]['callback_data'])[6];
        $gato->move(1,$move_by);

        $this->assertEquals(true,$move_by == False && $move == 'agent');
    }
}
