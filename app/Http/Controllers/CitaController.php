<?php
namespace App\Http\Controllers;

use App\Models\HorarioDisponible;
use App\Models\Cita;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Session;
use App\Models\User;
use App\Models\UsuarioBono;
use Illuminate\Support\Facades\Auth;
use PDF;

class CitaController extends Controller
{
    // Mostrar la vista del calendario
    public function detalle($id)
    {
        $cita = HorarioDisponible::find($id);
    
        if (!$cita) {
            return response()->json(['error' => 'Cita no encontrada'], 404);
        }
    
        return response()->json([
            'id' => $cita->id,
            'fecha' => $cita->fecha,
            'hora_inicio' => $cita->hora_inicio,
            'hora_fin' => $cita->hora_fin,
            'estado' => $cita->estado,
        ]);
    }
    
public function index()
{
    // Obtener el ID del usuario logueado
    $userId = Auth::id();

    $citas = Cita::join('horarios_disponibles', 'citas.horario_id', '=', 'horarios_disponibles.id')
                 ->where('citas.user_id', $userId)
                 ->where('citas.estado', 'reservado')
                 ->select('citas.id', 'horarios_disponibles.hora_inicio', 'horarios_disponibles.hora_fin','horarios_disponibles.fecha','citas.estado')
                 ->get();
    // Pasar las citas a la vista
 
    return view('calendario.index', compact('citas'));
}

    // Obtener los horarios disponibles como eventos para FullCalendar
    public function obtenerHorarios()
    {
        $horarios = HorarioDisponible::where('estado', 'disponible')->get();

        $events = $horarios->map(function ($horario) {
            return [
                'id' => $horario->id,
                'title' => 'Disponible',
                'start' => $horario->fecha . 'T' . $horario->hora_inicio,
                'end' => $horario->fecha . 'T' . $horario->hora_fin,
                'estado' => $horario->estado,
            ];
        });
        
        return response()->json($events);
    }

    public function crearCitanew(Request $request)
    {
        $tenant = session('tenant');
        
        // Obtener todos los usuarios
        $usuarios = User::all();
        $horarios = HorarioDisponible::where('estado', 'disponible')->whereDate('fecha', Carbon::today())
        ->get();
        
        return view('cita_datos.createnew', [
            'usuarios' => $usuarios,
            'horarios' =>$horarios, // Pasamos solo los usuarios
        ]);
    }




    public function getBonos($user_id)
    {
        // Obtener el usuario
        $usuario = User::find($user_id);
    
        // Obtener los bonos del usuario con sesiones restantes mayores a 0
        $bonos = $usuario->bonos()->wherePivot('sesiones_restantes', '>', 0)->get();
    
        // Retornar los bonos en formato JSON
        return response()->json(['bonos' => $bonos]);
    }
    

    public function crearCitastore(Request $request)
    {
      
        // Crear la cita
       
        $cita = new Cita();
        $cita->user_id = $request['user_id'];
        $cita->horario_id = $request['horario_id'];
        $cita->estado = "reservado";
        $cita->tenant_id = session('tenant')->id; // Asignar tenant_id
        $cita->Bono = $validated['bono_id'] ?? null; // Si no se selecciona bono, quedará como null
        $cita->save();

        $bonos = UsuarioBono::where('user_id',$request['user_id'])->with('bono')->get();

         // Cambiar el estado del horario a "ocupado"
        $horario = HorarioDisponible::findOrFail($request['horario_id']);
       
        $horario->update(['estado' => 'reservado']);

        // Redirigir a la función create en el controlador CitaDatosController
        return redirect()->route('citas.datos.create', ['id' => $cita->id]);
    }
    


    // Crear una cita (reservar un horario)
    public function crearCita(Request $request)
    {
        $tenant = session('tenant');
       
        // Valida los datos
        $request->validate([
            'user_id' => 'required|integer',
            'horario_disponible_id' => 'required|exists:horarios_disponibles,id',
        ]);

        // Cambiar el estado del horario a "ocupado"
        $horario = HorarioDisponible::findOrFail($request->horario_disponible_id);
        $horario->update(['estado' => 'ocupado']);
      
        // Crear la cita
        $cita = Cita::create([
            'user_id' => $request->user_id,
            'horario_id' => $request->horario_disponible_id,
            'estado' => 'reservado',
            'tenant_id'=> $tenant->id,
        ]);
        $userId = Auth::id();

        $citas = Cita::join('horarios_disponibles', 'citas.horario_id', '=', 'horarios_disponibles.id')
                     ->where('citas.user_id', $userId)
                     ->where('citas.estado', 'reservado')
                     ->select('citas.id', 'horarios_disponibles.hora_inicio', 'horarios_disponibles.hora_fin','horarios_disponibles.fecha','citas.estado')
                     ->get();
        // Pasar las citas a la vista
     
        return redirect()->route('calendario.index')->with('success', 'Cita Creada exitosamente');
    }

    public function obtenerHorariosPorFecha($fecha)
{
    // Asegúrate de que la fecha esté correctamente formateada
    $fecha = \Carbon\Carbon::parse($fecha)->format('Y-m-d');

    $horarios = HorarioDisponible::where('fecha', $fecha)
        ->where('estado', 'disponible')
        ->get();

    if ($horarios->isEmpty()) {
        return response()->json(['horas' => []]); // Si no hay horarios, devuelve un array vacío
    }

    return response()->json(['horas' => $horarios]);
}
public function citasReservadas($userId)
{
    $citas = Cita::where('citas.user_id', $userId)  // Especificamos 'citas' para el user_id
                 ->where('citas.estado', 'reservado')  // Especificamos 'citas' para el estado
                 ->join('horarios_disponibles', 'citas.horario_id', '=', 'horarios_disponibles.id')
                 ->get([
                     'horarios_disponibles.hora_inicio', 
                     'horarios_disponibles.hora_fin', 
                     'citas.estado'
                 ]);

    return response()->json(['citas' => $citas]);
}


// En tu controlador de Citas
public function cancelarCita($id)
{
    
      $cita = Cita::findOrFail($id);


    if (!$cita) {
        return response()->json(['mensaje' => 'Cita no encontrada'], 404);
    }

   

    // Cambiar el estado de la cita a cancelada
    $cita->estado = 'cancelada';
    $cita->save();

    // Actualizar el estado del horario a disponible
    $horario = HorarioDisponible::find($cita->horario_id);
    if ($horario) {
        $horario->estado = 'disponible';
        $horario->save();
    }
       // Obtener el ID del usuario logueado
    $userId = auth()->id();

    // Obtener las citas del usuario
    $citas = Cita::where('user_id', $userId)->get();


    return redirect()->route('calendario.index')->with('success', 'Cita cancelada exitosamente');
}

public function citasSemanales()
{
    // Obtener el rango de fechas de la semana actual
    $startOfWeek = Carbon::now()->startOfWeek(); // Lunes
    $endOfWeek = Carbon::now()->endOfWeek(); // Domingo
     $tenant = session('tenant');
  $citas = DB::table('VW_CITAS_USER')
        ->where('tenant', $tenant->id)// Filtrar citas de la semana actual
        ->get();
    return view('calendario.semanales', compact('citas'));
}

public function listarCitas(Request $request)
{
    // Filtrar citas por rango de fechas, si se proporcionan
    $query = Cita::with('usuario', 'horario') // Relación con el usuario y horario
                ->join('horarios_disponibles', 'citas.horario_id', '=', 'horarios_disponibles.id')
                ->select('citas.*', 'horarios_disponibles.fecha', 'horarios_disponibles.hora_inicio', 'horarios_disponibles.hora_fin')
                ->orderBy('horarios_disponibles.fecha')
                ->orderBy('horarios_disponibles.hora_inicio');
                
    if ($request->has('fechaInicio') && $request->has('fechaFin')) {
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');
        $query->whereBetween('horarios_disponibles.fecha', [$fechaInicio, $fechaFin]);
    }

    // Cambiar de get() a paginate() para obtener los resultados paginados
    $citas = $query->paginate(10);  // Paginación para DataTables

    return view('calendario.semanales', compact('citas'));
}
public function cambiarEstadoCita(Request $request, $idCita)
{
       // Comenzamos una transacción para asegurar que ambas tablas se actualicen correctamente
    DB::beginTransaction();

    try {
        // Actualizar el estado en la tabla Citas
        DB::table('citas')
            ->where('id', $idCita)
            ->update(['estado' => 'Finalizado']);
        
        // 2. Buscar el horario_id correspondiente en la tabla horarios_disponibles
        $horario = DB::table('citas')
            ->where('id', $idCita) // Usamos el mismo id que en citas
            ->first();

        // 3. Si encontramos el horario, actualizar su estado
        if ($horario) {
            DB::table('horarios_disponibles')
                ->where('id', $horario->horario_id) // Actualizamos el horario por su ID
                ->update(['estado' => 'Finalizado']);
        }

        // Confirmamos la transacción
        DB::commit();

        // Mensaje de éxito para SweetAlert
         return redirect()->route('citas.semanales')->with('success', 'La cita ha sido finalizada exitosamente.');
    } catch (\Exception $e) {
        // En caso de error, revertimos la transacción
        DB::rollBack();

        // Redirigir con mensaje de error
        return redirect()->route('citas.semanales')->with('error', 'Hubo un error al finalizar la cita: ' . $e->getMessage());
    }
}


public function resumen($user_id)
{
    $citas = Cita::where('user_id', $user_id)
        ->orderBy('created_at')
        ->with('datos') // asumiendo relación Cita -> cita_datos
        ->get();

    if ($citas->isEmpty()) {
        return view('consultas.resumen')->with('mensaje', 'Este usuario aún no tiene sesiones.');
    }

    return view('consultas.resumen', compact('citas'));
}


}
