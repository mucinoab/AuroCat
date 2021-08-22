<?php

namespace App\Http\Controllers;

use App\Services\CommandService;
use App\Services\GatoService;
use DateTime;
use Illuminate\Http\Request;
use Pusher\Pusher;


class PusherNotificationController extends Controller {
  // Handles all the incoming messages to the bot(webhook), responds
  // accordingly and updates the web view

  public $commandService;
  public $gatoService;

  public function __construct(CommandService $commandService, GatoService $gatoService) {
    $this->commandService = $commandService;
    $this->gatoService = $gatoService;
  }

  // Handles all the updates from telegram.
  public function telegram_to_agent(Request $request) {
    $update = json_decode($request->getContent(), TRUE);

    // It is a callback query
    if (isset($update['callback_query']))
      $this->gatoService->game_logic($update['callback_query']);
    else
      $this->commandService->handleMessage($update);
  }

  // Handles a message that was sent by an agent in the web view.
  public function agent_to_telegram(Request $request) {
    $update = json_decode($request->getContent(), TRUE);
    $this->commandService->handleAgentMessage($update);
  }
}
