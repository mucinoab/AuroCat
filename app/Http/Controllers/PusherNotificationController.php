<?php

namespace App\Http\Controllers;

use App\Services\GatoService;
use Illuminate\Http\Request;

class PusherNotificationController extends Controller
{
  // Handles all the incoming messages to the bot(webhook), responds
  // accordingly and updates the web view

  public $gatoService;

  public function __construct(GatoService $gatoService)
  {
    $this->gatoService = $gatoService;
  }

  // Handles all the updates from telegram.
  public function telegram_to_agent(Request $request)
  {
    $update = json_decode($request->getContent(), TRUE);

    // It is a callback query
    if (isset($update['callback_query']))
      $this->gatoService->handleGame($update['callback_query']);
    else
      $this->gatoService->handleTelegramUserMessage($update);
  }

  // Handles a message that was sent by an agent in the web view.
  public function agent_to_telegram(Request $request)
  {
    $update = json_decode($request->getContent(), TRUE);
    $this->gatoService->handleAgentMessage($update);
  }
}
