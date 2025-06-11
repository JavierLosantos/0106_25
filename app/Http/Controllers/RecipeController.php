<?php

namespace App\Http\Controllers;
use App\Helpers\Translator;
use Illuminate\Http\Request;
use App\Services\SpoonacularService;

class RecipeController extends Controller
{
    protected $spoonacular;

    public function __construct(SpoonacularService $spoonacular)
    {
        $this->spoonacular = $spoonacular;
    }

    public function index(Request $request)
    {
        $query = $request->get('query', 'chicken'); // valor por defecto
        $recipes = $this->spoonacular->searchRecipes($query);

        return view('recipes.index', [
            'recipes' => $recipes['results'] ?? [],
            'query' => $query,
        ]);
    }
    public function show($id)
    {
        $recipe = $this->spoonacular->getRecipeInformation($id);
    
         /* if ($recipe) {
            $recipe['summary'] = Translator::toSpanish($recipe['summary']);
            $recipe['instructions'] = Translator::toSpanish($recipe['instructions'] ?? 'Sin instrucciones');
            $recipe['title'] = Translator::toSpanish($recipe['title']);
        } */
    
        return view('recipes.show', ['recipe' => $recipe]);
    }
}
