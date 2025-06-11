<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenFoodFactsController extends Controller
{
     public function buscar(Request $request)
    {
        $codigo = $request->input('barcode');
        // Aquí buscarías el producto en la base de datos
        return back()->with('mensaje', "Código recibido: $codigo");
    }
    public function buscarProducto(Request $request)
    {
        $barcode = $request->input('barcode'); // Código de barras ingresado por el usuario
        $response = Http::get("https://world.openfoodfacts.org/api/v2/product/$barcode.json");

        if ($response->successful()) {
            $producto = $response->json();
            if (isset($producto['product'])) {
                return view('producto', ['producto' => $producto['product']]);
            } else {
                return back()->with('error', 'Producto no encontrado.');
            }
        }

        return back()->with('error', 'Error al conectar con Open Food Facts.');
    }

  

    public function buscarPorSupermercadoYCategoria(Request $request)
    {
        $supermercado = $request->input('supermercado', 'Mercadona');
        $categoria = $request->input('categoria', 'leche');

        $url = "https://world.openfoodfacts.org/cgi/search.pl?search_terms={$categoria}&brands={$supermercado}&json=true";
        
        $response = Http::get($url);

        if ($response->successful()) {
            $productos = $response->json()['products'] ?? [];
            return view('productos.lista', compact('productos', 'supermercado', 'categoria'));
        } else {
            return redirect()->back()->with('error', 'No se pudo obtener la información.');
        }
    }
}
