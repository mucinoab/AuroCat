<?php

namespace App\Http\Controllers;

use DateTime;
use Pusher\Pusher;

require_once "Gato.php";
require_once "TelegramApi.php";

class PusherNotificationController extends Controller {
  // Maneja todos los mensajes enviados al bot.
  // Responde conforme la situación y replica mensajes a la vista web.
  public function telegram_a_agente() {
    $update = json_decode(file_get_contents('php://input'), TRUE);

    if (isset($update['callback_query'])) {
      // Es un callback query
      $update = $update['callback_query'];
      logica_juego($update);
      $side = "right";
    } else {
      $side = "left";
    }

    // Manejo de comandos
    $text = trim($update['message']['text']);
    switch ($text) {
      case "/start":
        send_msj(
          "Envía /nuevo para jugar.\nConsulta las reglas [aquí.](https://es.wikipedia.org/wiki/Tres_en_l%C3%ADnea#Reglas)",
          $update['message']['chat']['id']
        );
        break;

      // Mismos dos casos, nuevo juego
      case "/nuevo":
      case "Sí":
        send_keyboard("Marca la casilla.", $update['message']['chat']['id'], Gato::nuevo_juego());
        break;

      case "No":
        send_msj("Gracias por jugar.", $update['message']['chat']['id']);
        break;
    }

    $data = [
      'id'   => $update['message']['chat']['id'],
      'msj'  => $update['message']['text'],
      'side' => $side, // Indica quién envió el mensaje
      'time' => $update['message']['date'],
    ];

    self::propagate_msj($data);
  }

  // Propaga el mensaje a los agentes(vista web).
  public function propagate_msj(array $data) {
    $pusher = new Pusher(
      env('PUSHER_APP_KEY'),
      env('PUSHER_APP_SECRET'),
      env('PUSHER_APP_ID'),
      array(
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'encrypted' => true
      )
    );

    $pusher->trigger('nuevo-mensaje', 'App\\Events\\Notify', $data);
  }

  // Manda mensaje que fue enviado desde vista web a usuario y replica en todos
  // los navegadores activos
  public function agente_a_telegram() {
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
