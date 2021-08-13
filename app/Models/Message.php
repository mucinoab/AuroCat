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
}
