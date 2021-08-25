<?php
namespace App\Services;

use App\Services\CommandService;

// Retry keyboard
const RETRY = [[
  ["text" => "Sí"],
  ["text" => "No"]
]];

class GatoService {

  public $commandService;

  public function __construct(CommandService $commandService) {
    $this->commandService = $commandService;
  }

  // Handles all game states, inputs and outputs.
  function game_logic(array &$update) {
    $move = explode(",", $update['data']);
    $practice_game = filter_var($move[4], FILTER_VALIDATE_BOOLEAN);

    switch ($move[0]) {
      case " ": // Empty position: a valid move
        $chatId = $update['message']['chat']['id'];
        $message_id = $update['message']['message_id'];
        $move_by = !isset($update['agent']); // Agent or user move

        $gato = new Gato((int) $move[2], (int) $move[3], $practice_game, $message_id);
        $gato->move((int) $move[1], $move_by);

        // Random bot play
        if ($practice_game) $gato->bot_move();

        $board_state = $gato->state_to_json();
        update_keyboard($chatId, $message_id, $board_state);

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

        CommandService::propagate_msj([
          'id'   => $update['message']['chat']['id'],
          'side' => "right",
          'time' => $update['message']['date'],
          'callback' => [
            'data'          => $gato->game_state(),
            'practice game' => $practice_game
          ]
        ]);

        if($practice_game) $move_by = !$practice_game;

        $this->commandService->updateState($chatId, $board_state,$move_by);

        if ($game_status != 3) { // End of a game
          send_keyboard($message, $chatId, RETRY, "keyboard");
          $this->commandService->sendWinnerMessage($chatId, $message, $game_status);
        }

        break;
    }
  }
}
