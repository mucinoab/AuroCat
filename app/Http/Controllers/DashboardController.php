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
     *  gwad - games won and drawn
     *   tgp - total games played
     *    tp - time played
     * avgtp - average time played
     */
    public function index(Request $request)
    {
        
        $type = $request->option;

        if ($type == 'gwad') {
            return $this->getGamesWonAndDrawn();
        } else if ($type == 'tgp') {
            return $this->getTotalGamesPlayed();
        } else if ($type == 'tp') {
            return $this->getTimePlayed();
        } else if ($type == 'avgtp') {
            return $this->getAvgTimePlayed();
        }

    }

    // Return all games won or drawn against the agent or bot
    public function getGamesWonAndDrawn()
    {
        $status = 1;
        $title = 'Games Won and Drawn';
        $msg = 'Succesfull get of game records!';
        $data = [];
        $code = 200;
        $labels = ['Red', 'Blue', 'Yellow', 'Green', 'Grey', 'Pink', 'Orange', 'Purple'];
        // -- Agente vs UsuarioTelegram (Gana Agente)
        $agentBeatsUser = count(Game::where([['winner', '=', 0], ['opponent', '=', 1]])->get());
        // -- Agente vs UsuarioTelegram (Gana TelegramUser)
        $userBeatsAgent = count(Game::where([['winner', '=', 1], ['opponent', '=', 1]])->get());
        // -- Agente vs UsuarioTelegram (Empate)
        $userAgentDraw = count(Game::where([['winner', '=', 2], ['opponent', '=', 1]])->get());
        // -- Bot vs UsuarioTelegram (Gana Bot)
        $botBeatsUser = count(Game::where([['winner', '=', 0], ['opponent', '=', 0]])->get());
        // -- Bot vs UsuarioTelegram (Gana TelegramUser)
        $userBeatsBot = count(Game::where([['winner', '=', 1], ['opponent', '=', 0]])->get());
        // -- Bot vs UsuarioTelegram (Empate)
        $userBotDraw = count(Game::where([['winner', '=', 2], ['opponent', '=', 0]])->get());
        // Creamos una clase vacía
        $stats = new \stdClass();
        $stats->user_agent_total = $agentBeatsUser + $userBeatsAgent +  $userAgentDraw;
        $stats->user_beats_agent = $userBeatsAgent;
        $stats->agent_beats_user = $agentBeatsUser;
        $stats->user_agent_draw = $userAgentDraw;
        $stats->user_bot_total = $botBeatsUser + $userBeatsBot +  $userBotDraw;
        $stats->user_beats_bot = $userBeatsBot;
        $stats->bot_beats_user = $botBeatsUser;
        $stats->user_bot_draw = $userBotDraw;

        return response()->json([
            'status' => $status,
            'title' => $title,
            'msg' => $msg,
            'data' => $stats,
            'labels' => $labels
        ], $code);
    }

    // Return all games played by telegram user, agent and bot
    public function getTotalGamesPlayed()
    {
        $title = "Total Games Played";
        $code = 200;
        $labels = ['Red', 'Blue', 'Yellow'];

        // -- All Games played by the telegram user
        $user_games = DB::table('games')->count();
        // -- All games played by the agent
        $agent_games = DB::table('games')
            ->where('opponent', '=', 1)
            ->count();
        // -- All games played by the bot
        $bot_games = DB::table('games')
            ->where('opponent', '=', 0)
            ->count();

        // Creamos una clase vacía
        $stats = new \stdClass();
        $stats->user_games = $user_games;
        $stats->agent_games = $agent_games;
        $stats->bot_games = $bot_games;

        return response()->json([
            'title' => $title,
            'data' => $stats,
            'labels' => $labels
        ], $code);
    }

    // Return the total time played by the telegram user, against the agent and the bot
    public function getTimePlayed() 
    {
        $title = "Time Played";
        $code = 200;
        $labels = ['Red', 'Yellow', 'Blue'];

        $total_time_played = Game::select(
            DB::raw('sec_to_time(sum(time_to_sec(timediff(from_unixtime(states.date), 
                from_unixtime(games.date))))) as total_time_played'))
                ->join('states', 'games.id', '=', 'states.game_id')
                ->whereNotNull('games.winner')
                ->first();

        $total_time_played_vs_agent = Game::select(
            DB::raw('sec_to_time(sum(time_to_sec(timediff(from_unixtime(states.date), 
                    from_unixtime(games.date))))) as total_time_played_vs_agent'))
            ->join('states', 'games.id', '=', 'states.game_id')
            ->where('games.opponent',1)->whereNotNull('games.winner')
            ->first();

        $total_time_played_vs_bot = Game::select(
            DB::raw('sec_to_time(sum(time_to_sec(timediff(from_unixtime(states.date), 
                    from_unixtime(games.date))))) as total_time_played_vs_bot'))
                ->join('states', 'games.id', '=', 'states.game_id')
                ->where('games.opponent',0)->whereNotNull('games.winner')
                ->first();

        $data = [$total_time_played,$total_time_played_vs_agent,$total_time_played_vs_bot];    

        return response()->json([
            'title' => $title,
            'data' => $data,
            'labels' => $labels
        ], $code);
    }

    // Return the average total time played by the telegram user, against the agent and the bot
    public function getAvgTimePlayed() {
        $title = "Average Time Played";
        $code = 200;
        $labels = ['Red', 'Yellow', 'Blue'];

        $total_time_played = Game::select(
            DB::raw('sec_to_time(round(avg(time_to_sec(timediff(from_unixtime(states.date), 
                from_unixtime(games.date)))))) as avg_total_time_played'))
                ->join('states', 'games.id', '=', 'states.game_id')
                ->whereNotNull('games.winner')
                ->first();

        $total_time_played_vs_agent = Game::select(
            DB::raw('sec_to_time(round(avg(time_to_sec(timediff(from_unixtime(states.date), 
                    from_unixtime(games.date)))))) as avg_time_played_vs_agent'))
            ->join('states', 'games.id', '=', 'states.game_id')
            ->where('games.opponent',1)->whereNotNull('games.winner')
            ->first();

        $total_time_played_vs_bot = Game::select(
            DB::raw('sec_to_time(round(avg(time_to_sec(timediff(from_unixtime(states.date), 
                    from_unixtime(games.date)))))) as avg_time_played_vs_bot'))
                ->join('states', 'games.id', '=', 'states.game_id')
                ->where('games.opponent',0)->whereNotNull('games.winner')
                ->first();

        $data = [$total_time_played,$total_time_played_vs_agent,$total_time_played_vs_bot];    

        return response()->json([
            'title' => $title,
            'data' => $data,
            'labels' => $labels
        ], $code);        
    }


}
