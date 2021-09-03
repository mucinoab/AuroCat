<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   
    //return all the chats that are palying with an agent or bot
    public function getGameStats()
    {
        $status = 1;
        $title= 'Game records';
        $msg = 'Succesfull get of game records!';
        $data = [];
        $code = 201;

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
            'status'=> $status,
            'title'=> $title,
            'msg'=> $msg,
            'data'=> $stats
        ], $code);
    }
}