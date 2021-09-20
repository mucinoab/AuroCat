<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name'
    ];


    public final function createTelegramUserIfNotExist($id,$name = "Invitado")
    {
        $telegram_user = TelegramUser::firstOrCreate(
            ['id' => $id],
            ['name' => $name]
        );

        return $telegram_user;
    }

    //Relationships in Laravel
    
    public function games()
    {
        return $this->hasMany(Game::class);
    }


}
