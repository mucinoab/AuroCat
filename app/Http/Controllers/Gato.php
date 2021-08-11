<?php

namespace App\Http\Controllers;

// Implementación del juego gato.

// Teclado de reintentar juego
const RETRY = [[
  ["text" => "Sí"],
  ["text" => "No"]
]];

class Gato {
  // Representa el valor del bitmask en las 8 posiciones ganadoras.
  const WIN_VALUES = [7, 56, 448, 73, 146, 292, 273, 84];

  // Ejemplos
  //
  // tablero |   bitmask  | decimal
  //
  // x|x|x
  //  | |    -> 000000111 -> 7
  //  | |
  //
  //  | |
  // x|x|x   -> 000111000 -> 56
  //  | |

  // Todas las casillas están llenas.
  const EMPATE = (1 << 9) - 1;


  // Bitmask que representa las casillas ocupadas por un jugador, su utilizan
  // dos.
  // Se usan 9 bits [0, 511]
  // 0 1 2
  // 3 4 5
  // 6 7 8

  private static $white;
  private static $black;

  public function __construct(int $w, int $b) {
    self::$white = $w;
    self::$black = $b;
  }

  // Evalúa si el jugador $mask esta en una posición ganadora
  final function is_win(int $mask): bool {
    foreach(self::WIN_VALUES as $win) {
      if (($mask & $win) == $win) return true;
    }

    return false;
  }

  // Convierte el estado del juego actual en un json que representa el estado
  // actual del juego e información adicional sobre la casilla en si, esto para
  // que al recibir esta información se pueda replicar fácilmente el del juego
  // y el movimiento que se hizo.
  //
  // Representación
  // Un arreglo bidimensional de 3x3 donde cada casilla contiene lo siguiente:
  //  {
  //    "text": <casilla(X, O)>,
  //    "callback_data": "<casilla>,<posición de botón [0-8]>,<bitmask blancas>,<bitmask negras>"
  //  }
  public function state_to_json(): array {
    $board = array();
    $row = array();

    foreach (range(0, 9) as $i) {
      if($i != 0 && ($i % 3) == 0) {
        // Se acaba una fila
        array_push($board, $row);
        $row = array();
      }

      $mask = (1 << $i);
      $tile = ' ';

      if (($mask & self::$white) != 0) {
        $tile = 'O';
      } else if (($mask & self::$black) != 0) {
        $tile = 'X';
      }

      //       casilla, idx,    bitmask blanca,     bitmask negra
      $data = "{$tile},{$i}," . self::$white . ','. self::$black;
      array_push($row, array("text" => $tile, "callback_data" => $data));
    }

    return $board;
  } 

  // Realiza el movimiento $m, realizado por el jugador $side, donde `true` es
  // el jugador `black` y `false` es  el jugador `white`.
  // Si no es un movimiento válido, regresa `false`, en caso contrario, `true`;
  public function move(int $m, bool $side): bool {
    // Es una casilla ocupada, no es un movimiento válido
    if (((self::$white | self::$black) & (1 << $m)) != 0) 
      return false;

    // A qué lado pertenece el movimiento
    if ($side)
      self::$black |= 1 << $m;
    else
      self::$white |= 1 << $m;
    
    return true;
  }

  // Indica en cuál de los 4 estados se encuentra el juego.
  //  0 => Gana agente/bot
  //  1 => Gana usuario
  //  2 => Empate
  //  3 => Nada, juego aún en curso
  public function status(): int {
    // TODO: usar enums, php 8.1

    if ($this->is_win(self::$white)) {
      return 0;
    }

    if ($this->is_win(self::$black)) {
      return 1;
    }

    if ((self::$white | self::$black) == self::EMPATE) {
      return 2;
    }

    return 3;
  }

  // Bot hace un movimiento random
  public function bot_move() {
    // Es un juego en curso
    if (self::status() == 3) {
      $pos = rand(0, 8);

      while (!self::move($pos, false)) {
        $pos = rand(0, 8);
      }
    }
  }

  public static function nuevo_juego(): array {
    $gato = new Gato(0, 0);
    return $gato->state_to_json();
  }
}

// Maneja todos los estados, entradas y respuestas del juego.
function logica_juego(array &$update) {
  $move = explode(",", $update['data']);
  switch ($move[0]) {
    case " ": // Casilla vacía, movimiento válido
      $gato = new Gato((int) $move[2], (int) $move[3]);

      // movimiento usuario
      $gato->move((int) $move[1], true);

      // TODO: Movimiento de agente
      // movimiento del bot
      $gato->bot_move();

      $chatId = $update['message']['chat']['id'];
      $message_id = $update['message']['message_id'];

      update_keyboard($chatId, $message_id, $gato->state_to_json());

      switch ($gato->status()) {
        case 0:
          send_keyboard("Perdiste...\n¿Deseas jugar de nuevo?", $chatId, RETRY, "keyboard");
          break;

        case 1:
          send_keyboard("¡Ganaste!\n¿Deseas jugar de nuevo?", $chatId, RETRY, "keyboard");
          break;

        case 2:
          send_keyboard("Empate.\n¿Deseas jugar de nuevo?", $chatId, RETRY, "keyboard");
          break;
      }
      break;
  // Idea: 
  // Cuando se haga un movimiento en una casilla no válida, actualizar el
  // mensaje para decir que no es válida.
  }
}
