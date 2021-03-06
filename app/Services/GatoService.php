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

        if($move_by == True && $move[6] == 'user' && !$practice_game) return; //When a user tries to play twice.
        if($move_by == False && $move[6] == 'agent') return; //When the agent tries to play twice.

        $gato = new Gato((int) $move[2], (int) $move[3], $practice_game, $game_id,$message_id);
        $gato->move((int) $move[1], $move_by);

        //Indicates the player who threw the last turn, only if it was a valid movement.
        if($move[6] == 'user'){
          $gato ->turn = 'agent';
        } else if($move[6] == 'agent' && !$practice_game){
          $gato->turn = 'user';
        }

        // Random bot play
        if ($practice_game) $gato->bot_move();

        $board_state = $gato->state_to_json();
        
        update_keyboard($chatId, $message_id, $board_state);

        $game_status = $gato->status();
        $win = 3;
        switch ($game_status) {
          case 0:
            $message = "Perdiste...\n¿Deseas jugar de nuevo?";
            $win = 0;
            break;

          case 1:
            $message = "¡Ganaste!\n¿Deseas jugar de nuevo?";
            $win = 1;
            break;

          case 2:
            $message = "Empate.\n¿Deseas jugar de nuevo?";
            $win = 2;
            break;
        }

        propagate_msj([
          'id'   => $update['message']['chat']['id'],
          'transmitter' => 1,
          'date' => $update['message']['date'],
          'name' => $update['message']['chat']['first_name'],
          'callback' => [
            'data'          => $gato->game_state(),
            'practice_game' => $practice_game,
            'win' => $win
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
            'message'      => $message,
            'transmitter'     => 1, // Indicates who sends the message
            'date'     => $update['message']['date'],
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
    $text = isset($update['message']['text']) ? trim($update['message']['text']) : '<b><i>sticker: </i></b>'. $update['message']['sticker']['emoji'];
    $chatId = $update['message']['chat']['id'];
    
    // Handles commands of the type "/function"
    switch ($text) {
      case "/start":
        $message = "Envía /nuevo para jugar 🤖.\nConsulta las reglas [aquí.](https://es.wikipedia.org/wiki/Tres_en_l%C3%ADnea#Reglas)";
        send_msj($message, $chatId);
        $message = "<p>Env&iacute;a /nuevo para jugar 🤖. Consulta las reglas <a href='https://es.wikipedia.org/wiki/Tres_en_l%C3%ADnea#Reglas'>Aquí</a></p>";
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
    }
    
    // The last name is an optional field.
    $last_name = isset($update['message']['chat']['last_name']) ? $update['message']['chat']['last_name'] : "";

    $msg_data = [
      'id'       => $chatId,
      'name'     => $update['message']['chat']['first_name'],
      'lastName' => $last_name,
      'message'      => $text,
      'transmitter'     => 0, // Indicates who sends the message
      'date'     => $update['message']['date'],
    ];

    propagate_msj($msg_data);

    // Save telegram user message
    $this->commandService->saveTelegramUserMessage($chatId,$update['message']['from']['first_name'],$text);
    
    if(isset($message)){
      $msg_data['message'] = $message;
      $msg_data['transmitter'] = 1;
      propagate_msj($msg_data);
      // save bot message
      $this->commandService->saveAgentOrBotMessage($chatId,$message);
    }
  }

  // Handles all agents messages.
  public function handleAgentMessage($update)
  {
    $chatId = $update["chat"];
    $msg = $update["msg"];
    
    $data = [
      'message' => $msg,
      'id' => $chatId,
      'transmitter' => 1,
      'instanceId' => $update['senderId'],
      'date' => (new DateTime())->getTimestamp(),
    ];

    send_msj($msg, $chatId);
    propagate_msj($data);
    //save agent message
    $this->commandService->saveAgentOrBotMessage($chatId, $msg);
  }

  public function onCourse($id){
    return $this->commandService->getLastTelegramUserGame($id);
  }

}
