<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Message;
use App\Models\State;
use App\Models\TelegramUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TelegramUserController extends Controller
{
  // Cache time to live in seconds
  const CACHE_TTL = 5;

  // Returns all the chats that are playing with an agent or bot
  public function index()
  {
    $data = array();

    $users = TelegramUser::with(['game','game.stateRelation','game.message'])->get()->sortByDesc('game.date')->when(request('chats_number'), function($query) {
      return $query->take(request('chats_number'));
    })->when(request('offset'), function($query) {
      return $query->skip(request('offset'));
    });

    foreach($users as $user) {
      $values = array(
        'chats'=>array(
          'id'=>          $user->id,
          'name'=>        $user->name,
          'lastMessage'=> $user->game->message->message,
          'date'=>        $user->game->message->date,
          'opponent'=>    $user->game->opponent,
          'state'=>       $user->game->state,
          'gameId'=>      $user->game->id,
          'turn'=>        $user->game->stateRelation->turn,
          'unread'=>      0
        ),
      );

      array_push($data, $values);
    }

    return response()->json(['data' => $data]);
  }

  //return the messages from a chat_id with optional delimitation parameters
  public function conversation()
  {
    // http://localhost:8000/conversation?chats_number=10&offset=1&chats=[id,id,...]
    $messages = array();
    if(request('chats'))
    {
      $ids = json_decode(request('chats'));
      foreach($ids as $id){
        $userMessages = Message::where('chat_id',$id)->orderBy('date','DESC')->when(request('chats_number'),function($query){
          return $query->take(request('chats_number'));
        })->when(request('offset'),function($query){
          return $query->skip(request('offset'));
        })->get(['chat_id','message','transmitter','date']);

        array_push($messages,array("chat_id"=>$id,"messages"=>$userMessages));
      }
    }else{
      // http://localhost:8000/conversation?chat_id=1728265258&chats_number=10&offset=1
      $userMessages = Message::where('chat_id',request('chat_id'))->orderBy('date','DESC')->when(request('chats_number'),function($query){
        return $query->take(request('chats_number'));
      })->when(request('offset'),function($query){
        return $query->skip(request('offset'));
      })->get(['message','transmitter','date']);
      array_push($messages,array("chat_id"=>request('chat_id'),"messages"=>$userMessages));
    }
    return response()->json(["messages" => $messages]);
  }

  public function game(){
    $game = Game::find(request('game_id'));
    $game->stateRelation;
    if($game->state == 2){
      $game->stateRelation->turn = 2;
    }
    return response()->json(["game" => $game]);
  }

  public function lastGame(){
    $game = Game::where('telegram_user_id',request('chat_id'))->orderBy('date','DESC')->first();
    $game->stateRelation;
    if($game->state == 2){
      $game->stateRelation->turn = 2;
    }
    return response()->json(["game" => $game]);
  }

}
