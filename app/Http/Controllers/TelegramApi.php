<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

// Basic implementation of the Telegram Bot API
// Source: https://core.telegram.org/bots/api

const BASE_URL = 'https://api.telegram.org/bot';

// Send message, with optional keyboard 
function send_msj(string $msj, string $chat_id, array $keyboard=[], string $keyboard_type="inline_keyboard") {
  // Reference: https://core.telegram.org/bots/api#sendmessage
  
  // Some reserved strings that need to be escaped 
  $msj = str_replace(['.', '!'], ['\\.', '\\!'], $msj); 

  $path = BASE_URL . getenv('TELEGRAM_TOKEN') . "/sendMessage";
  $response = Http::post($path, [
    "text" => $msj,
    "chat_id" => $chat_id,
    "parse_mode" => "MarkdownV2",
    'reply_markup' => [$keyboard_type => $keyboard],
  ]);
}

// Send message with keyboard
function send_keyboard(string $msj, string $chat_id, array $keyboard, string $keyboard_type="inline_keyboard") {
  // Reference: https://core.telegram.org/bots/api#inlinekeyboardmarkup

  send_msj($msj, $chat_id, $keyboard, $keyboard_type);
}

// Updates keyboard of a previously sent message
function update_keyboard(string $chat_id, string $message_id, array $keyboard) {
  // Reference: https://core.telegram.org/bots/api#editmessagereplymarkup

  $path = BASE_URL . getenv('TELEGRAM_TOKEN') . '/editMessageReplyMarkup';
  $response = Http::post($path, [
    'message_id' =>  $message_id,
    'chat_id' =>  $chat_id,
    'reply_markup' => ["inline_keyboard" => $keyboard]
  ]);
}
