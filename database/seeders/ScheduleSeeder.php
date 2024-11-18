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
        $faker = Faker::create('en_CA'); // Canadian locale for realism
        $veterinarians = User::where('role', 'veterinarian')->get();

        $waterlooLocations = [
            ['id' => 1, 'name' => 'Waterloo West Veterinary Clinic', 'address' => '123 King St N, Waterloo, ON'],
            ['id' => 2, 'name' => 'Bridgeport Animal Hospital', 'address' => '456 Bridgeport Rd E, Waterloo, ON'],
            ['id' => 3, 'name' => 'Laurelwood Veterinary Hospital', 'address' => '789 Erbsville Rd, Waterloo, ON'],
            ['id' => 4, 'name' => 'Waterloo Region Animal Clinic', 'address' => '101 University Ave W, Waterloo, ON'],
            ['id' => 5, 'name' => 'Pet Care Clinic Waterloo', 'address' => '202 Columbia St W, Waterloo, ON'],
        ];

        foreach ($veterinarians as $vet) {
            foreach (range(1, 5) as $index) { // 5 schedules per vet
                $startHour = $faker->numberBetween(8, 17); // Start between 8 a.m. and 5 p.m.
                $startTime = $faker->dateTimeBetween("today {$startHour}:00", "today {$startHour}:30")->format('H:i:s');
                $endTime = date('H:i:s', strtotime($startTime . ' +1 hour')); // End time 1 hour later

                $location = $faker->randomElement($waterlooLocations);

                DB::table('schedules')->insert([
                    'user_id' => $vet->id,
                    'service_company_provider_name' => $location['name'],
                    'schedule_date' => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_booked' => $faker->boolean(30), // 70% chance the slot is booked
                    'description' => 'Appointment for veterinary service in Waterloo, Ontario.',
                    'cost' => $faker->randomElement([75, 100, 150, 200, 250]), // Realistic costs
                    'location' => $location['id'], // Set based on Waterloo locations
                    'service_type' => $faker->randomElement(['Checkup', 'Vaccination', 'Surgery', 'Emergency Care']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
