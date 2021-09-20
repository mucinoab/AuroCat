<?php

namespace Tests\Feature\Models;

use App\Models\TelegramUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TelegramUserTest extends TestCase
{

    /** @test */
    public function telegramUserCanBeCreate()
    {
        $telegramUser = TelegramUser::factory()->create();
        
        $this->assertDatabaseHas('telegram_users',[
            'id' => $telegramUser->id,
            'name' => $telegramUser->name
        ]);	
    }

    /** @test */
    function createTelegramUserIfNotExist()
    {
        $telegramUser = TelegramUser::factory()->make();

        $newTelegramUser = $telegramUser->createTelegramUserIfNotExist($telegramUser->id,$telegramUser->name);
    
        $this->assertDatabaseHas('telegram_users',[
            'id' => $newTelegramUser->id,
            'name' => $newTelegramUser->name
        ]);	
    }  
}
