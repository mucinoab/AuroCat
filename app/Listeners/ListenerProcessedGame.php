<?php

namespace App\Listeners;

use App\Events\ProcessedGame;
use App\Models\Game;
use App\Models\State;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ListenerProcessedGame
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProcessedGame  $event
     * @return void
     */
    public function handle(ProcessedGame $event)
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
        $game = Game::where('telegram_user_id', '=', $event->id)->where('state', '!=',2)->first();

        /**
        * Created the first state of the game
        * -- turn = identify the players' turn
        *      0 => agente
        *      1 =>  telegram_user
        * */
        State::create([
            'game_id' => $game->id,
            'board_state' => json_encode($event->board_state),
            'transmitter' => 0,
            'turn' => 1,
            'date' => $event->date
           ]);
    }
}
