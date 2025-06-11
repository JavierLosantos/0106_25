<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Food;
use App\Models\Recipe;
use App\Models\User;
use Carbon\Carbon;
use App\Models\MenuDia;
use Barryvdh\DomPDF\Facade\Pdf;


class WeeklyMenuController extends Controller
{
   public function index(Request $request)
{
    $weekStart = $request->query('week_start', Carbon::now()->startOfWeek()->toDateString());
    $usuarioId = $request->query('usuario_id', auth()->id());
    $tipo = $request->query('tipo', 'Producto');

    $usuario = User::findOrFail($usuarioId);

    $menu = Menu::firstOrCreate(
        ['user_id' => $usuarioId, 'week_start' => $weekStart],
        ['Tipo' => $tipo]
    );

    $menu->update(['Tipo' => $tipo]); // Siempre actualiza el tipo seleccionado

    $menu->load('items');

 
    // En el controlador antes de pasarlo a la vista
$foods = Food::all()->keyBy('id'); // name, calories, proteina_g disponibles

    $recipes = Recipe::select('id', 'name', 'total_calories', 'total_protein_g')->get();


    return view('menu.weekly', compact('menu', 'foods', 'recipes', 'weekStart', 'usuario', 'tipo'));
}

public function storeBulk(Request $request, Menu $menu)
{
   
    $usuario_id = $request->input('usuario_id', auth()->id());
    $tipo = $request->input('tipo', 'Producto');

    foreach ($request->input('items', []) as $key => $group) {
        [$day, $meal] = explode('_', $key);
        $ids = $group['ids'] ?? [];
        $quantities = $group['weights'] ?? [];  // Los pesos enviados desde el formulario

        $totalCalories = 0;
        $totalProtein = 0;
        $totalFat = 0;
        $totalCarbs = 0;

        if ($tipo === 'Producto') {
            $productos = Food::whereIn('id', $ids)->get()->keyBy('id');

            foreach ($ids as $index => $id) {
                $cantidad = floatval($quantities[$index] ?? 0); // gramos
                $producto = $productos[$id] ?? null;

                if ($producto && $cantidad > 0) {
                    $factor = $cantidad / 100;
                    $totalCalories += $producto->calories * $factor;
                    $totalProtein += $producto->proteina_g * $factor;
                    $totalFat += $producto->grasas_g * $factor;
                    $totalCarbs += $producto->cho_t_g * $factor;  // carbohidratos
                }
            }
        } else {
            $recetas = Recipe::whereIn('id', $ids)->get()->keyBy('id');

            foreach ($ids as $index => $id) {
                $cantidad = floatval($quantities[$index] ?? 0);
                $receta = $recetas[$id] ?? null;

                if ($receta && $cantidad > 0) {
                    $factor = $cantidad / 100;
                    $totalCalories += $receta->total_calories * $factor;
                    $totalProtein += $receta->total_protein_g * $factor;
                    $totalFat += $receta->total_fat_g * $factor;
                    $totalCarbs += $receta->total_cho_t_g * $factor; // carbohidratos de la receta
                }
            }
        }

        MenuDia::updateOrCreate(
            [
                'menu_id' => $menu->id,
                'usuario_id' => $menu->user_id,
                'day' => $day,
                'meal' => $meal,
                'tipo' => $tipo,
            ],
            [
                'items' => $ids,
                'quantities' => $quantities,
                'total_calories' => round($totalCalories, 2),
                'total_protein_g' => round($totalProtein, 2),
                'total_fat_g' => round($totalFat, 2),
                'total_cho_t_g' => round($totalCarbs, 2), // asegúrate de agregar esta columna en la tabla
            ]
        );
    }

    return redirect()->route('menu.edit', [
        'menu' => $menu->id,
        
    ])->with('success', 'Menú actualizado correctamente');
}


public function edit(Menu $menu)
{
    $usuario = $menu->user;
    $weekStart = $menu->week_start;
    $tipo = $menu->Tipo;

    $menuDias = MenuDia::where('menu_id', $menu->id)
        ->where('tipo', $tipo)
        ->get()
        ->keyBy(fn($item) => $item->day . '_' . $item->meal);

    // Cargar todos los productos o recetas, no solo los usados
    if ($tipo === 'Producto') {
        $foods = Food::all()->keyBy('id');
        $recipes = collect(); // vacío si no se usan recetas
    } else {
        $recipes = Recipe::select('id', 'name', 'total_calories', 'total_protein_g')
            ->get()
            ->keyBy('id');
        $foods = collect(); // vacío si no se usan productos
    }

    return view('menu.weekly_edit', compact(
        'menu',
        'usuario',
        'weekStart',
        'tipo',
        'foods',
        'recipes',
        'menuDias'
    ));
}



public function updateBulk(Request $request, Menu $menu)
{
  
    $itemsData = $request->input('items', []);
    $tipo = $menu->Tipo;

    foreach ($itemsData as $key => $data) {
        [$day, $meal] = explode('_', $key);
        $itemIds = $data['ids'] ?? [];
        $quantities = $data['quantities'] ?? [];

        // Normalizar: quitar vacíos
        $cleanedIds = array_filter($itemIds);
        $cleanedQuantities = array_values(array_filter($quantities, fn($q) => $q !== null && $q !== ''));

        // Buscar si ya existe un registro para este día y comida
        $menuDia = MenuDia::where('menu_id', $menu->id)
            ->where('day', $day)
            ->where('meal', $meal)
            ->where('tipo', $tipo)
            ->first();

        // Si no hay items, borrar si existía
        if (empty($cleanedIds)) {
            if ($menuDia) {
                $menuDia->delete();
            }
            continue;
        }

        // Calcular nutrición total (simplificado)
        $totalCalories = 0;
        $totalProtein = 0;

        if ($tipo === 'Producto') {
            $foods = Food::whereIn('id', $cleanedIds)->get()->keyBy('id');
            foreach ($cleanedIds as $i => $id) {
                $food = $foods[$id] ?? null;
                $qty = $cleanedQuantities[$i] ?? 0;
                if ($food) {
                    $totalCalories += $food->calories * ($qty / 100);
                    $totalProtein += $food->proteina_g * ($qty / 100);
                }
            }
        } else {
            $recipes = Recipe::whereIn('id', $cleanedIds)->get()->keyBy('id');
            foreach ($cleanedIds as $i => $id) {
                $recipe = $recipes[$id] ?? null;
                $qty = $cleanedQuantities[$i] ?? 0;
                if ($recipe) {
                    $totalCalories += $recipe->total_calories * ($qty / 100);
                    $totalProtein += $recipe->total_protein_g * ($qty / 100);
                }
            }
        }

        // Guardar o actualizar
        if ($menuDia) {
            $menuDia->update([
                'items' => $cleanedIds,
                'quantities' => $cleanedQuantities,
                'total_calories' => $totalCalories,
                'total_protein_g' => $totalProtein,
            ]);
        } else {
     
            MenuDia::create([
                'menu_id' => $menu->id,
                'usuario_id' => $menu->user_id,
                'day' => $day,
                'meal' => $meal,
                'tipo' => $tipo,
                'items' => $cleanedIds,
                'quantities' => $cleanedQuantities,
                'total_calories' => $totalCalories,
                'total_protein_g' => $totalProtein,
            ]);
        }
    }

    return redirect()->route('menu.edit', $menu->id)->with('success', 'Menú actualizado correctamente.');
}


public function generarPDF($id)
{
    

    
    $menu = Menu::where('id',$id)->first();
       
     $usuario =$menu->user_id;
    $weekStart = $menu->week_start;
    $tipo = $menu->Tipo;
           
    $menuDias = MenuDia::where('menu_id', $menu->id)
        ->where('tipo', $tipo)
        ->get()
        ->keyBy(fn($item) => $item->day . '_' . $item->meal);

    if ($tipo === 'Producto') {
        $foods = Food::all()->keyBy('id');
        $recipes = collect();
    } else {
        $recipes = Recipe::select('id', 'name', 'total_calories', 'total_protein_g')
            ->get()
            ->keyBy('id');
        $foods = collect();
    }
    $usuario = User::where('id',$usuario)->first();
  
    // Cargar la vista del PDF
    $pdf = Pdf::loadView('menu.pdf', compact(
        'menu',
        'usuario',
        'weekStart',
        'tipo',
        'foods',
        'recipes',
        'menuDias'
    ))->setPaper('a4', 'landscape');

    return $pdf->stream("menu_semanal.pdf");
}


}
