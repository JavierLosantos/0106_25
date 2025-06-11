<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MealPlannerController extends Controller
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.spoonacular.key'); // Tu API key de Spoonacular
    }

    // Vista de inicio
    public function index()
    {
        return view('menu-auto.index');
    }


public function generate(Request $request)
{
    // Obtener parámetros del formulario
    $targetCalories = $request->input('targetCalories');
    $diet = $request->input('diet');
    $cuisine = $request->input('cuisine');
    $mealType = $request->input('mealType');
    $intolerances = $request->input('intolerances');
    $excludeIngredients = $request->input('excludeIngredients');

    // API credentials
    $apiKey = config('services.spoonacular.key');

    // Preparar los parámetros de la solicitud
    $query = [
        'timeFrame' => 'week',
        'apiKey' => $apiKey,
    ];

    if ($targetCalories) $query['targetCalories'] = $targetCalories;
    if ($diet) $query['diet'] = $diet;
    if ($cuisine) $query['cuisine'] = $cuisine;
    if ($mealType) $query['type'] = $mealType;
    if ($intolerances) $query['intolerances'] = $intolerances;
    if ($excludeIngredients) $query['exclude'] = $excludeIngredients;

    // Llamar a la API de Spoonacular
    $response = Http::get('https://api.spoonacular.com/mealplanner/generate', $query);

    if ($response->successful()) {
        $mealPlan = $response->json();

        return view('menu-auto.index', [
            'mealPlan' => $mealPlan['week'],
            'targetCalories' => $targetCalories,
            'diet' => $diet,
            'cuisine' => $cuisine,
            'mealType' => $mealType,
            'intolerances' => $intolerances,
            'excludeIngredients' => $excludeIngredients,
        ]);
    } else {
        return back()->with('error', 'Error al generar el plan de comidas: ' . $response->body());
    }
}


    // Obtener los detalles completos de la receta
    public function getRecipeDetails($id)
{
    $response = Http::get("https://api.spoonacular.com/recipes/{$id}/information?includeNutrition=true", [
        'includeNutrition' => true,
        'apiKey' => $this->apiKey
        
    ]);

    if ($response->successful()) {
        $recipe = $response->json();
        // Inspecciona la estructura de la respuesta

        return view('menu-auto.recipe', [
            'recipe' => $recipe,
        ]);
    } else {
        return back()->withErrors('No se pudo obtener la receta. Intenta de nuevo.');
    }
}

}
