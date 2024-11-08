<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'test',
        ]);

        /*TODO: CHARM, DO NOT FORGET TO REMOVE THIS WHEN DOING PROD WORK!*/
        User::factory()->create([
            'first_name' => 'Charm',
            'last_name' => 'Test',
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
