<?php

namespace App\Services;

use App\Models\Game;
use App\Services\CommandService;
use DateTime;

require_once "TelegramService.php";
require_once "PropagateService.php";

const AGENT = "Juego vs Agente 🧍";
const BOT = "Juego vs Bot 🤖";

// Retry keyboard
const RETRY = [[
  ["text" => "Sí"],
  ["text" => "No"]
]];

// Opponent selection keyboard
const OPPONENT = [[
  ["text" => AGENT],
  ["text" => BOT]
]];

class GatoService
{

  public $commandService;

  public function __construct(CommandService $commandService)
  {
    $this->commandService = $commandService;
  }

  // Handles all game states, inputs and outputs.
  function handleGame(array &$update,Game $game)
  {
    $move = explode(",", $update['data']);
    $practice_game = filter_var($move[4], FILTER_VALIDATE_BOOLEAN);

    switch ($move[0]) {
      case " ": // Empty position: a valid move
        $chatId = $update['message']['chat']['id'];
        $message_id = $update['message']['message_id'];
        $move_by = !isset($update['agent']); // Agent or user move
        $game_id = $move[5];  //game_id

        $gato = new Gato((int) $move[2], (int) $move[3], $practice_game, $game_id);
        $gato->move((int) $move[1], $move_by);

        // Random bot play
        if ($practice_game) $gato->bot_move();

        $board_state = $gato->state_to_json();
        
        /**
         * We send the board together with the next message  that we use 
         * for the game_id, for that reason we add +1 to update in the next  
         * message and no the message used fot the game_id.
         */
        update_keyboard($chatId, $game_id+1, $board_state);

        $game_status = $gato->status();

        switch ($game_status) {
          case 0:
            $message = "Perdiste...\n¿Deseas jugar de nuevo?";
            break;

          case 1:
            $message = "¡Ganaste!\n¿Deseas jugar de nuevo?";
            break;

          case 2:
            $message = "Empate.\n¿Deseas jugar de nuevo?";
            break;
        }

        propagate_msj([
          'id'   => $update['message']['chat']['id'],
          'side' => "right",
          'time' => $update['message']['date'],
          'callback' => [
            'data'          => $gato->game_state(),
            'practice game' => $practice_game
          ]
        ]);

        if ($practice_game) $move_by = !$practice_game;

        $this->commandService->updateState($game->id, $board_state, $move_by);

        if ($game_status != 3) { // End of a game
          send_keyboard($message, $chatId, RETRY, "keyboard");

          $last_name = isset($update['message']['chat']['last_name']) ? $update['message']['chat']['last_name'] : "";

          $msg_data = [
            'id'       => $chatId,
            'name'     => $update['message']['chat']['first_name'],
            'lastName' => $last_name,
            'msg'      => $message,
            'side'     => "right", // Indicates who sends the message
            'time'     => $update['message']['date'],
          ];
    
          propagate_msj($msg_data);

          $this->commandService->sendWinnerMessage($chatId, $message, $game_status,$game);
        }

        break;
    }
  }

  // Handles all TelegramUser messages.
  public function handleTelegramUserMessage(array &$update)
  {
    $text = isset($update['message']['text']) ? trim($update['message']['text']) : json_encode($update['message']['sticker']);
    $chatId = $update['message']['chat']['id'];
    
    // Handles commands of the type "/function"
    switch ($text) {
      case "/start":
        $message = "Envía /nuevo para jugar 🤖.\nConsulta las reglas [aquí.](https://es.wikipedia.org/wiki/Tres_en_l%C3%ADnea#Reglas)";
        send_msj($message, $chatId);

        $this->commandService->command_start(
          $chatId,
          $update['message']['from']['first_name'], // name
          $message
        );
        break;

        // The same two cases, a new game
      case "/nuevo":
      case "Sí":
        $message = "Elige un oponente.";
        send_keyboard($message, $chatId, OPPONENT, "keyboard");
        break;

      case BOT:
        $this->commandService->new_game($chatId, true, $update,BOT);
        return;

      case AGENT:
        $this->commandService->new_game($chatId, false, $update,AGENT);
        return;

      case "No":
        $message = "Gracias por jugar.";
        send_msj($message, $chatId);
        break;
      default:
        $this->commandService->sendMessage($chatId, $text, 0);
    }

    // The last name is an optional field.
    $last_name = isset($update['message']['chat']['last_name']) ? $update['message']['chat']['last_name'] : "";

    $msg_data = [
      'id'       => $chatId,
      'name'     => $update['message']['chat']['first_name'],
      'lastName' => $last_name,
      'msg'      => $text,
      'side'     => "left", // Indicates who sends the message
      'time'     => $update['message']['date'],
    ];

    propagate_msj($msg_data);

    if(isset($message)){
      $msg_data['msg'] = $message;
      $msg_data['side'] = "right";
      propagate_msj($msg_data);
    }

  }

  // Handles all agents messages.
  public function handleAgentMessage($update)
  {
    $chatId = $update["chat"];
    $msg = $update["msg"];

    $data = [
      'msg' => $msg,
      'id' => $chatId,
      'side' => 'right',
      'instanceId' => $update['senderId'],
      'time' => (new DateTime())->getTimestamp(),
    ];

    send_msj($msg, $chatId);
    $this->commandService->sendMessage($chatId, $msg, 1);
    propagate_msj($data);
  }


  public function onCourse($id){
    return $this->commandService->getLastTelegramUserGame($id);
  }

}
