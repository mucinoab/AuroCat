<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TelegramUserController extends Controller
{
    public function index()
    {

        $chats = [];
        $users = DB::table('telegram_users')
            ->select('id', 'name', DB::raw('UNIX_TIMESTAMP(updated_at) as fecha'))
            ->orderByDesc('fecha')
            ->get();

        foreach ($users as $user) {
            array_push(
                $chats,
                [
                    "id" => $user->id,
                    "name" => $user->name,
                    "time" => $user->fecha,
                ]
            );
        }

        return response()->json(["chats" => $chats]);
    }

    
    /**
     * Return all active games with agent
     */
    public function agentGames()
    {
        $agentGames = [];
        $games = Game::with('telegramUser:id,name')->where('opponent','=',1)->where('state','!=',2)->get(['id','telegram_user_id']);

        foreach ($games as $game) {
            array_push(
                $agentGames,
                [
                    "id" => $game->id,
                    "telegram_user_id" => $game->telegram_user_id,
                    "name" => $game->telegramUser->name
                ]
            );
        }

        return response()->json(["agentGames" => $agentGames]);

    }

    /**
     * Return all active games with bot
     */
    public function botGames()
    {
        $botGames = [];
        $games = Game::with('telegramUser:id,name')->where('opponent','=',0)->where('state','!=',2)->get(['id','telegram_user_id']);

        foreach ($games as $game) {
            array_push(
                $botGames,
                [
                    "id" => $game->id,
                    "telegram_user_id" => $game->telegram_user_id,
                    "name" => $game->telegramUser->name
                ]
            );
        }

        return response()->json(["botGames" => $botGames]);
    }

    /**
     * Return all finalized games 
     */
    public function finalizedGames()
    {
        $finalizedGames = [];
        $games = Game::with('telegramUser:id,name')->where('state','=',2)->get(['id','telegram_user_id']);

        foreach ($games as $game) {
            array_push(
                $finalizedGames,
                [
                    "id" => $game->id,
                    "telegram_user_id" => $game->telegram_user_id,
                    "name" => $game->telegramUser->name
                ]
            );
        }

        return response()->json(["finalizedGames" => $finalizedGames]);
    }
}
