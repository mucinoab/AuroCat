<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    protected $fillable = [
        'game_id',
        'board_state',
        'transmitter',
        'turn',
        'date'
    ];

    public function createState($game_id,$board_state,$transmitter,$turn,$date){
        State::create([
            'game_id' => $game_id,
            'board_state' => json_encode($board_state),
            'transmitter' => $transmitter,
            'turn' => $turn,
            'date' => $date
        ]);
    }

    public function getAState($game_id)
    {
        return State::where('game_id',$game_id)->first();
    }

    public function updateState($game_id,$board_state)
    {
        $state = $this->getAState($game_id);
        $state->board_state = json_encode($board_state);
        $state->save();
    }
}
