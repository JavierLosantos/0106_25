<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;

class FoodController extends Controller
{
public function index(Request $request)
{
    $query = Food::query();

    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where('name', 'like', '%' . $search . '%');
    }

    $foods = $query->paginate(25);

    return view('foods.index', compact('foods'));
}



    public function store(Request $request)
    {
        $food = Food::create($request->all());
        return response()->json($food);
    }

    public function update(Request $request, Food $food)
    {
        $food->update($request->all());
        return response()->json(['message' => 'Actualizado']);
    }
}
