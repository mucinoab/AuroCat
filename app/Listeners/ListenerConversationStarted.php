<?php

namespace App\Listeners;

use App\Events\ConversationStarted;
use App\Models\Game;
use App\Models\Message;
use App\Models\TelegramUser;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ListenerConversationStarted
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
     * @param  ConversationStarted  $event
     * @return void
     */
    public function handle(ConversationStarted $event)
    {

        /**
         * firstOr Create
         * The 'firstOrCreate method tries to find a model matching the attributes you pass
         * in the first parameter. If a model is not found, it automatically creates and saves a new
         * a new Model after applying any attributes passed in the second parameter
         */

         TelegramUser::firstOrCreate(
             ['id' => $event->id],
             ['name' => $event->name]
         );

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
    }
}
