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
    }
}
