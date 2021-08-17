<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\TelegramUser;
use App\Models\Message;
use App\Models\State;
use Illuminate\Http\Request;

class GameController extends Controller
{

    public function add_telegram_user($id, $name)
    {
        /**
         * firstOr Create
         * The 'firstOrCreate method tries to find a model matching the attributes you pass
         * in the first parameter. If a model is not found, it automatically creates and saves a new
         * a new Model after applying any attributes passed in the second parameter
         */

        TelegramUser::firstOrCreate(
            ['id' => $id],
            ['name' => $name]
        );
    }


    public function get_game_state($id)
    {
        /**
         * The following query gets the record from the games table that contains
         * the telegram_user_id and its status is differet from finished(2).
         * There should be only one record
         * -- state
         *      0 => bot
         *      1 => agente
         *      2 => finalizado
         */

        return Game::where('telegram_user_id', '=', $id)->where('state', '!=', 2)->first();
    }


    public function change_state_game($id)
    {
        //changed the state of the record and saved it
        $game = $this->get_game_state($id);
        $game->state = 2;
        $game->save();
    }

    public function command_start($id, $name, $date, $update_id, $message)
    {
        $this->add_telegram_user($id, $name);
        $game = $this->get_game_state($id);
        //IF the record does not exist, i create a new one
        //ELSE change changed the state of the record and saved it.After create a new one
        if ($game == null) {
            $game = Game::create([
                'telegram_user_id' => $id,
                'date' => $date
            ]);
        } else {
            $game->state = 2;
            $game->save();
            $game = Game::create([
                'telegram_user_id' => $id,
                'date' => $date
            ]);
        }

        /**
         * sended the /start message
         * -- transmitter
         *       0 => agente/bot
         *       1 => telegram_user
         *  */
        Message::create([
            'game_id' => $game->id,
            'chat_id' => $id,
            'update_id' => $update_id,
            'message' => '/start',
            'transmitter' => 1,
            'date' => $date
        ]);

        Message::create([
            'game_id' => $game->id,
            'chat_id' => $id,
            'update_id' => $update_id,
            'message' => $message,
            'transmitter' => 0,
            'date' => $date
        ]);
    }

    public function command_new_game($id, $date,$update_id,$text,$message,$board_state)
    {
        $game = $this->get_game_state($id);
        //IF the record does not exist, i create a new one
        //ELSE change changed the state of the record and saved it.After create a new one
        if ($game == null) {
            $game = Game::create([
                'telegram_user_id' => $id,
                'date' => $date
            ]);
        } else {
            $game->state = 2;
            $game->save();
            $game = Game::create([
                'telegram_user_id' => $id,
                'date' => $date
            ]);
        }

        /**
         * sended the /start message
         * -- transmitter
         *       0 => agente/bot
         *       1 => telegram_user
         *  */
        Message::create([
            'game_id' => $game->id,
            'chat_id' => $id,
            'update_id' => $update_id,
            'message' => $text,
            'transmitter' => 1,
            'date' => $date
        ]);

        Message::create([
            'game_id' => $game->id,
            'chat_id' => $id,
            'update_id' => $update_id,
            'message' => $message,
            'transmitter' => 0,
            'date' => $date
        ]);

        /**
         * Created the first state of the game
         * -- turn = identify the players' turn
         *      0 => agente
         *      1 =>  telegram_user
         * */
        State::create([
            'game_id' => $game->id,
            'board_state' => json_encode($board_state),
            'transmitter' => 0,
            'turn' => 1,
            'date' => $date
        ]);
    }

    public function game_message($id,$message,$date = "",$update_id = "")  
    {
        $game = $this->get_game_state($id);
        /**
         * sended the /start message
         * -- transmitter
         *       0 => agente/bot
         *       1 => telegram_user
         *  */

        if($update_id == ""){
            //Get the unix time
            $dateUnix =  time();

            $update_id =  $dateUnix;
            $date =  $dateUnix;
        }

        Message::create([
            'game_id' => $game->id,
            'chat_id' => $id,
            'update_id' => $update_id,
            'message' => $message,
            'transmitter' => 0,
            'date' => $date
        ]);
    }

    public function game_telegram_message($id,$update_id,$message,$date)  
    {
        $game = $this->get_game_state($id);
        /**
         * sended the /start message
         * -- transmitter
         *       0 => agente/bot
         *       1 => telegram_user
         *  */
        Message::create([
            'game_id' => $game->id,
            'chat_id' => $id,
            'update_id' => $update_id,
            'message' => $message,
            'transmitter' => 1,
            'date' => $date
        ]);
    }



    public function game_state($id,$board_state,$date)
    {
        $game = $this->get_game_state($id);
         /**
        * Created the first state of the game
        * -- turn = identify the players' turn
        *      0 => agente
        *      1 =>  telegram_user
        * */
        State::create([
            'game_id' => $game->id,
            'board_state' => json_encode($board_state),
            'transmitter' => 0,
            'turn' => 1,
            'date' => $date
           ]);
    }

  


}
