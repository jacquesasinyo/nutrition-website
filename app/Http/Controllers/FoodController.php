<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class FoodController extends Controller
{
    private function handlePhotoUpload($file)
    {
        if (!$file) {
            return null;
        }

        // Ensure the storage directory exists
        $storage_path = storage_path('app/public/foods');
        if (!File::exists($storage_path)) {
            File::makeDirectory($storage_path, 0775, true);
        }

        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = $file->getClientOriginalExtension();
        $fileToStore = $filename . '_' . time() . '.' . $extension;

        // Move the file directly to ensure it's stored
        if ($file->move($storage_path, $fileToStore)) {
            return '/storage/foods/' . $fileToStore;
        }

        return null;
    }

    public function index(Request $request)
    {
        $query = Food::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($q) use ($searchTerm) {
                $q->where('turkish_description', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        $foods = $query->select('id', 'turkish_description', 'description', 'photo')->with('nutrients')->paginate(20);

        return view('foods.index', compact('foods'));
    }


    public function show(Food $food)
    {
        $allowedNutrients = [
            'Carbohydrate, by difference' => 'Karbonhidrat',
            'Protein' => 'Protein',
            'Total lipid (fat)' => 'Yağ',
            'Fiber, total dietary' => 'Lif',
            'Cholesterol' => 'Kolesterol',
            'Sodium, Na' => 'Sodyum',
            'Potassium, K' => 'Potasyum',
            'Calcium, Ca' => 'Kalsiyum',
            'Vitamin A, RAE' => 'Vitamin A',
            'Vitamin C, total ascorbic acid' => 'Vitamin C',
            'Iron, Fe' => 'Demir',
            'Energy' => 'Enerji (kcal)',
            'Energy (Atwater General Factors)' => 'Enerji (kcal)',
            'Energy (Atwater Specific Factors)' => 'Enerji (kcal)'
        ];

        $nutrientValues = $food->nutrients->mapWithKeys(function ($nutrient) {
            return [$nutrient->nutrient_name => $nutrient];
        });

        $nutrients = collect($allowedNutrients)->map(function ($turkishName, $englishName) use ($nutrientValues) {
            return (object) [
                'nutrient_name' => $turkishName,
                'amount' => isset($nutrientValues[$englishName]) ? $nutrientValues[$englishName]->amount : 0,
                'unit_name' => isset($nutrientValues[$englishName]) ? $nutrientValues[$englishName]->unit_name : 'g'
            ];
        });


        $energyValue = 0;
        foreach (['Energy', 'Energy (Atwater General Factors)', 'Energy (Atwater Specific Factors)'] as $energyKey) {
            if (isset($nutrientValues[$energyKey]) && $nutrientValues[$energyKey]->amount > 0) {
                $energyValue = $nutrientValues[$energyKey]->amount;
                break;
            }
        }


        return view('foods.show', compact('food', 'nutrients', 'energyValue'));
    }
    public function create()
    {
        return view('foods.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'turkish_description' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'fdc_id' => 'required|integer',
            'data_type' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $this->handlePhotoUpload($request->file('photo'));
        }

        Food::create($validatedData);

        return redirect()->route('food.index')->with('success', 'Food item created successfully!');
    }

    public function edit(Food $food, Request $request)
    {
        $page = $request->query('page', 1);
        return view('foods.edit', compact('food', 'page'));
    }
    public function update(Request $request, Food $food)
    {
        $validatedData = $request->validate([
            'turkish_description' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category' => 'nullable|string',
            'data_type' => 'nullable|string',
            'fdc_id' => 'nullable|string|unique:food,fdc_id,' . $food->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($food->photo) {
                $old_file = public_path($food->photo);
                if (File::exists($old_file)) {
                    File::delete($old_file);
                }
            }
            $validatedData['photo'] = $this->handlePhotoUpload($request->file('photo'));
        }

        $food->update($validatedData);

        $page = $request->input('page', 1);
        return redirect()->route('food.index', ['page' => $page])->with('success', 'Yiyecek başarıyla güncellendi!');
    }

    public function destroy(Food $food)
    {
        // Delete photo if exists
        if ($food->photo) {
            $file_path = public_path($food->photo);
            if (File::exists($file_path)) {
                File::delete($file_path);
            }
        }

        $food->delete();

        return redirect()->route('food.index')->with('success', 'Yiyecek başarıyla silindi!');
    }

}
