<?php

namespace App\Http\Controllers;

use App\Models\Bono;
use App\Models\Cabecerapreoperativoencargado;
use App\Models\Cabecerapreoperativoencargado_manual;
use App\Models\Cabecerapreoperativoencargado_seleccion;
use App\Models\cabecerapreoperativoencargado_topping;
use App\Models\Cabecerapreoperativooperario;
use App\Models\Cabecerapreoperativooperario_topping;
use App\Models\Ccoad;
use App\Models\Ctemp;
use App\Models\User as User;
use App\Models\v_resultado_coad;
use App\Models\v_resultado_temp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\UserFile;
use App\Models\Consulta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Cita;
use App\Models\CitaDato;
use App\Models\TenantUser;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    



  
    public function user()
    {
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
      public function paciente()
    {
        // Obtener el tenant activo desde la sesión
        $user = Auth::user();
        $tenant = session('tenant');
        $userId = Auth::id();
        $fechaHoy = Carbon::now()->format('d/m/Y'); // Fecha Actualizar 
  // Última cita finalizada del usuario
        $ultimaCita = Cita::where('user_id', $userId)
                        ->where('estado', 'Finalizado')
                        ->orderBy('id', 'desc')
                        ->first();
                    
                        
                        // Datos de la última cita finalizada
    $citadatos = $ultimaCita ? CitaDato::where('cita_id', $ultimaCita->id)->first() : null;
  // Datos de usuario (Objetivo)
    $objetivo = $user->Objetivo ?? 'Sin objetivo definido';
    // Obtener las citas reservadas del usuario autenticado
    $citas = Cita::join('horarios_disponibles', 'citas.horario_id', '=', 'horarios_disponibles.id')
                ->where('citas.user_id', $userId)
                ->where('citas.estado', 'reservado')
                ->select('citas.id', 'horarios_disponibles.hora_inicio', 'horarios_disponibles.hora_fin', 'horarios_disponibles.fecha', 'citas.estado')
                ->get();
                
 // Obtener los bonos del usuario con sesiones restantes mayores a 0
        $bono = $user->bonos()->wherePivot('sesiones_restantes', '>', 0)->get();
   // Verificar si hay bonos disponibles
    $hayBonoActivo = $bono->isNotEmpty();
    return view('dashboard1', compact('citas','bono','user', 'fechaHoy', 'citadatos', 'objetivo','hayBonoActivo'));

    
    }
    public function admin()
    {
        $user = Auth::user();
        
        $userId = Auth::id();
        $tenant = 1 ;
        $fechaHoy = Carbon::now();
        
        // Semana actual
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
 
        // Citas con estado reservado Y fuera de la semana actual
        $citas = Cita::with('horario')
            ->where('tenant_id', $tenant)
            ->where('estado', 'reservado')
            ->whereHas('horario', function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereNotBetween('fecha', [$startOfWeek, $endOfWeek]);
            })
            ->get();
    
        // Formato para FullCalendar
        $eventos = $citas->map(function ($cita) {
            return [
                'title' => 'Cita con usuario #' . $cita->user_id,
                'start' => $cita->horario->fecha . 'T' . $cita->horario->hora_inicio,
                'end' => $cita->horario->fecha . 'T' . $cita->horario->hora_fin,
            ];
        });
    
        // Consultas dentro de la semana actual
        $consultas = Consulta::where('tenant_id', $tenant)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get();
    
        return view('dashboard', compact('eventos', 'consultas'));
    }


    public function delete($id){

        $cat = User::where('id',$id)->get()->first();
        $cat->delete();
        $cat = TenantUser::where('user_id',$id)->get()->first();
        $cat->delete();

        $users = User::where('id', '<>', 1)->get();
                
        return view('users.user', ['resultados' => $users]);
    }

    public function edit($id){

        $users = User::where('id',$id)->get()->first();
        return view('/users/edit', ['resultados' => $users]);
    }

   public function update(Request $request, $id)
{
    

    

    // Buscar el usuario por ID
    $usuario = User::findOrFail($id);

    // Actualizar los datos del usuario
    $usuario->name = $request->input('name');
    $usuario->email = $request->input('email');
    $usuario->telefono = $request->input('telefono');
    $usuario->genero = $request->input('genero');
    $usuario->peso = $request->input('peso');
    $usuario->altura = $request->input('altura');
    $usuario->tipo_alimentacion = $request->input('tipo_alimentacion');
    $usuario->deportista = $request->input('deportista');
    $usuario->role = $request->input('role');
    if( $usuario->role === null){
        $usuario->role = "Paciente";
    }

    // Solo actualizar la contraseña si se proporciona una nueva
    if ($request->filled('password')) {
        $usuario->password = Hash::make($request->input('password'));
    }

    // Guardar cambios
    $usuario->save();

    return redirect()->route('user.table')->with('success', 'Usuario actualizado correctamente');
}


    public function crear()
    {
        return view('users.crear');
    }
    public function store(Request $request)
{
   // Obtener el tenant activo desde la sesión
   $tenant = session('tenant');

    // Creación del usuario
    $user = new User();
    $user->name              = $request->input('name');
    $user->email             = $request->input('email');
    $user->role              = $request->input('role');
    $user->telefono          = $request->input('telefono');
    $user->fecha_nacimiento  = $request->input('fecha_nacimiento');
    $user->genero            = $request->input('genero');
    $user->peso              = $request->input('peso');
    $user->altura            = $request->input('altura');
    $user->tipo_alimentacion = $request->input('tipo_alimentacion');
    $user->deportista        = $request->input('deportista');
    $user->password          = Hash::make($request->input('password'));
    $user->save();
   
    // Asignar el usuario al tenant
    $user->tenants()->attach($tenant->id);
    
    return redirect()->route('user.table')->with('success', 'Usuario creado correctamente');
}


    public function setting()
    {
        $currentUser = auth()->user();
       
        $users = User::where('id', $currentUser->id)->get()->first();
        return view('users.edit', ['resultados' => $users]);
    }
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status === 'activo' ? 'inactivo' : 'activo';
        $user->save();
    
        return back()->with('success', 'Estado del usuario actualizado.');
    }

    public function profile($id)
    {
        $usuario = User::findOrFail($id);
        return view('users.profile', compact('usuario'));
    }
    public function logout(Request $request)
    {
        Auth::logout(); // Log out the user

        // Redirect to the home page or any other page you prefer after logging out
        return redirect('/'); // Adjust the redirect as needed
    }


}
