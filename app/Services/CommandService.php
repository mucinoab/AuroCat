<?php

namespace App\Services;

use App\Models\TelegramUser;
use App\Models\Game;
use App\Models\Message;
use App\Models\State;
use DateTime;
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

  public function handleMessage($request) {
    $text = trim($request['message']['text']);
    $chatId = $request['message']['chat']['id'];

    // Handles commands of the type "/function"
    switch ($text) {
      case "/start":
        $message = "Envía /nuevo para jugar.\nConsulta las reglas [aquí.](https://es.wikipedia.org/wiki/Tres_en_l%C3%ADnea#Reglas)";
        send_msj($message, $chatId);

        $this->command_start(
          $chatId,
          $request['message']['date'],
          $request['update_id'],
          $message,
          $request['message']['from']['first_name'], // name
        );

        break;

      // The same two cases, a new game vs the bot
      case "/nuevo":
      case "Sí":
        $this->new_game($chatId, true, $request);
        return;

      case "/nuevo_bot": // game vs agent 
        $this->new_game($chatId, false, $request);
        return;

      case "No":
        send_msj("Gracias por jugar.", $chatId);
        break;
    }

    $msg_data = [
      'id'   => $chatId,
      'name' => $request['message']['chat']['first_name'],
      'msg'  => $text,
      'side' => "left", // Indicates who sends the message
      'time' => $request['message']['date'],
    ];

    self::propagate_msj($msg_data);
  }

  private function new_game(string $chatId, bool $practice, array $request) {
    self::propagate_msj([
      'id'   => $chatId,
      'msg'  => $request['message']['text'],
      'side' => "left", // Indicates who sends the message
      'time' => $request['message']['date'],
    ]);

    $message = "Marca la casilla.";

    $gato = new Gato(0, 0, $practice, $chatId);
    $msg_id = send_keyboard($message, $chatId, $gato->state_to_json());
    $gato->game_id = $msg_id;

    $board_state = $gato->state_to_json();
    update_keyboard($chatId, $msg_id, $board_state);

    $msg_data = [
        'id'   => $chatId,
        'side' => "right",
        'time' => $request['message']['date'],
        'callback' => [
          'data'          => $gato->game_state(),
          'practice game' => $practice,
        ],
    ];

    self::propagate_msj($msg_data);

    $this->command_newGame(
      $chatId,
      $request['message']['date'],
      $request['update_id'],
      $message,
      $board_state
    );
  } 

  public function command_start($id, $name, $date, $update_id, $message) {
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

  public function updateState($id, $board_state)
  {
    $game = $this->game->getLastGame($id);
    $this->state->updateState($game->id, $board_state);
  }

  public function sendWinnerMessage($id, $message, $winner)
  {
    $game     = $this->game->getLastGame($id);
    $dateUnix = $update_id = time();

    $this->message->createMessage($game->id, $id, $update_id, $message, 0, $dateUnix);
    $this->game->changeGameStateToFinaledWithWinner($game, $winner);
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


  public function handleAgentMessage($request){
    $chatId = $request["chat"];
    $msg = $request["msg"];

    $data = [
      'msg' => $msg,
      'id' => $chatId,
      'side' => 'right',
      'instanceId' => $request['senderId'],
      'time' => (new DateTime())->getTimestamp(),
    ];

    send_msj($msg, $chatId);
    self::propagate_msj($data);
  }

  // Propagates the message to the agents in the web view
  public static function propagate_msj(array $msj_data)
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
