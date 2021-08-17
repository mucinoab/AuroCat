<?php

namespace App\Http\Controllers;

use App\Services\CommandService;
use App\Services\GatoService;
use DateTime;
use Pusher\Pusher;


class PusherNotificationController extends Controller {
  // Handles all the incoming messages to the bot(webhook), responds
  // accordingly and updates the web view

  
  public $commandService;
  public $gatoService;

  public function __construct(CommandService $commandService, GatoService $gatoService)
  {
    $this->commandService = $commandService;
    $this->gatoService = $gatoService;
  
  }


  public function telegram_to_agent() {
    $update = json_decode(file_get_contents('php://input'), TRUE);

    if (isset($update['callback_query'])) {
      // It is a callback query
      $update = $update['callback_query'];
      $this->gatoService->game_logic($update);

    } else {

      $this->commandService->sendMessage($update);
    
    }

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
