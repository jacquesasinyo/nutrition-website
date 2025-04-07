<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Food;
use App\Models\FoodNutrient;

class FoodSeeder extends Seeder
{
    private $translations = [
        'Apple, raw' => 'Elma, çiğ',
        'Banana, raw' => 'Muz, çiğ',
        'Chicken breast, raw' => 'Tavuk göğsü, çiğ',
        'Fruits' => 'Meyveler',
        'Poultry' => 'Kümes Hayvanları'
    ];

    public function run()
    {
        $jsonPath = database_path('data/foods.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("File not found: " . $jsonPath);
            return;
        }

        $json = File::get($jsonPath);
        $data = json_decode($json, true);

        if (!isset($data['FoundationFoods'])) {
            $this->command->error("Invalid JSON format: Missing 'FoundationFoods' key.");
            return;
        }

        foreach ($data['FoundationFoods'] as $food) {
            if (!isset($food['description'], $food['foodCategory']['description'], $food['dataType'], $food['fdcId'])) {
                $this->command->warn("Skipping food item due to missing fields: " . json_encode($food));
                continue;
            }


            $turkishDescription = $this->translateToTurkish($food['description']);

            $newFood = Food::create([
                'description' => $food['description'],
                'turkish_description' => $turkishDescription, // ✅ Ensure this is included
                'category' => $food['foodCategory']['description'],
                'data_type' => $food['dataType'],
                'fdc_id' => $food['fdcId']
            ]);

            if (isset($food['foodNutrients']) && is_array($food['foodNutrients'])) {
                foreach ($food['foodNutrients'] as $nutrient) {
                    if (isset($nutrient['nutrient']['name'])) {
                        FoodNutrient::create([
                            'food_id' => $newFood->id,
                            'nutrient_name' => $nutrient['nutrient']['name'],
                            'unit_name' => $nutrient['nutrient']['unitName'] ?? '',
                            'amount' => $nutrient['amount'] ?? 0
                        ]);
                    } else {
                        $this->command->warn("Skipping nutrient due to missing name: " . json_encode($nutrient));
                    }
                }
            }
        }
    }

    /**
     * Translate English food descriptions to Turkish using Google Translate API.
     */
    private function translateToTurkish($text)
    {
        return $text;
    }
}
