<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramUser extends Model
{
    protected $fillable = [
        'id',
        'name'
    ];

    public final function createTelegramUserIfNotExist($id,$name)
    {
        TelegramUser::firstOrCreate(
            ['id' => $id],
            ['name' => $name]
        );
    }


}
