<?php

// namespace Database\Seeders;
// \_0>

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Agent;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AgentSeeder::class);
    }
}

class AgentSeeder extends Seeder {
    public function run(){
        Agent::create(['name' => 'Bruno', 'lastname' => 'Muciño', 'email' => 'mucinoab@gmail.com', 'password' => Hash::make('1234')]);
    }
}
