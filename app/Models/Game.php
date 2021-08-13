<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    protected $fillable = [
        'name',
        'telegram_user_id',
        'state',
        'winner',
        'opponent',
        'date'
    ];
}
