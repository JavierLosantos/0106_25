<?php

namespace App\Http\Controllers;
use App\Models\Transaccion;
use App\Models\TransaccionView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanzasController extends Controller
{
    public function index()
    { 
       $tenant = session('tenant');
        // Obtener ingresos y gastos totales
        $ingresos = Transaccion::where('tipo', 'ingreso')
    ->where('id_tenant', $tenant->id) // Filtrar por tenant
    ->sum('monto');
        $gastos = Transaccion::where('tipo', 'gasto') ->where('id_tenant', $tenant->id) // Filtrar por tenant
    ->sum('monto');
        $balance = $ingresos - $gastos;
        $tenants = session('tenant');
        // Obtener datos de ingresos y gastos por mes
        $ingresosPorMes = Transaccion::where('tipo', 'ingreso')-> where('id_tenant',$tenant->id)
            ->selectRaw('MONTH(created_at) as mes, SUM(monto) as total')
            ->groupBy('mes')
            ->pluck('total', 'mes');
    
        $gastosPorMes = Transaccion::where('tipo', 'gasto')-> where('id_tenant',$tenant->id)
            ->selectRaw('MONTH(created_at) as mes, SUM(monto) as total')
            ->groupBy('mes')
            ->pluck('total', 'mes');
        // Obtener lista detallada de transacciones
    
        $transacciones = TransaccionView::where('id_tenant',$tenants->id)
            ->orderBy('created_at', 'desc')
            ->get();


        return view('finanzas.index', compact('ingresos', 'gastos', 'balance', 'ingresosPorMes', 'gastosPorMes', 'transacciones'));
    }

    public function store(Request $request)
{
    $tenants = session('tenant');
    $transaccion = new Transaccion();
    $transaccion->descripcion = $request->descripcion;
    $transaccion->monto = $request->monto;
    $transaccion->tipo = 'gasto';
    $transaccion->fecha = now();
    $transaccion->id_tenant = $tenants->id;
    $transaccion->save();

    return response()->json([
        'id' => $transaccion->id,
        'fecha' => $transaccion->created_at->format('d/m/Y'),
        'descripcion' => $transaccion->descripcion,
        'usuario' => $transaccion->nombre,
        'monto' => $transaccion->monto
    ]);
}

}    