<?php

use App\Http\Controllers\WeeklyMenuController;
use App\Http\Controllers\BonoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\CitaDatosController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\FinanzasController;
use App\Http\Controllers\HorarioDisponibleController;
use App\Http\Controllers\OpenFoodFactsController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\MealPlannerController;
use App\Http\Controllers\FoodController;

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/dashboard',[App\Http\Controllers\HomeController::class, 'admin'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard1',[App\Http\Controllers\HomeController::class, 'paciente'])->middleware(['auth', 'verified'])->name('pruebas');



Route::middleware('auth')->group(function () {
  //TENANT
  Route::get('/select-tenant', [TenantController::class, 'show'])->name('select-tenant')->Middleware();
  Route::post('/set-tenant', [TenantController::class, 'set'])->name('set-tenant')->Middleware();
  Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index')->Middleware();
  Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
  Route::delete('tenants/{tenant}', [TenantController::class, 'destroy'])->name('tenants.destroy');

  //Usuarios
Route::get('/user/table', [App\Http\Controllers\HomeController::class, 'user'])->name('user.table')->Middleware();
Route::get('/user/edit/{id}',[App\Http\Controllers\HomeController::class,'edit'])->name('users.edit')->Middleware();
Route::get('/user/delete/{id}',[App\Http\Controllers\HomeController::class,'delete']);
Route::put('/user/update/{id}',[App\Http\Controllers\HomeController::class,'update'])->name('users.update')->Middleware();
Route::get('/crear', [App\Http\Controllers\HomeController::class, 'crear'])->name('user.crear')->Middleware();
Route::post('/store', [App\Http\Controllers\HomeController::class, 'store'])->name('user.store')->Middleware();
Route::get('/user/settings', [App\Http\Controllers\HomeController::class, 'setting'])->name('user.settings')->Middleware();
Route::get('/user/perfil/{id}', [App\Http\Controllers\HomeController::class, 'profile'])->name('users.profile');
Route::post('/users/{id}/toggle-status', [App\Http\Controllers\HomeController::class, 'toggleStatus'])->name('users.toggleStatus');
Route::get('/logout', [HomeController::class, 'logout'])->name('logout');

//usuarios y archivos para subir
Route::get('/users', [App\Http\Controllers\UserFileController::class, 'index'])->name('users.index');
Route::post('/users/{user}/upload', [App\Http\Controllers\UserFileController::class, 'upload'])->name('users.upload');
Route::delete('/users/deleteFile/{file}', [App\Http\Controllers\UserFileController::class, 'deleteFile'])->name('users.deleteFile');
Route::post('/users/{id}/toggle-status', [App\Http\Controllers\HomeController::class, 'toggleStatus'])->name('users.toggleStatus');

//consultas
Route::get('/usuarios-con-consultas', [App\Http\Controllers\ConsultaController::class, 'usuariosConConsultas'])->name('usuarios.consultas');
Route::get('/consultas/{user}', [App\Http\Controllers\ConsultaController::class, 'consultasPorUsuario'])->name('consultas.usuario');
Route::get('/consultas1', [App\Http\Controllers\ConsultaController::class, 'citasPorUsuario1'])->name('consultas.usuario1');

 // Mostrar todas las consultas
 Route::get('/consultas', [App\Http\Controllers\ConsultaController::class, 'index'])->name('consultas.index');
 // Mostrar el formulario para crear una nueva consulta
 Route::get('/consulta/create', [App\Http\Controllers\ConsultaController::class, 'create'])->name('consultas.create');
 // Guardar una nueva consulta
 Route::post('/consultas', [App\Http\Controllers\ConsultaController::class, 'store'])->name('consultas.store');

Route::get('/usuarios-con-consultas', [App\Http\Controllers\ConsultaController::class, 'usuariosConConsultas'])->name('usuarios.consultas');
Route::get('/consultas/{user}', [App\Http\Controllers\ConsultaController::class, 'consultasPorUsuario'])->name('consultas.usuario');
Route::get('/consultas1', [App\Http\Controllers\ConsultaController::class, 'citasPorUsuario1'])->name('consultas.usuario1');

Route::patch('/consultas/validar', [App\Http\Controllers\ConsultaController::class,  'validar'])->name('consultas.validar');


Route::resource('horarios', HorarioDisponibleController::class)->except(['edit', 'update', 'show']);



Route::get('/calendario', [CitaController::class, 'index'])->name('calendario.index');
Route::get('/citas/obtener', [CitaController::class, 'obtenerHorarios']);
Route::post('/citas/crear', [CitaController::class, 'crearCita'])->name('citas.crear');

Route::get('/horarios/disponibles/{fecha}', [CitaController::class, 'obtenerHorariosPorFecha']);
// Ruta para obtener las citas reservadas del usuario
Route::get('/citas/reservadas/{user_id}',  [CitaController::class, 'citasReservadas']);

Route::post('/citas/cancelar/{id}', [CitaController::class, 'cancelarCita'])->name('citas.cancelar');
Route::get('/citas-semanales', [CitaController::class, 'citasSemanales'])->name('citas.semanales');
Route::get('/citas/listar', [CitaController::class, 'listarCitas'])->name('citas.listar');

Route::post('/citas/{idCita}/finalizar', [CitaController::class, 'cambiarEstadoCita'])->name('citas.finalizar');

Route::get('citas/datos/create/{id}', [CitaDatosController::class, 'create'])->name('citas.datos.create');
Route::get('citas/datos/form/{id}', [CitaDatosController::class, 'form'])->name('citas.datos.form');
Route::post('citas/datos/form', [CitaDatosController::class, 'storeform'])->name('citas.datos.formstore');

Route::post('citas/datos/{id}', [CitaDatosController::class, 'store'])->name('citas.datos.store');
Route::get('/citas/{id}/datos', [CitaDatosController::class, 'show'])->name('citas.datos.show');
Route::get('/citas/datos/{id}/pdf', [CitaDatosController::class, 'exportPDF'])->name('cita_datos.pdf');

Route::get('/citas/datos/edit/{id}', [CitaDatosController::class, 'edit'])->name('citas.datos.edit');
Route::put('/citas/datos/update/{id}', [CitaDatosController::class, 'update'])->name('citas.datos.update');
Route::get('/citas/datos/{user}', [App\Http\Controllers\ConsultaController::class, 'citasPorUsuario'])->name('citas.datos.usuario');
Route::get('/citas/get', [HomeController::class, 'getCitas'])->name('citas.get');


Route::get('/citas/{id}/descargar-ics', [App\Http\Controllers\CitaICSController::class, 'descargarICS'])->name('citas.descargarICS');


//productos

Route::get('/escanear', function () {
    return view('capturar');
})->name('escanear.codigo');

Route::post('/buscar-producto1', [OpenFoodFactsController::class, 'buscar'])->name('buscar.producto1');
Route::post('/buscar-producto', [OpenFoodFactsController::class, 'buscarProducto'])->name('buscar.producto');
Route::get('/productos', [OpenFoodFactsController::class, 'buscarPorSupermercadoYCategoria'])->name('productos.buscar');
Route::get('/producto', function () {
    return view('buscar_producto');
});

// Ruta para mostrar la lista de bonos
Route::get('/bonos', [BonoController::class, 'index'])->name('bonos.index');
    
// Ruta para mostrar el formulario de creación de bono
Route::get('/bonos/create', [BonoController::class, 'create'])->name('bonos.create');

// Ruta para almacenar el nuevo bono
Route::post('/bonos', [BonoController::class, 'store'])->name('bonos.store');

// Ruta para mostrar el formulario de edición de un bono
Route::get('/bonos/{bono}/edit', [BonoController::class, 'edit'])->name('bonos.edit');

// Ruta para actualizar un bono
Route::put('/bonos/{bono}', [BonoController::class, 'update'])->name('bonos.update');

// Ruta para eliminar un bono
Route::delete('/bonos/{bono}', [BonoController::class, 'destroy'])->name('bonos.destroy');
Route::post('/users/assign-bono', [BonoController::class, 'assignBono'])->name('users.assignBono');
Route::get('/users/assign-bonos/{id}', [BonoController::class, 'assignBonos'])->name('users.assignBono1');
Route::get('bonos/todos', [BonoController::class, 'showBonosDeTodosLosUsuarios'])->name('bonos.show');
Route::get('/citas/detalle/{id}', [CitaController::class, 'detalle'])->name('citas.detalle');

Route::get('/get-bonos/{user_id}', [CitaController::class, 'getBonos'])->name('get.bonos');
Route::get('/citas/crearnew', [CitaController::class, 'crearCitanew'])->name('cita.createnew');
Route::post('/citas/storenew', [CitaController::class, 'crearCitastore'])->name('citas.storenew');
Route::post('/horarios/storen', [HorarioDisponibleController::class, 'storen'])->name('horarios.storeN');

Route::get('/finanzas', [FinanzasController::class, 'index'])->name('finanzas.index');
Route::post('/transacciones', [FinanzasController::class, 'store'])->name('transacciones.store');



Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{id}', [RecipeController::class, 'show'])->name('recipes.show');



Route::get('/menu-auto', [MealPlannerController::class, 'index'])->name('menu-auto.index'); // Vista inicial
Route::post('/menu-auto/generate', [MealPlannerController::class, 'generate'])->name('menu-auto.generate'); // Generar plan
Route::get('/menu-auto/recipe/{id}', [MealPlannerController::class, 'getRecipeDetails'])->name('menu-auto.recipe'); // Detalles de la receta

Route::get('/usuarios/{usuario}/menus', [App\Http\Controllers\ConsultaController::class, 'menusPorUsuario'])->name('citas.menu.usuario');



//menu semanal 
    Route::get('/menu-semanal', [WeeklyMenuController::class,'index'])->name('menu.semanal');
Route::get('/menu/semanal', [WeeklyMenuController::class, 'index'])->name('menu.semanal');
Route::post('/menu/{menu}/items', [WeeklyMenuController::class, 'storeAllItems'])->name('menu.items.saveAll');
Route::get('/menus/{menu}/edit', [WeeklyMenuController::class, 'edit'])->name('menu.edit');
Route::post('/menus/{menu}/items/update', [WeeklyMenuController::class, 'updateBulk'])->name('menu.item.update.bulk');
Route::post('/menu/{menu}/items/store', [WeeklyMenuController::class, 'storeBulk'])->name('menu.item.store.bulk');
Route::get('/menu/{id}/pdf', [WeeklyMenuController::class, 'generarPDF'])->name('menu.pdf');


//seguimientos
Route::get('/citas/resumen/{user_id}', [CitaController::class, 'resumen'])->name('citas.resumen');

Route::get('/foods', [FoodController::class, 'index'])->name('foods.index');
Route::post('/foods', [FoodController::class, 'store'])->name('foods.store');
Route::put('/foods/{food}', [FoodController::class, 'update'])->name('foods.update');

});



require __DIR__.'/auth.php';
