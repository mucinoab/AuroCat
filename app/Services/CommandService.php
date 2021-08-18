<?php

namespace App\Services;

use App\Models\TelegramUser;
use App\Models\Game;
use App\Models\Message;
use App\Models\State;
use Pusher\Pusher;

require_once "TelegramService.php";

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

  public function handleMessage($request)
  {
    $text = trim($request['message']['text']);

    // Handles commands "/function"
    switch ($text) {
      case "/start":
        $id = $request['message']['chat']['id'];
        $message = "Envía /nuevo para jugar.\nConsulta las reglas [aquí.](https://es.wikipedia.org/wiki/Tres_en_l%C3%ADnea#Reglas)";
        send_msj(
          $message,
          $id
        );

        $this->command_start(
          $id, // id
          $request['message']['from']['first_name'], // name
          $request['message']['date'], // date
          $request['update_id'], // update_id
          $message // message
        );

        break;
        // The same two cases, a new game
      case "/nuevo":
      case "Sí":
        $message = "Marca la casilla.";
        $id = $request['message']['chat']['id'];
        $board_state = Gato::new_game();
        send_keyboard($message, $id, $board_state);

        $this->command_newGame(
          $id, // id
          $request['message']['date'], // date
          $request['update_id'], // update_id
          $message, //message
          $board_state // board_state
        );

        break;

      case "No":
        $id = $request['message']['chat']['id'];
        $message = "Gracias por jugar.";
        send_msj($message, $id);
        break;
    }

    $msj_data = [
      'id'   => $request['message']['chat']['id'],
      'msj'  => $request['message']['text'],
      'side' => "right", // Indicates who sends the message
      'time' => $request['message']['date'],
    ];

    self::propagate_msj($msj_data);
  }

  public function command_start($id, $name, $date, $update_id, $message)
  {
    $this->telegramUser->createTelegramUserIfNotExist($id, $name);
    $game = $this->game->getLastGame($id);
    $game = $this->firstOrCreateNewGame($game, $id, $date);
    $this->message->createMessage($game->id, $id, $update_id, '/start', 1, $date);
    $this->message->createMessage($game->id, $id, $update_id, $message, 0, $date);
  }

  public function command_newGame($id, $date, $update_id, $message, $board_state)
  {
    $game = $this->game->getLastGame($id);
    $game = $this->firstOrCreateNewGame($game, $id, $date);
    $this->message->createMessage($game->id, $id, $update_id, '/nuevo', 1, $date);
    $this->message->createMessage($game->id, $id, $update_id, $message, 0, $date);
    $this->state->createState($game->id, $board_state, 0, 1, $date);
  }

  public function updateState($id,$board_state)
  {
    $game = $this->game->getLastGame($id);
    $this->state->updateState($game->id,$board_state);
  }

  public function sendWinnerMessage($id,$message,$winner)
  {
    $game = $this->game->getLastGame($id);
    $dateUnix =  time();
    $update_id =  $dateUnix;
    $date =  $dateUnix;
    $this->message->createMessage($game->id,$id,$update_id,$message,0,$date);
    $this->game->changeGameStateToFinaledWithWinner($game,$winner);
  }

  public function firstOrCreateNewGame($game, $id, $date)
  {
    if ($game == null) {
      $game = $this->game->createGame($id, $date);
    } else {
      $this->game->changeGameStateToFinaled($game);
      $game = $this->game->createGame($id, $date);
    }

    return $game;
  }

  // Propagates the message to the agents in the web view
  public function propagate_msj(array $msj_data)
  {
    $pusher = new Pusher(
      env('PUSHER_APP_KEY'),
      env('PUSHER_APP_SECRET'),
      env('PUSHER_APP_ID'),
      array(
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'encrypted' => true
      )
    );

    $pusher->trigger('nuevo-mensaje', 'App\\Events\\Notify', $msj_data);
  }
}
