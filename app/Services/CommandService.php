<?php

namespace App\Services;

use App\Jobs\FinishGame;
use App\Models\TelegramUser;
use App\Models\Game;
use App\Models\Message;
use App\Models\State;

class CommandService
{
  public $telegramUser;
  public $game;
  public $message;
  public $state;

  public function __construct(TelegramUser $telegramUser, Game $game, Message $message, State $state)
  {
    $this->telegramUser = $telegramUser;
    $this->game = $game;
    $this->message = $message;
    $this->state = $state;
  }

  public function new_game(string $chatId, bool $practice, array $request)
  {
    propagate_msj([
      'id'   => $chatId,
      'msg'  => $request['message']['text'],
      'side' => "left", // Indicates who sends the message
      'time' => $request['message']['date'],
    ]);

    $message = "Marca la casilla.";

    $gato = new Gato(0, 0, $practice, $chatId);
    $msg_id = send_keyboard($message, $chatId, $gato->state_to_json());
    $gato->game_id = $msg_id;

    $board_state = $gato->state_to_json();
    update_keyboard($chatId, $msg_id, $board_state);

    $msg_data = [
      'id'   => $chatId,
      'side' => "right",
      'time' => $request['message']['date'],
      'callback' => [
        'data'          => $gato->game_state(),
        'practice game' => $practice,
      ],
    ];

    propagate_msj($msg_data);

    $this->command_newGame(
      $chatId,
      $request['message']['date'],
      $request['update_id'],
      $message,
      $board_state,
      $practice
    );
  }

  public function command_start($id, $name, $date, $update_id, $message)
  {
    $telegram_user = $this->telegramUser->createTelegramUserIfNotExist($id, $name);
    $game = $this->game->getLastGame($telegram_user);
    if ($game == null || $game->state == 2) {
      $game = $this->game->createGame($id, $date);
    }

    $this->message->createMessage($game->id, $id, $update_id, '/start', 0, $date);
    $this->message->createMessage($game->id, $id, $update_id, $message, 1, $date + 1);
  }

  public function command_newGame($id, $date, $update_id, $message, $board_state, $opponent)
  {
    $telegram_user = TelegramUser::find($id);
    $game = $this->game->getLastGame($telegram_user);
    $game = $this->firstOrCreateNewGame($game, $id, $date, $opponent);
    $this->message->createMessage($game->id, $id, $update_id, '/nuevo', 0, $date);
    $this->message->createMessage($game->id, $id, $update_id, $message, 1, $date + 1);
    $this->state->createState($game->id, $board_state, 0, 1, $date);

    FinishGame::dispatch($game)->delay(now()->addMinutes(15));
  }

  public function updateState($id, $board_state, $transmitter)
  {
    $telegram_user = $this->telegramUser->createTelegramUserIfNotExist($id);
    $game = $this->game->getLastGame($telegram_user);
    $this->state->updateState($game->id, $board_state, $transmitter);
  }

  public function sendWinnerMessage($id, $message, $winner)
  {
    $telegram_user = $this->telegramUser->createTelegramUserIfNotExist($id);
    $game = $this->game->getLastGame($telegram_user);
    $dateUnix =  time();
    $update_id =  $dateUnix;
    $date =  $dateUnix;
    $this->message->createMessage($game->id, $id, $update_id, $message, 0, $date);
    $this->game->changeGameStateToFinaledWithWinner($game, $winner);
  }

  public function firstOrCreateNewGame($game, $id, $date, $opponent)
  {
    if ($game == null) {
      $game = $this->game->createGame($id, $date, $opponent);
    } else {
      $this->game->changeGameStateToFinaled($game);
      $game = $this->game->createGame($id, $date, $opponent);
    }

    return $game;
  }

  public function sendMessage($id, $message, $transmitter)
  {
    $telegram_user = $this->telegramUser->createTelegramUserIfNotExist($id);
    $game = $this->game->getLastGame($telegram_user);
    if ($game == null) return;
    $dateUnix =  time();
    $update_id =  $dateUnix;
    $date =  $dateUnix;
    $this->message->createMessage($game->id, $id, $update_id, $message, $transmitter, $date);
  }
}
