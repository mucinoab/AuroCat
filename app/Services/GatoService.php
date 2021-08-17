<?php
namespace App\Services;

use App\Http\Controllers\GameController;

// Retry keyboard
const RETRY = [[
  ["text" => "Sí"],
  ["text" => "No"]
]];

class GatoService {

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

      $game  = new GameController();
      $game->game_state(
        $chatId, //id
        $board_state, // board_state
        $update['message']['date'] //date
      );

      switch ($gato->status()) {
        case 0:
          $message = "Perdiste...\n¿Deseas jugar de nuevo?";
          send_keyboard($message, $chatId, RETRY, "keyboard");

          $game->game_message(
            $chatId, //chta_id
            $message //message
          );
          $game->change_state_game($game->id);
          break;

        case 1:
          $message = "¡Ganaste!\n¿Deseas jugar de nuevo?";
          send_keyboard($message, $chatId, RETRY, "keyboard");

          $game->game_message(
            $chatId, //chta_id
            $message //message
          );
          $game->change_state_game($chatId);

          break;

        case 2:
          $message = "Empate.\n¿Deseas jugar de nuevo?";
          send_keyboard($message, $chatId, RETRY, "keyboard");

          $game->game_message(
            $chatId, //chta_id
            $message //message
          );
          $game->change_state_game($chatId);
          break;
        }
      break;
  }
}

}