<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'turkish_description', 'category', 'data_type', 'fdc_id', 'photo'];

    public function nutrients()
    {
        return $this->hasMany(FoodNutrient::class);
    }
}
