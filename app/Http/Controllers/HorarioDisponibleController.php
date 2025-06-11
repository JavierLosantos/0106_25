<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HorarioDisponible;

use Carbon\Carbon;
class HorarioDisponibleController extends Controller
{
    public function index()
    {
        $tenant = session('tenant');
        $horarios = HorarioDisponible::where('tenant_id',$tenant->id)->orderBy('fecha', 'desc')->get();
        return view('horarios.index', compact('horarios'));
    }

    public function create()
    {
        return view('horarios.create');
    }



    public function destroy($id)
    {
        HorarioDisponible::findOrFail($id)->delete();
        return redirect()->route('horarios.index')->with('success', 'Horario eliminado correctamente.');
    }
    // app/Http/Controllers/HorarioController.php



public function store(Request $request)
{
    $request->validate([
        'fecha' => 'required|date',
        'hora_inicio' => 'required|date_format:H:i',
        'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
    ]);
    $tenant = session('tenant');
    // Convertir las horas de inicio y fin en objetos Carbon
    $horaInicio = Carbon::createFromFormat('H:i', $request->hora_inicio);
    $horaFin = Carbon::createFromFormat('H:i', $request->hora_fin);

    // Generar intervalos de 30 minutos
    while ($horaInicio->lt($horaFin)) {
        // Crear un nuevo horario disponible
        $horarioDisponible = new HorarioDisponible();
        $horarioDisponible->fecha = $request->fecha;
        $horarioDisponible->hora_inicio = $horaInicio->format('H:i');
        $horarioDisponible->hora_fin = $horaInicio->copy()->addMinutes(30)->format('H:i'); // Copiar antes de modificar
        $horarioDisponible->estado = 'disponible'; // Agregar el estado siempre como 'disponible'
        $horarioDisponible->tenant_id = $tenant->id;
        $horarioDisponible->save();

        // Avanzar 30 minutos para la siguiente iteración
        $horaInicio->addMinutes(30);
    }

    return redirect()->route('horarios.index')->with('success', 'Horario creado con éxito');
}

public function obtenerHorasDisponibles($fecha)
{
    // Asegúrate de que la columna 'fecha' existe en tu base de datos
    // Si la columna se llama diferente, cámbiala aquí

    // Obtener los horarios disponibles para una fecha determinada
    $horariosDisponibles = HorarioDisponible::whereDate('fecha', $fecha)
        ->where('estado', 'disponible')  // Asegúrate de que el campo 'estado' existe en la tabla
        ->get();

    // Si los horarios existen, devuelve una lista de horas de inicio
    $horas = $horariosDisponibles->map(function($horario) {
        return $horario->hora_inicio;  // Ajusta esto según lo que necesites mostrar (hora inicio, duración, etc.)
    });

    // Responde con las horas disponibles
    return response()->json(['horas' => $horas]);
}

public function storen(Request $request)
{
    $request->validate([
        'fecha' => 'required|date',
        'hora_inicio' => 'required|date_format:H:i',
        'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
    ]);
    $tenant = session('tenant');
    // Convertir las horas de inicio y fin en objetos Carbon
    $horaInicio = Carbon::createFromFormat('H:i', $request->hora_inicio);
    $horaFin = Carbon::createFromFormat('H:i', $request->hora_fin);

    // Generar intervalos de 30 minutos
    while ($horaInicio->lt($horaFin)) {
        // Crear un nuevo horario disponible
        $horarioDisponible = new HorarioDisponible();
        $horarioDisponible->fecha = $request->fecha;
        $horarioDisponible->hora_inicio = $horaInicio->format('H:i');
        $horarioDisponible->hora_fin = $horaInicio->copy()->addMinutes(30)->format('H:i'); // Copiar antes de modificar
        $horarioDisponible->estado = 'disponible'; // Agregar el estado siempre como 'disponible'
        $horarioDisponible->tenant_id = $tenant->id;
        $horarioDisponible->save();

        // Avanzar 30 minutos para la siguiente iteración
        $horaInicio->addMinutes(30);
    }

    return redirect()->route('cita.createnew')->with('success', 'Horario creado con éxito');
}


}
