<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_user_id',
        'update_id',
        'message',
        'transmitter',
        'date'
    ];

    /**
     * Change a new message
     */
    public function createMessage($chat_id,$update_id,$message,$transmitter,$date)
    {
        Message::create([
            'telegram_user_id' => $chat_id,
            'update_id' => $update_id,
            'message' => $message,
            'transmitter' => $transmitter,
            'date' => $date
        ]);
    }

    //Relationships in Laravel

    public function telegram_user()
    {
        return $this->belongsTo(TelegramUser::class);
    }
}
