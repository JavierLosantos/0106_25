<?php

namespace App\Http\Controllers;
header('Access-Control-Allow-Origin: *');

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Bono;
use Carbon\Carbon;
use App\Models\UserFile;
use App\Models\Consulta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Cita;
use App\Models\CitaDato;
use App\Models\TenantUser;
use App\Models\User;

class Apicontroller extends Controller
{
    public function login(Request $request)
    {
        // Validar entrada
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentar autenticar
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        // Obtener usuario autenticado
        $user = Auth::user();

        // Crear token
        $token = $user->createToken('API Token')->plainTextToken;
            
        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }


    public function paciente()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();
        $userId = Auth::id();
        $fechaHoy = Carbon::now()->format('d/m/Y'); // Fecha de hoy

        // Obtener la última cita finalizada del usuario
        $ultimaCita = Cita::where('user_id', $userId)
                        ->where('estado', 'Finalizado')
                        ->orderBy('id', 'desc')
                        ->first();
                        
        // Datos de la última cita finalizada
        $citadatos = $ultimaCita ? CitaDato::where('cita_id', $ultimaCita->id)->first() : null;

        // Obtener el objetivo del usuario
        $objetivo = $user->Objetivo ?? 'Sin objetivo definido';

        // Obtener las citas reservadas del usuario
        $citas = Cita::join('horarios_disponibles', 'citas.horario_id', '=', 'horarios_disponibles.id')
                    ->where('citas.user_id', $userId)
                    ->where('citas.estado', 'reservado')
                    ->select('citas.id', 'horarios_disponibles.hora_inicio', 'horarios_disponibles.hora_fin', 'horarios_disponibles.fecha', 'citas.estado')
                    ->get();

        // Obtener los bonos del usuario con sesiones restantes mayores a 0
        $bono = $user->bonos()->wherePivot('sesiones_restantes', '>', 0)->get();

        // Verificar si hay bonos disponibles
        $hayBonoActivo = $bono->isNotEmpty();

        // Retornar todos los datos como respuesta JSON
        return response()->json([
            'citas' => $citas,
            'bono' => $bono,
            'user' => $user,
            'fechaHoy' => $fechaHoy,
            'citadatos' => $citadatos,
            'objetivo' => $objetivo,
            'hayBonoActivo' => $hayBonoActivo,
        ]);
    }



}
