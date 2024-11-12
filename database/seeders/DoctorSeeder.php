<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Seed users table with 10 random users (doctors)
        for ($i = 0; $i < 10; $i++) {
            // Create a user for the doctor
            $user = DB::table('users')->insertGetId([
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

            // Now, seed the professional_information table for the doctor
            DB::table('professional_information')->insert([
                'license_number' => strtoupper($faker->bothify('???-######')),
                'license_province' => 'Ontario',  // All doctors are from Ontario
                'license_type' => $faker->randomElement(['Full', 'Provisional', 'Restricted']),
                'specialty' => $faker->randomElement(['General Practice', 'Surgery', 'Dermatology', 'Cardiology']),
                'board_certifications' => $faker->sentence,
                'graduation_year' => $faker->year,
                'veterinary_school' => $faker->company,
                'years_of_experience' => $faker->numberBetween(1, 30),
                'continuing_education' => $faker->sentence,
                'professional_title' => $faker->randomElement(['Dr.', 'DVM']),
                'user_id' => $user,  // Link the doctor to the user created above
                'is_verified' =>  true, // let's assume all of them are verified
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
