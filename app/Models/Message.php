<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    protected $fillable = [
       'game_id',
        'chat_id',
        'update_id',
        'message',
        'transmitter',
        'date'
    ];

    /**
     * Change a new message
     */
    public function createMessage($game_id,$chat_id,$update_id,$message,$transmitter,$date)
    {
        Message::create([
            'game_id' => $game_id,
            'chat_id' => $chat_id,
            'update_id' => $update_id,
            'message' => $message,
            'transmitter' => $transmitter,
            'date' => $date
        ]);
    }

    //Relationships in Laravel

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    
}
