<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    // Softdeletes
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'chat_messages';
}
