<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Message;
use App\Models\State;
use App\Models\TelegramUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TelegramUserController extends Controller
{
   
    //return all the chats that are palying with an agent or bot
    public function index()
    {
        $agentGames = [];
        $games = TelegramUser::get(['id','name']);
        foreach ($games as $game) {
            $message = Message::where('chat_id',$game->id)->orderBy('date','DESC')->first();
            array_push(
                $agentGames,
                [
                    "id" => $game->id,
                    "name" => $game->name,
                    "date" => $message->date,
                    "lastMessage" => $message->message
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

     //return all the chats that are palying with an agent or bot
     public function lastGame(Request $request)
     {
         $game = Game::where('telegram_user_id',$request->chat_id)->orderBy('date','DESC')->first();
         $state = State::where('game_id',$game->id)->first();

         

 
         return response()->json(["lastGame" => $state,"game"=>$game]);
     }
}
