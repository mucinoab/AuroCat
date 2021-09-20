<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Message;
use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'game_id' => Game::factory(),
            'chat_id' => TelegramUser::factory(),
            'update_id' => $this->faker->unixTime($max = 'now'),
            'message' => $this->faker->text,
            'transmitter' => random_int(0,1),
            'date' => $this->faker->unixTime($max = 'now'),
        ];
    }
}
