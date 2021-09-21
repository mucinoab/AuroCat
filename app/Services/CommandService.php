<?php

namespace App\Services;

use App\Jobs\FinishGame;
use App\Models\TelegramUser;
use App\Models\Game;
use App\Models\Message;
use App\Models\State;

class CommandService
{
  public $telegramUser;
  public $game;
  public $message;
  public $state;

  public function __construct(TelegramUser $telegramUser, Game $game, Message $message, State $state)
  {
    $this->telegramUser = $telegramUser;
    $this->game = $game;
    $this->message = $message;
    $this->state = $state;
  }

  public function new_game(string $chatId, bool $practice, array $request,string $gameType)
  {
    
    propagate_msj([
      'id'   => $chatId,
      'message'  => $request['message']['text'],
      'transmitter' => 0, // Indicates who sends the message
      'date' => $request['message']['date'],
    ]);
    
    $message = "Preparando tu juego 🎮.\nMarca una casilla ✖️";
    $msg_id = send_msj($message,$chatId);

    propagate_msj([
      'id'   => $chatId,
      'message'  => $message,
      'transmitter' => 1, // Indicates who sends the message
      'date' => $request['message']['date'],
    ]);
    // create a game
    $game = $this->game->createGame($chatId,$request['message']['date'],$practice);
    
    $gato = new Gato(0, 0, $practice, $game->id,$msg_id);

    $board_state = $gato->state_to_json();

    update_keyboard($chatId, $msg_id, $board_state);

    $msg_data = [
      'id'   => $chatId,
      'transmitter' => 1,
      'date' => $request['message']['date'],
      'callback' => [
        'data'          => $gato->game_state(),
        'practice_game' => $practice,
      ],
    ];
    propagate_msj($msg_data);

    $this->command_newGame(
      $chatId,
      $request['message']['date'],
      $request['update_id'],
      $message,
      $board_state,
      $game,
      $gameType
    );
  }

  public function command_start($id, $name,$message)
  {
    $telegramUser = $this->telegramUser->createTelegramUserIfNotExist($id,$name);
    $this->sendMessageToTelegramUser($telegramUser, '/start', 0);
    $this->sendMessageToTelegramUser($telegramUser, $message, 1);
  }

  public function command_newGame($telegram_user_id, $date, $update_id, $message, $board_state,$game,$gameType)
  {
    $this->message->createMessage($game->id, $telegram_user_id, $update_id, $gameType, 0, $date);
    $this->message->createMessage($game->id, $telegram_user_id, $update_id, $message, 1, $date);
    $this->state->createState($game->id, $board_state, 0, 1, $date);

    FinishGame::dispatch($game)->delay(now()->addMinutes(15));
  }

  public function updateState($game_id, $board_state, $transmitter)
  {
    $this->state->updateState($game_id, $board_state, $transmitter);
  }

  public function sendWinnerMessage($id, $message, $winner,Game $game)
  {
    $dateUnix =  time();
    $this->message->createMessage($game->id, $id, $dateUnix, $message, 0, $dateUnix);
    $game->winner = $winner;
    $game->state = 2;
    $game->save();
  }

  public function sendMessage($id, $message, $transmitter)
  {
    $telegram_user = $this->telegramUser->createTelegramUserIfNotExist($id);
    $game = $this->game->getLastGame($telegram_user);
    if ($game == null) return;
    $dateUnix =  time();
    $update_id =  $dateUnix;
    $date =  $dateUnix;
    $this->message->createMessage($game->id, $id, $update_id, $message, $transmitter, $date);
  }

  public function sendMessageToTelegramUser($telegram_user, $message, $transmitter)
  {
    $game = $this->game->getLastGame($telegram_user);
    if ($game == null) return;
    $dateUnix =  time();
    $update_id =  $dateUnix;
    $date =  $dateUnix;
    $this->message->createMessage($game->id, $telegram_user->id, $update_id, $message, $transmitter, $date);
  }


  public function getLastTelegramUserGame($id)
  {
    $actualGame = $this->game->findGame($id);

    if($actualGame->state == 2){
      return null; 
    }
    return $actualGame;
  }
}
