<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TenantController extends Controller
{
    public function show()
    {
        return view('auth.select-tenant');
    }

    public function set(Request $request)
    {
        $tenant = Tenant::findOrFail($request->tenant_id);
        session(['tenant' => $tenant]);
        // Get the authenticated user's role
        $role = Auth::user()->role;

        // Store role in session
        session(['role' => $role]);

        return redirect()->intended('/dashboard');
    }

    public function index()
    {
        // Obtiene todos los tenants asociados al usuario autenticado
        $tenants = Tenant::get();
        
        return view('tenant.tenant', compact('tenants'));
    }
        
    public function store(Request $request)
    {
    // Concatenar el nombre con "local" para el dominio
    $dominio = $request->name . ".local";

    // Crear el nuevo tenant
    $tenant = Tenant::create([
        'name' => $request->name,
        'domain' => $dominio,
    ]);
    // Obtener el usuario autenticado
    $user = Auth::user();

    // Asignar el usuario al tenant
    $user->tenants()->attach($tenant->id);

    // Obtener todos los tenants asociados al usuario autenticado (si es necesario)
    $tenants = Tenant::all();  // Si quieres todos los tenants, puedes usar `Tenant::all()`

    // Redirigir a la lista de tenants con un mensaje de éxito
    return redirect()->route('tenants.index', compact('tenants'))->with('success', 'Tenant creado exitosamente.');
    }
    
    public function destroy($tenantId)
{
    // Obtener el tenant por su ID
    $tenant = Tenant::findOrFail($tenantId);

    // Eliminar la relación entre el usuario y el tenant
    $user = Auth::user();
    $user->tenants()->detach($tenant->id);

    // Eliminar el tenant
    $tenant->delete();

    // Redirigir con mensaje de éxito
    return redirect()->route('tenants.index')->with('success', 'Tenant eliminado exitosamente.');
}



}
