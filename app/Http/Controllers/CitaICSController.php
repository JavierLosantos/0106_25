<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class CitaICSController extends Controller
{
    public function descargarICS($id)
    {

        $cita = DB::table('VW_CITAS_USER')->where('ID_Cita', $id)->first();
       
        // Aseguramos que los valores no sean nulos antes de procesarlos
        if (!$cita->fecha || !$cita->hora_inicio || !$cita->hora_fin) {
            abort(404, "La cita no tiene fecha u hora válida.");
        }

        // Crear instancias Carbon con fecha y hora combinadas
        $fechaInicio = Carbon::parse($cita->fecha . ' ' . $cita->hora_inicio)->format('Ymd\THis');
        $fechaFin = Carbon::parse($cita->fecha . ' ' . $cita->hora_fin)->format('Ymd\THis');
        $fechaCreacion = Carbon::now()->format('Ymd\THis\Z'); // Hora actual en formato UTC

        // Generar contenido ICS
        $evento = "BEGIN:VCALENDAR\r\n";
        $evento .= "VERSION:2.0\r\n";
        $evento .= "PRODID:-//MiApp//Calendario//ES\r\n";
        $evento .= "BEGIN:VEVENT\r\n";
        $evento .= "UID:" . uniqid() . "\r\n";
        $evento .= "DTSTAMP:$fechaCreacion\r\n";
        $evento .= "DTSTART:$fechaInicio\r\n";
        $evento .= "DTEND:$fechaFin\r\n";
        $evento .= "SUMMARY:Cita Nutrición Nazaret\r\n";
        $evento .= "DESCRIPTION:Consulta con Nazaret\r\n";
        $evento .= "LOCATION:NutriNaza\r\n";
        $evento .= "END:VEVENT\r\n";
        $evento .= "END:VCALENDAR\r\n";

        // Generar la respuesta como archivo ICS
        return Response::make($evento, 200, [
            'Content-Type' => 'text/calendar',
            'Content-Disposition' => 'attachment; filename="cita.ics"',
        ]);
    }
}

