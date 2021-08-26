<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TelegramUserController extends Controller
{
   
    //return all the chats that are palying with an agent or bot
    public function index()
    {
        $agentGames = [];
        $games = Game::with(['telegramUser:id,name','message:game_id,date,transmitter,message','state:game_id,date,transmitter'])->where('state','!=',2)->orderByDesc('date')->get(['id','telegram_user_id']);
        

        foreach ($games as $game) {
            $stateDate = 0;
            $stateMessage = 0;    

            if($game->state != null){ 
                $stateDate = $game->state->date;
                $game->state->message = $game->state->transmitter == 0 ? 'Esperando movimiento' : 'Responder Jugada';
            }
            if($game->message != null){
                $stateMessage = $game->message->date;
            }

            array_push(
                $agentGames,
                [
                    "id" => $game->telegram_user_id,
                    "name" => $game->telegramUser->name,
                    "time" => $game[$stateDate>$stateMessage ? 'state': 'message']->date,
                    "lastMessage" => $game[ $stateDate>$stateMessage ? 'state': 'message']->message
                ]
            );
        }

        return response()->json(["chats" => $agentGames]);
    }

    //return the messages from a chat_id with optional delimitation parameters
    // ej: APP_URL/conversation?chat_id=1728265258&chats_number=5&offset=2
    public function conversation(Request $request)
    {
        $conversation = Message::query();
        $conversation->where('chat_id','=',$request->chat_id);

        if($request->chats_number)
        {
            $conversation->take($request->chats_number);
        }

        if($request->offset)
        {
            $conversation->skip($request->offset);
        }

        $conversation->orderby('date','desc');
        
        return response()->json(["conversation" => $conversation->get()]);

    }
}
