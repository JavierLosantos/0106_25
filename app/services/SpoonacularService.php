<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SpoonacularService
{
    protected $baseUrl = 'https://api.spoonacular.com';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.spoonacular.key');
    }

    public function searchRecipes($query, $number = 10)
    {
        $response = Http::get("{$this->baseUrl}/recipes/complexSearch", [
            'apiKey' => $this->apiKey,
            'query'  => $query,
            'number' => $number,
        ]);

        return $response->successful() ? $response->json() : null;
    }

    public function getRecipeInformation($id)
    {
        $response = Http::get("{$this->baseUrl}/recipes/{$id}/information", [
            'apiKey' => $this->apiKey,
        ]);

        return $response->successful() ? $response->json() : null;
    }
}
