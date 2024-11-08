<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllergensSeeder extends Seeder
{
    public function run()
    {
        $allergens = [
            ['allergen' => 'Beef', 'classification' => 'Food', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Dairy', 'classification' => 'Food', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Chicken', 'classification' => 'Food', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Lamb', 'classification' => 'Food', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Fish', 'classification' => 'Food', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Eggs', 'classification' => 'Food', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Wheat', 'classification' => 'Food', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Corn', 'classification' => 'Food', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Soy', 'classification' => 'Food', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Pork', 'classification' => 'Food', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Turkey', 'classification' => 'Food', 'species_affected' => 'Dog'],
            ['allergen' => 'Rabbit', 'classification' => 'Food', 'species_affected' => 'Dog'],
            ['allergen' => 'Venison', 'classification' => 'Food', 'species_affected' => 'Dog'],
            ['allergen' => 'Barley', 'classification' => 'Food', 'species_affected' => 'Dog'],
            ['allergen' => 'Oats', 'classification' => 'Food', 'species_affected' => 'Dog'],
            ['allergen' => 'Peas', 'classification' => 'Food', 'species_affected' => 'Dog'],
            ['allergen' => 'Additives (preservatives, dyes)', 'classification' => 'Food', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Dust mites', 'classification' => 'Environmental', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Mold spores', 'classification' => 'Environmental', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Pollen (grass, trees, weeds)', 'classification' => 'Environmental', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Flea saliva (flea bites)', 'classification' => 'Environmental', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Perfumes and cleaning agents', 'classification' => 'Environmental', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Wool and synthetic fabrics', 'classification' => 'Environmental', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Smoke (cigarette smoke)', 'classification' => 'Environmental', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Rubber and plastic materials', 'classification' => 'Environmental', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Feather bedding', 'classification' => 'Environmental', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Grass (contact allergy)', 'classification' => 'Environmental', 'species_affected' => 'Dog'],
            ['allergen' => 'Sodium lauryl sulfate (SLS)', 'classification' => 'Shampoo Ingredient', 'species_affected' => 'Dog'],
            ['allergen' => 'Cocamidopropyl betaine', 'classification' => 'Shampoo Ingredient', 'species_affected' => 'Dog'],
            ['allergen' => 'Parabens', 'classification' => 'Shampoo Ingredient', 'species_affected' => 'Dog'],
            ['allergen' => 'Artificial fragrances', 'classification' => 'Shampoo Ingredient', 'species_affected' => 'Dog'],
            ['allergen' => 'Essential oils (e.g., tea tree)', 'classification' => 'Shampoo Ingredient', 'species_affected' => 'Dog'],
            ['allergen' => 'Dust', 'classification' => 'Environmental', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Antibiotics (e.g., penicillin, sulfa drugs)', 'classification' => 'Medical', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'NSAIDs (e.g., aspirin, ibuprofen)', 'classification' => 'Medical', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Vaccines (e.g., rabies, distemper)', 'classification' => 'Medical', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Topical treatments (e.g., flea/tick products)', 'classification' => 'Medical', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Anesthesia agents (e.g., thiopental)', 'classification' => 'Medical', 'species_affected' => 'Dog'],
            ['allergen' => 'Corticosteroids', 'classification' => 'Medical', 'species_affected' => 'Dog & Cat'],
            ['allergen' => 'Hormonal medications (e.g., estrogen)', 'classification' => 'Medical', 'species_affected' => 'Dog']
        ];

        foreach ($allergens as $allergen) {
            DB::table('pet_allergens')->insert($allergen);
        }
    }
}
