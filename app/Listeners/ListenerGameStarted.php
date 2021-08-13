<?php

namespace App\Listeners;

use App\Events\GameStarted;
use App\Models\Message;
use App\Models\Game;
use App\Models\State;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ListenerGameStarted
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
     * @param  GameStarted  $event
     * @return void
     */
    public function handle(GameStarted $event)
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

        //IF the record does not exist, i create a new one
        //ELSE change changed the state of the record and saved it.After create a new one
        if($game == null){
            $game = Game::create([
                'telegram_user_id' => $event->id,
                'date' => $event->date
            ]);
        }else{
            $game->state = 2;
            $game->save();
            $game = Game::create([
                'telegram_user_id' => $event->id,
                'date' => $event->date
            ]);
        }



       /**
        * sended the /start message
        * -- transmitter
        *       0 => agente/bot
        *       1 => telegram_user
        *  */
       Message::create([
           'game_id' => $game->id,
           'chat_id' => $event->id,
           'update_id' => $event->update_id,
           'message' => $event->message,
           'transmitter' => 1,
           'date' => $event->date
       ]);

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
