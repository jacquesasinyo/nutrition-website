<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

use App\Models\Food;

use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateFoodSeeder extends Seeder
{
    public function run()
    {
        $translator = new GoogleTranslate('tr');


        $foods = Food::all();

        foreach ($foods as $food) {
            try {

                $translatedName = $translator->translate($food->description);


                $food->update(['turkish_description' => $translatedName]);

                Log::debug("Translated: {$food->description} => {$translatedName} \n");
            } catch (\Exception $e) {
                Log::debug("Error translating {$food->description}: " . $e->getMessage() . "\n");
            }
        }
    }
}
