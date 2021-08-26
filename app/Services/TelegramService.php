<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

// Basic implementation of the Telegram Bot API
// Source: https://core.telegram.org/bots/api

const BASE_URL = 'https://api.telegram.org/bot';

// Send message, with optional keyboard.
// Returns the message id.
function send_msj(string $msj, string $chat_id, array $keyboard=[], string $keyboard_type="inline_keyboard"): string {
  // Reference: https://core.telegram.org/bots/api#sendmessage
  
  // Some reserved strings that need to be escaped 
  $msj = str_replace(['.', '!'], ['\\.', '\\!'], $msj); 

  $path = BASE_URL . getenv('TELEGRAM_TOKEN') . "/sendMessage";
  $response = Http::post($path, [
    "text" => $msj,
    "chat_id" => $chat_id,
    "parse_mode" => "MarkdownV2",
    'reply_markup' => [
      $keyboard_type => $keyboard,
      'one_time_keyboard' => true,
    ],
  ]);

  return $response['result']['message_id'];
}

// Send message with keyboard
// Returns the message id.
function send_keyboard(string $msj, string $chat_id, array $keyboard, string $keyboard_type="inline_keyboard"): string {
  // Reference: https://core.telegram.org/bots/api#inlinekeyboardmarkup

  return send_msj($msj, $chat_id, $keyboard, $keyboard_type);
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
