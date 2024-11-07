<?php

namespace Database\Factories\Pet;

use App\Models\Pet\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Pet::class; 
    public function definition(): array
    {
        // TODO: fix this later
        return [
            'breed' => fake()->randomElement(),
            'animal_type' => fake()->randomElement(),
            'dob' => fake()->randomElement(),
            'color' => fake()->randomElement(),
            'gender' => fake()->randomElement(),
            'name' => fake()->randomElement(),
            'pet_owner_id'=> fake()->randomElement()
        ];
    }
}
