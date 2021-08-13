<?php

namespace App\Listeners;

use App\Events\MessageSended;
use App\Models\Game;
use App\Models\Message;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ListenerMessageSended
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
     * @param  MessageSended  $event
     * @return void
     */
    public function handle(MessageSended $event)
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

        //Get the unix time
        $dateUnix =  time();

        /**
         * sended the /start message
         * -- transmitter
         *       0 => agente/bot
         *       1 => telegram_user
         *  */
        Message::create([
            'game_id' => $game->id,
            'chat_id' => $event->id,
            'update_id' => $dateUnix,
            'message' => $event->message,
            'transmitter' => 0,
            'date' => $dateUnix
        ]);


        //changes to final state and set the winner
        $game->state = 2;
        $game->winner = $event->winner;
        $game->save();
    }
}
