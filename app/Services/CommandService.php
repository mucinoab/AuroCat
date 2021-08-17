<?php

namespace App\Services;

use App\Http\Controllers\GameController;
use Pusher\Pusher;

require_once "TelegramService.php";
require_once "GatoService.php";


class CommandService
{

  public function sendMessage($request)
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

        $game = new GameController();
        $game->command_start(
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

        $game = new GameController();
        $game->command_new_game(
          $id, // id
          $request['message']['date'], // date
          $request['update_id'], // update_id
          $text, // text,
          $message, //message
          $board_state // board_state
        );
        break;

      case "No":
        $id = $request['message']['chat']['id'];
        $message = "Gracias por jugar."; 
        send_msj($message,$id);
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
