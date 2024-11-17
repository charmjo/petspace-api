<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        // Seed users table with 10 random users (doctors)
        $veterinarians = User::where('role', 'veterinarian')->get();

        for ($veterinarians as $vet) {
            // Create a user for the doctor
            $schedules = DB::table('schedules')->insertGetId([
                'user_id' => $vet->id,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'role' => 'veterinarian',  // Assuming all are veterinarians
                'dob' => $faker->date(),
                'gender' => $faker->randomElement(['male', 'female']),
                'phone' => $faker->phoneNumber,
                'is_form_filled' => 'yes',
                'avatar_storage_path' => $faker->imageUrl(),
                'password' => bcrypt('p@ssW0rd'),  // Default password for now
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        }
    }
}
