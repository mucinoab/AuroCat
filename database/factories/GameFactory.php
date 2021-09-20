<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Game::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'telegram_user_id' => TelegramUser::factory(),
            'state' => random_int(0,1),
            'winner' => null,
            'opponent' => random_int(0,1),  
            'date' => $this->faker->unixTime($max = 'now')            
        ];
    }
}
