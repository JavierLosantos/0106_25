<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Cita;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
        {
                    
        $request->authenticate();
        $request->session()->regenerate();
        
        $user = Auth::user();
        $tenants = $user->tenants;
        
        // Verifica si el usuario tiene exactamente un tenant
        if ($tenants->count() === 1) {
            $tenant = $tenants->first();
            session(['tenant' => $tenant]);
        
            // Redirección según el rol y estado del usuario
            if ($user->role === 'Admin' && $user->status === 'activo') {
                return redirect()->route('dashboard');
            }
        
            if ($user->role === 'Paciente' && $user->status === 'activo') {
                return redirect()->route('pruebas');
            }
        } else {
            // Si el usuario es Admin y tiene más de un tenant
            if ($user->role === 'Admin') {
                session(['tenant' => $tenants->first()]);
                return redirect()->intended(route('dashboard'));
            }
}

// Redirección por defecto para otros casos
return redirect()->route('home');

             
        }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
