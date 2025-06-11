<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'total_calories',
        'total_protein_g',
    ];

    /**
     * Relación con los alimentos.
     */
    public function foods()
    {
        return $this->belongsToMany(Food::class, 'recipe_foods')
                    ->withPivot('quantity');
    }

    /**
     * Método para calcular las calorías totales de la receta.
     */
    public function calculateTotalCalories()
    {
        $totalCalories = 0;

        foreach ($this->foods as $food) {
            $totalCalories += $food->calories * $food->pivot->quantity; // Calorías * cantidad
        }

        return $totalCalories;
    }

    /**
     * Después de crear la receta, recalcular las calorías totales.
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($recipe) {
            $recipe->total_calories = $recipe->calculateTotalCalories();
            $recipe->save();
        });
    }
}
