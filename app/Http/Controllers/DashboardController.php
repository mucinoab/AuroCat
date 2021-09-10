<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  /**
  * Return a result corresponding to the options passed by parameter
  * ej: APP_URL/rates/{option}
  *
  * option  Description
  * gwad - games won and drawn
  *  tgp - total games played
  *   tp - time played
  */
  public function index(Request $request)
  {
    $type = $request->option;

    if ($type == 'gwad') {
      $a = $this->getGamesWonAndDrawn();
    } else if ($type == 'tgp') {
      $a = $this->getTotalGamesPlayed();
    } else if ($type == 'tp') {
      $a = $this->getTimePlayed();
    }

    return $a;
  }

  // Return all games won or drawn against the agent or bot
  public function getGamesWonAndDrawn()
  {
    $games = Game::select('winner', 'opponent')->get();

    $agentBeatsUser = 0;
    $userBeatsAgent = 0;
    $userAgentDraw  = 0;

    $botBeatsUser   = 0;
    $userBeatsBot   = 0;
    $userBotDraw    = 0;

    foreach($games as $game) {
      $game_result = "{$game->winner}{$game->opponent}";

      switch ($game_result) {
      // ---------- Agent vs User -------------
      case "01": // Agent wins
        $agentBeatsUser++;
        break;

      case "11": // User wins
        $userBeatsAgent++;
        break;

      case "21": // Tie
        $userAgentDraw++;
        break;

      // ---------- Bot vs User -------------
      case "00": // Bot wins
        $botBeatsUser++;
        break;

      case "10": // User wins
        $userBeatsBot++;
        break;

      case "20": // Tie
        $userBotDraw++;
        break;
      }
    }

    $labels = [
      ["Agent Wins", "User Wins", "Tie"],
      ["Bot Wins", "User Wins", "Tie"],
    ];

    $data = [
      [$agentBeatsUser, $userBeatsAgent, $userAgentDraw],
      [$botBeatsUser, $userBeatsBot, $userBotDraw],
    ];

    return response()->json([
      'title' => 'Games Won and Drawn',
      'data' => $data,
      'labels' => $labels
    ]);
  }

  // Return all games played by telegram user, agent and bot
  public function getTotalGamesPlayed()
  {
    $title = "Total Games Played";
    $games = Game::select('opponent')->get();

    $agent_games = 0;
    $bot_games = 0;

    foreach ($games as $game) {
      if ($game->opponent == 0) $bot_games++;
      else $agent_games++;
    }

    return response()->json([
      'title' => $title,
      'data' => [$agent_games, $bot_games],
      'labels' => ["vs Agent", "vs Bot"],
    ]);
  }

  // Return the total time played by the telegram user, against the agent and the bot
  public function getTimePlayed()
  {
    $time_played_vs_agent = Game::select(
      DB::raw('sec_to_time(sum(time_to_sec(timediff(from_unixtime(states.date),
    from_unixtime(games.date))))) as total_time_played_vs_agent'))
      ->join('states', 'games.id', '=', 'states.game_id')
      ->where('games.opponent',1)->whereNotNull('games.winner')
      ->first();

    $time_played_vs_bot = Game::select(
      DB::raw('sec_to_time(sum(time_to_sec(timediff(from_unixtime(states.date),
    from_unixtime(games.date))))) as total_time_played_vs_bot'))
      ->join('states', 'games.id', '=', 'states.game_id')
      ->where('games.opponent',0)->whereNotNull('games.winner')
      ->first();

    return response()->json([
      'title' => "Time Played",
      'data' => [
        $time_played_vs_agent,
        $time_played_vs_bot
      ],
      'labels' => [
        "vs Agent",
        "vs Bot"
      ]
    ]);
  }
}
