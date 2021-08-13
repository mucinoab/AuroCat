<?php

namespace App\Http\Controllers;

use App\Models\TelegramUser;
use Illuminate\Http\Request;

class GameController extends Controller
{

    public function add_telegram_user(Request $request){
        TelegramUser::create([
            "name" => $request->input('name')
        ]);
    }
}
