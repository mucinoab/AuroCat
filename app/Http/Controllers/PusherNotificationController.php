<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Pusher\Pusher;

class PusherNotificationController extends Controller
{
  public function telegram_a_agente()
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

    $update = json_decode(file_get_contents('php://input'), TRUE)['message'];

    $data['id']   = $update['chat']['id'];
    $data['msj']  = $update['text'];
    $data['time'] = $update['date'];

    $pusher->trigger('nuevo-mensaje', 'App\\Events\\Notify', $data);
  }

  public function agente_a_telegram()
  {
    $update = json_decode(file_get_contents('php://input'), TRUE);
    $chatId = $update["chat"];
    $msj = $update["msj"];

    $path = 'https://api.telegram.org/bot' . getenv('TELEGRAM_TOKEN');
    $req = file_get_contents($path . '/sendmessage?chat_id=' . $chatId . '&text=' . $msj);
  }
}
