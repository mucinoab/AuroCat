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
      $this->commandService->handleMessage($update);
    }

  }

  // Handles a message that was sent by an agent in the web view.
  public function agent_to_telegram() {
    $update = json_decode(file_get_contents('php://input'), TRUE);
    $this->commandService->handleAgentMessage($update);
  }
  
}
