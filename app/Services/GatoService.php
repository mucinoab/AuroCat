<?php
namespace App\Services;

// Retry keyboard
const RETRY = [[
  ["text" => "Sí"],
  ["text" => "No"]
]];

class GatoService {

  public $commandService;

  public function __construct(CommandService $commandService)
  {
    $this->commandService = $commandService;
  }

// Handles all game states, inputs and outputs.
function game_logic(array &$update) {
  $move = explode(",", $update['data']);

  switch ($move[0]) {
    case " ": // Empty position: a valid move
      $gato = new Gato((int) $move[2], (int) $move[3]);

      // User play
      $gato->move((int) $move[1], true);

      // Random bot play
      $gato->bot_move();

      $chatId = $update['message']['chat']['id'];
      $message_id = $update['message']['message_id'];
      $board_state = $gato->state_to_json();
      update_keyboard($chatId, $message_id, $board_state);
      $this->commandService->updateState($chatId,$board_state);

      switch ($gato->status()) {
        case 0:
          $message = "Perdiste...\n¿Deseas jugar de nuevo?";
          send_keyboard($message, $chatId, RETRY, "keyboard");

          $this->commandService->sendWinnerMessage($chatId,$message,0);
          break;

        case 1:
          $message = "¡Ganaste!\n¿Deseas jugar de nuevo?";
          send_keyboard($message, $chatId, RETRY, "keyboard");

          $this->commandService->sendWinnerMessage($chatId,$message,1);
          break;

        case 2:
          $message = "Empate.\n¿Deseas jugar de nuevo?";
          send_keyboard($message, $chatId, RETRY, "keyboard");

          $this->commandService->sendWinnerMessage($chatId,$message,2);
          break;
        }
      break;
  }
}

}