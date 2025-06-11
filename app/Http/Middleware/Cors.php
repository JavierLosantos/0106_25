<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Request;
class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
      public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Add CORS headers
        $response->header('Access-Control-Allow-Origin', 'http://localhost:21180'); // Or * if you want to allow all
        $response->header('Access-Control-Allow-Methods', 'POST, PUT, GET, OPTIONS, DELETE');
        $response->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Origin, Authorization, Accept, Client-Security-Token');

        return $response;
    }
}
