<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    protected $fillable = [
        'name',
        'telegram_user_id',
        'state',
        'winner',
        'opponent',
        'date'
    ];

    public function getLastGame($id)
    {
        /**
         * The following query gets the record from the games table that contains
         * the telegram_user_id and its status is differet from finished(2).
         * There should be only one record
         * -- state
         *      0 => bot
         *      1 => agente
         *      2 => finalizado
         */

        return Game::where('telegram_user_id', '=', $id)->where('state', '!=', 2)->first();
    }
    
    public function changeGameStateToFinaled($game)
    {
        $this->changeGameState($game,2);
    }

    public function changeGameStateToFinaledWithWinner($game,$winner)
    {
        $game->state = 2;
        $game->winner = $winner;
        $game->save();
    }


    public function changeGameState($game,$state)
    {
        //changed the state of the record and saved it
        $game->state = $state;
        $game->save();
    }

    public function createGame($id,$date)
    {
        return Game::create([
            'telegram_user_id' => $id,
            'date' => $date
          ]);
    }




}
