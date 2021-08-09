<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

// Implementación básica de la API de los bots de Telegram
// Fuente: https://core.telegram.org/bots/api

const BASE_URL = 'https://api.telegram.org/bot';

// Manda mensaje, con teclado opcional
function send_msj(string $msj, string $chat_id, array $keyboard=[], string $keyboard_type="inline_keyboard") {
  // Referencia: https://core.telegram.org/bots/api#sendmessage
  
  // Hay algunos caracteres reservados, se debe escapar.
  $msj = str_replace(['.', '!'], ['\\.', '\\!'], $msj); 

  $path = BASE_URL . getenv('TELEGRAM_TOKEN') . "/sendMessage";
  $response = Http::post($path, [
    "text" => $msj,
    "chat_id" => $chat_id,
    "parse_mode" => "MarkdownV2",
    'reply_markup' => [$keyboard_type => $keyboard],
  ]);
}

// Manda mensaje con teclado
function send_keyboard(string $msj, string $chat_id, array $keyboard, string $keyboard_type="inline_keyboard") {
  // Referencia: https://core.telegram.org/bots/api#inlinekeyboardmarkup

  send_msj($msj, $chat_id, $keyboard, $keyboard_type);
}

// Actualiza teclado de un mensaje previamente enviado
function update_keyboard(string $chat_id, string $message_id, array $keyboard) {
  // Referencia: https://core.telegram.org/bots/api#editmessagereplymarkup

  $path = BASE_URL . getenv('TELEGRAM_TOKEN') . '/editMessageReplyMarkup';

  $response = Http::post($path, [
    'message_id' =>  $message_id,
    'chat_id' =>  $chat_id,
    'reply_markup' => ["inline_keyboard" => $keyboard]
  ]);
}
