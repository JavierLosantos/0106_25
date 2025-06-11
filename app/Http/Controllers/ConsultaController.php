<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Consulta;
use App\Models\Imagen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Cita;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
class ConsultaController extends Controller
{
    


public function index()
{
    $consultas = Consulta::with('user')->where('user_id', Auth::id())->get();
   
    return view('consultas.index', compact('consultas'));
}


    public function create()
    {
        return view('consultas.create');
    }

public function store(Request $request)
{
    $request->validate([
        'texto_largo' => 'required|string',
        'imagenes' => 'required|array|max:5',
        'imagenes.*' => 'image|mimes:jpg,jpeg,png,gif,pdf,doc,docx',
    ]);
     $tenant = session('tenant');
     $tenant = $tenant->id;
    
    // Crear la consulta
    $consulta = new Consulta();
    $consulta->user_id =auth()->id();
    $consulta->tenant_id =  $tenant;
    $consulta->texto_largo = $request->texto_largo;
    $consulta->Revisado = 0;
    $consulta->save();

    // Subir las imágenes
    if ($request->hasFile('imagenes')) {
        $userId = auth()->id();
        $destinationPath =  "/uploads/{$userId}";

        // 🔹 Asegurar que la ruta sea correcta
        $destinationPath = str_replace('//', '/', $destinationPath); // Evita barras dobles

        // 🔹 Verificar si la carpeta "uploads/{userId}" existe, si no, crearla
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
          
        foreach ($request->file('imagenes') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName); // Mueve la imagen a la carpeta del usuario

            // 🔹 Guardar la ruta relativa en la BD
            Imagen::create([
                'consulta_id' => $consulta->id,
                'user_id' => $userId,
                'imagen_path' => "uploads/{$userId}/{$fileName}", // Ruta accesible desde la web
            ]);
        }
    }

    return redirect()->route('consultas.index')->with('success', 'Consulta creada con éxito');
}
public function validar(Request $request)
{
    $selected = $request->input('selected', []);

    // Verifica si es string y conviértelo en array
    if (!is_array($selected)) {
        $selected = [$selected];
    }

    if (count($selected)) {
        Consulta::whereIn('id', $selected)->update(['Revisado' => 1]);
    }

    return redirect()->back()->with('success', 'Revision Validada.');
}

public function usuariosConConsultas()
{
    $usuarios = DB::select("SELECT * FROM vw_usuarios_con_registros");
    
    return view('consultas.usuarios', compact('usuarios'));
}

public function consultasPorUsuario(User $user)
{
    // Obtener consultas del usuario seleccionado con sus imágenes
    $consultas = Consulta::where('user_id', $user->id)->with('imagenes')->get();
       
    return view('consultas.indexusuarios', compact('user', 'consultas'));
}
    // Otros métodos como show, edit, update, destroy pueden ir aquí según lo necesites.

public function obtenerCitas()
{
    $citas = Cita::with('horario')  // Obtener las citas con los horarios
        ->where('user_id', auth()->id())  // Solo las citas del usuario logueado
        ->get();

    return response()->json($citas);
}

public function citasPorUsuario(User $user)
{
  
   $citas = DB::table('VW_CITAS_FIN')->where('id_user', $user->id)->get();
   return view('consultas.consultausuario', compact('citas'));

}
public function citasPorUsuario1()
{
  
    $userId = auth()->id();

   $citas = DB::table('VW_CITAS_FIN')->where('id_user',$userId)->get();
   return view('consultas.consultausuario', compact('citas'));

}

public function menusPorUsuario($usuarioId)
{
    $usuario = User::findOrFail($usuarioId);

    // Asegúrate de tener una relación entre Usuario y Menus, o ajusta según tu modelo
    $menus = Menu::where('user_id', $usuarioId)->get();

    return view('consultas.menu', compact('usuario', 'menus'));
}

}
