<?php

namespace App\Http\Controllers;

use DateTime;
use Pusher\Pusher;

require_once "Gato.php";
require_once "TelegramApi.php";

class PusherNotificationController extends Controller {
  // Handles all the incoming messages to the bot(webhook), responds
  // accordingly and updates the web view
  public function telegram_to_agent() {
    $update = json_decode(file_get_contents('php://input'), TRUE);

    if (isset($update['callback_query'])) {
      // It is a callback query 
      $update = $update['callback_query'];
      game_logic($update);
      $side = "right";
    } else {
      $side = "left";
    }

    // Handles commands "/function"
    $text = trim($update['message']['text']);
    switch ($text) {
      case "/start":
        send_msj(
          "Envía /nuevo para jugar.\nConsulta las reglas [aquí.](https://es.wikipedia.org/wiki/Tres_en_l%C3%ADnea#Reglas)",
          $update['message']['chat']['id']
        );
        break;

      // The same two cases, a new game
      case "/nuevo":
      case "Sí":
        send_keyboard("Marca la casilla.", $update['message']['chat']['id'], Gato::new_game());
        break;

      case "No":
        send_msj("Gracias por jugar.", $update['message']['chat']['id']);
        break;
    }

    $msj_data = [
      'id'   => $update['message']['chat']['id'],
      'msj'  => $update['message']['text'],
      'side' => $side, // Indicates who sends the message
      'time' => $update['message']['date'],
    ];

    self::propagate_msj($msj_data);
  }

  // Propagates the message to the agents in the web view
  public function propagate_msj(array $msj_data) {
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

  // Handles a message that was sent by an agent in the web view. 
  public function agent_to_telegram() {
    $update = json_decode(file_get_contents('php://input'), TRUE);
    $chatId = $update["chat"];
    $msj = $update["msj"];

    $data = [
      'msj' => $msj,
      'id' => $chatId,
      'side' => 'right',
      'instanceId' => $update['senderId'],
      'time' => (new DateTime())->getTimestamp(),
    ];

    send_msj($msj, $chatId);
    self::propagate_msj($data);
  }
}
