<?php

namespace Database\Seeders;

use App\Models\Pet\Pet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pet::factory()->create([
            'breed' => 'Persian',
            'animal_type' => 'cat',
            'dob' => '2022/06/28',
            'color' => 'white',
            'gender' => 'male',
            'name' => 'Nacho',
            'pet_owner_id'=>2
        ]);

        Pet::factory()->create([
            'breed' => 'Chihuahua',
            'animal_type' => 'dog',
            'dob' => '2020/06/28',
            'color' => 'black',
            'gender' => 'female',
            'name' => 'Diamond',
            'pet_owner_id'=>3
        ]);

        Pet::factory()->create([
            'breed' => 'Maine Coon',
            'animal_type' => 'cat',
            'dob' => '2019/03/15',
            'color' => 'gray',
            'gender' => 'male',
            'name' => 'Whiskers',    
            'pet_owner_id'=>3
        ]);

        Pet::factory()->create([
            'breed' => 'Labrador Retriever',
            'animal_type' => 'dog',
            'dob' => '2021/11/05',
            'color' => 'yellow',
            'gender' => 'male',
            'name' => 'Buddy',
            'pet_owner_id'=>3
        ]);

        Pet::factory()->create([
            'breed' => 'Persian',
            'animal_type' => 'cat',
            'dob' => '2022/08/10',
            'color' => 'white',
            'gender' => 'female',
            'name' => 'Snowflake',
            'pet_owner_id'=>2
        ]);

        Pet::factory()->create([
            'breed' => 'Bulldog',
            'animal_type' => 'dog',
            'dob' => '2018/02/20',
            'color' => 'brown',
            'gender' => 'male',
            'name' => 'Rocky',
            'pet_owner_id'=>2
        ]);

        
    }
}
