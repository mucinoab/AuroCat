<?php

namespace App\Services;

// Implementation of the "gato" game
class Gato {
  // Represents the 8 winning states of the game
  const WIN_VALUES = [7, 56, 448, 73, 146, 292, 273, 84];

  // Example
  //
  // board   |   bitmask  | decimal
  //
  // x|x|x
  //  | |    -> 000000111 -> 7
  //  | |
  //
  //  | |
  // x|x|x   -> 000111000 -> 56
  //  | |

  // Every board position has been taken
  const TIE = (1 << 9) - 1;

  // Bitmask that represents the taken positions in the board by a particular
  // player.
  // 0 1 2
  // 3 4 5
  // 6 7 8

  private static $white;
  private static $black;


  // Bot(true) or human(false) second player
  private static $practice_game;
  private $game_id;
  public $turn; //To save the last user who has been played. ('agent', 'user')
  private $msg_id;

  public function __construct(int $w, int $b, bool $practice_game, string $id,string $msg_id) {
    self::$white = $w;
    self::$black = $b;
    self::$practice_game = $practice_game;
    $this->game_id = $id;
    $this->turn = 'agent';
    $this->msg_id = $msg_id;
  }

  // Checks if a given bitmask is in a winning state
  final function is_win(int $mask): bool {
    foreach(self::WIN_VALUES as $win) {
      if (($mask & $win) == $win) return true;
    }

    return false;
  }

  // Transforms the current state of the game from the bitmask representation
  // to a json representation that is used by Telegram to draw the keyboard.
  //
  // For each of the board positions we store a few key values:
  //   - Text: This is the content that the user will see on the button, we
  //   show the current symbol in that position ('X', 'O', ' ')
  //
  //   - Callback data:  This is the data that Telegram sends back to us every
  //   time a user presses a key. In this field we store:
  //    - The symbol of that position
  //    - The button position relative to the board [0-9]
  //    - The bitmask for the player 1
  //    - The bitmask for the player 2
  //
  // The purpose of storing it this way is that we can then recreate the
  // whole state of a game from the values and then apply the given play.
  //
  // It is a 3x3 array, one cell for every posible position in the board.
  //  {
  //    "text": <['X', 'O', ' ']>,
  //    "callback_data": "<symbol>,<position of the button [0-8]>,<bitmask p1>,<bitmask p2>"
  //  }
  public function state_to_json(): array {
    $board = array();
    $row = array();

    foreach (range(0, 9) as $i) {
      if($i != 0 && ($i % 3) == 0) {
        // The end of a row
        array_push($board, $row);
        $row = array();
      }

      $mask = (1 << $i);
      $tile = ' ';

      if (($mask & self::$white) != 0)
        $tile = 'O';
      else if (($mask & self::$black) != 0)
        $tile = 'X';

      //        symbol, idx,      bitmask p1,        bitmask p1,      player type,            game_id             last_player       msg_id
      $data = "{$tile},{$i}," . self::$white . ','. self::$black.','.self::$practice_game.','.$this->game_id.','.$this->turn .','. $this->msg_id;
      array_push($row, array("text" => $tile, "callback_data" => $data));
    }

    return $board;
  }

  // Executes a given move by a given player (true => black, false => white)
  // The return value indicates if the provided move is valid.
  public function move(int $move, bool $player): bool {
    if ($move < 0 || $move > 8)
      return false;

    if (((self::$white | self::$black) & (1 << $move)) != 0)
      return false;

    if ($player)
      self::$black |= 1 << $move;
    else
      self::$white |= 1 << $move;

    return true;
  }

  // Provides the current state of the game
  //  0 => agent/bot wins
  //  1 => user wins
  //  2 => tie
  //  3 => nothing, game still in progress
  public function status(): int {
    // TODO: Use enums, php 8.1

    if ($this->is_win(self::$white)) {
      return 0;
    }

    if ($this->is_win(self::$black)) {
      return 1;
    }

    if ((self::$white | self::$black) == self::TIE) {
      return 2;
    }

    return 3;
  }

  // Perform a random move
  public function bot_move() {
    if (self::status() == 3) {
      $pos = rand(0, 8);

      while (!self::move($pos, false)) {
        $pos = rand(0, 8);
      }
    }
  }

  public function game_state(): string {
    return " , ," . self::$white . ','. self::$black . ',' . self::$practice_game . ',' . $this->game_id.','.$this->turn .','. $this->msg_id;
  }

  public static function new_game(bool $practice_game, string $game_id, string $msg_id): array {
    $gato = new Gato(0, 0, $practice_game, $game_id,$msg_id);
    return $gato->state_to_json();
  }
}
