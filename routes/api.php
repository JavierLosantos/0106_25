<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apicontroller;

Route::options('{any}', function () {
    return response()->json([], 204);
})->where('any', '.*');
Route::post('/login', [Apicontroller::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', [Apicontroller::class, 'user']);

Route::get('/ping', [Apicontroller::class, 'ping']);

Route::post('/cors-test', function () {
    return response()->json([
        'message' => 'CORS funcionando correctamente desde el servidor Laravel.'
    ]);
    
    
    
    Route::middleware('auth:sanctum')->get('/paciente', [Apicontroller::class, 'paciente']);
});