<?php

// namespace Database\Seeders;
// \_0>

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\UserType;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTypeSeeder::class);
        $this->call(UserSeeder::class);
    }
}

class UserTypeSeeder extends Seeder {
    public function run(){
        UserType::create(['name' => 'Agente']);
        UserType::create(['name' => 'Cliente']);
        UserType::create(['name' => 'Bot']);
    }
}

class UserSeeder extends Seeder {
    public function run(){
        User::create(['name' => 'Bruno', 'lastname' => 'Muciño', 'email' => 'mucinoab@gmail.com', 'password' => Hash::make('1234'), 'user_type_id' => 1]);
    }
}
