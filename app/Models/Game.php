<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'telegram_user_id',
        'state',
        'winner',
        'opponent',
        'date'
    ];
    
    /**
     * From user games, get the record which is different of finished(2)
     * -- state
     *      0 => bot
     *      1 => agente
     *      2 => finalizado
     */
    public function getLastGame($telegram_user)
    {
        return $telegram_user->games()->orderBy('date','DESC')->first();
    }
    /**
     * Change the game to finalized
     */
    public function changeGameStateToFinaled($game)
    {
        $this->changeGameState($game,2);
    }
    /**
     * Change the game to finalized and add the winner
     */
    public function changeGameStateToFinaledWithWinner($game,$winner)
    {
        $game->state = 2;
        $game->winner = $winner;
        $game->save();
    }

    /**
     * Change the game state
     */
    public function changeGameState($game,$state)
    {
        //changed the state of the record and saved it
        $game->state = $state;
        $game->save();
    }
    /**
     * Change a new game
     */
    public function createGame($telegram_user_id,$date,$opponent=false)
    {
        return Game::create([
            'telegram_user_id' => $telegram_user_id,
            'date' => $date,
            'opponent' => !$opponent
          ]);
    }

    public function findGame($id)
    {
        return Game::find($id);
    }


    //Relationships
    
    public function telegramUser()
    {
        return $this->belongsTo(TelegramUser::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function stateRelation()
    {
        return $this->hasOne(State::class);
    }

    public function message()
    {
        return $this->hasOne(Message::class)
            ->orderBy('date','desc');
    }

}
