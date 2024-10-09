<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        /*TODO: CHARM, DO NOT FORGET TO REMOVE THIS WHEN DOING PROD WORK!*/
        User::factory()->create([
            'name' => 'Charm Test',
            'email' => 'test2@example.com',
            'password' => 'test',
        ]);

        // steve for testing
        User::factory()->create([
            'first_name' => 'Steve',
            'last_name' => 'Test',
            'email' => 'steve.test@example.com',
            'password' => 'p@ssW0rd',
        ]);

        /*TODO: CHARM, DO NOT FORGET TO REMOVE THIS WHEN DOING PROD WORK!*/
        User::factory()->create([
            'first_name' => 'Mariana Test',
            'last_name' => 'Test',
            'email' => 'mariana@example.com',
            'password' => 'p@ssW0rd',
        ]);

        User::factory()->create([
            'first_name' => 'Apurva Test',
            'last_name' => 'Test',
            'email' => 'apurva@example.com',
            'password' => 'p@ssW0rd',
        ]);
    }
}
