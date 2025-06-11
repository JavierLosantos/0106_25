<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bono;
use App\Models\Transaccion;
use App\Models\User;
use App\Models\UsuarioBono;
use Illuminate\Support\Facades\Auth;

class BonoController extends Controller
{
    public function index()
    {
        $bonos = Bono::all(); // Obtiene todos los bonos
        return view('bonos.index', compact('bonos')); // Pasar los bonos a la vista
    }
 
    // Mostrar formulario para crear un bono
    public function create()
    {
        return view('bonos.create'); // Muestra el formulario de creación
    }

    // Almacenar un nuevo bono
    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric',
            'sesiones' => 'required|integer|min:1',
        ]);
        $tenant = session('tenant');
       
        // Crear un nuevo bono con los datos validados
        Bono::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'sesiones' => $request->sesiones,
            'tenant_id' =>$tenant->id, // Asumiendo que tienes tenant_id en el usuario autenticado
        ]);
    
        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('bonos.index')->with('success', 'Bono creado exitosamente');
    }

    // Mostrar formulario para editar un bono
    public function edit(Bono $bono)
    {
        return view('bonos.edit', compact('bono')); // Muestra el formulario de edición
    }

    // Actualizar un bono
    public function update(Request $request, Bono $bono)
    {
        // Validación de los datos
        $request->validate([
            'codigo' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'fecha_expiracion' => 'required|date',
        ]);

        // Actualizar el bono
        $bono->update($request->all());

        // Redirigir a la lista de bonos
        return redirect()->route('bonos.index')->with('success', 'Bono actualizado exitosamente');
    }

    // Eliminar un bono
    public function destroy(Bono $bono)
    {
        // Eliminar el bono
        $bono->delete();

        // Redirigir a la lista de bonos
        return redirect()->route('bonos.index')->with('success', 'Bono eliminado exitosamente');
    }

    public function assignBono(Request $request) {
  
    UsuarioBono::create([
        'user_id' => $request->user,
        'bono_id' => $request->bono_id,
        'sesiones_restantes' => Bono::find($request->bono_id)->sesiones,
        'pagado'=>$request->pagado,
    ]);
     // Obtener el precio del bono
     $bono = Bono::find($request->bono_id);
     $precio = $bono->precio; // Suponiendo que la tabla Bono tiene un campo 'precio'
  $tenant = session('tenant');
     // Registrar la transacción si está pagado
     if ($request->pagado) {
         Transaccion::create([
             'tipo' => 'ingreso',
             'monto' => $precio,
             'descripcion' => "Pago de bono: " . $bono->nombre, // Suponiendo que el bono tiene un campo 'nombre'
             'fecha' => now(),
             'id_tenant' => $tenant->id,
         ]);
     }

    // Obtener el tenant activo desde la sesión
    $tenant = session('tenant');
    $role = session('role');
    if ($tenant) {
        // Obtener los usuarios asociados a este tenant
        $users = User::join('tenant_user', 'tenant_user.user_id', '=', 'users.id')
                     ->where('tenant_user.tenant_id', '=', $tenant->id)
                     ->get();
                     
    } else {
        // Si no hay un tenant activo, manejar el error (redirigir o mostrar mensaje)
        return redirect('/')->withErrors(['error' => 'No se ha seleccionado un tenant.']);
    }
        $role = Auth::user()->role;
        
        if ($role == 'Admin'){
            $bonos = Bono::get();
    return view('users.user', ['resultados' => $users,'bonos'=>$bonos]);
        }elseif($role == 'Paciente'){
            $ids = Auth::user()->id;
            $users = User::where('id',$ids)->get()->first();
            return view('/users/edit',  ['resultados' => $users]);

        }
}
public function assignBonos($id, Request $request)
{
    // Obtener los bonos disponibles
    $bonos = Bono::all(); 
    
    // Obtener el usuario por su ID
    $user = User::find($id);

    if (!$user) {
        return redirect()->route('users.index')->with('error', 'Usuario no encontrado');
    }

    // Si se ha enviado el formulario, se asigna el bono
    if ($request->isMethod('post')) {
        $request->validate([
            'bono_id' => 'required|exists:bonos,id',
            'pagado' => 'required|in:SI,NO',
        ]);

        // Crear la relación entre el usuario y el bono, si existe la relación en la base de datos
        $user->bonos()->attach($request->bono_id, ['pagado' => $request->pagado]);

        return redirect()->route('users.index')->with('success', 'Bono asignado correctamente');
    }

    return view('bonos.asignar', ['user' => $user, 'bonos' => $bonos]);
}

public function showBonosDeTodosLosUsuarios()
{
    // Obtener todos los usuarios
    $usuarios = User::all();

    // Recorrer cada usuario y obtener sus bonos con sesiones restantes
    $usuariosConBonos = [];

    foreach ($usuarios as $usuario) {
       $bonosConSesiones = $usuario->bonos()->wherePivot('sesiones_restantes', '>', 0)->get();

        if ($bonosConSesiones->isNotEmpty()) {
            $usuariosConBonos[] = [
                'usuario' => $usuario,
                'bonos' => $bonosConSesiones
            ];
        }
    }

    return view('bonos.show', ['usuariosConBonos' => $usuariosConBonos]);
}



}
