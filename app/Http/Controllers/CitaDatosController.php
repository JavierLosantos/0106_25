<?php
namespace App\Http\Controllers;

use App\Models\Bono;
use App\Models\Cita;
use App\Models\CitaDato;
use App\Models\CuestionarioHabitosGenerales;
use App\Models\Cuestrionariosegunda;
use App\Models\Transaccion;
use App\Models\User;
use App\Models\UsuarioBono;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;

class CitaDatosController extends Controller
{
    public function create($id)
    {
        $cita = Cita::findOrFail($id);
        
        $bonos = UsuarioBono::where('user_id', $cita->user_id)
            ->where('sesiones_restantes', '>', 0) // Filtra solo los bonos con sesiones disponibles
            ->with('bono') // Carga la relación con la tabla de bonos
            ->get();
            $todosLosBonos = Bono::all(); 
           
            
        return view('cita_datos.create', compact('cita','bonos','todosLosBonos'));
    }

   public function store(Request $request, $citaId)
    {
        $cod_cita = $citaId;
        $cita = Cita::findOrFail($cod_cita);
        $user_id= $cita->user_id;
        // Validamos los datos recibidos.
        $validatedData = $request->validate([
            'peso'                         => 'required|numeric',
            'altura'                       => 'required|numeric',
            'cansado'                      => 'required|string',
            'agua'                         => 'required|numeric',
            'grasa'                        => 'required|numeric',
            'imc'                          => 'required|numeric',
            'brazo_izq'                    => 'required|numeric',
            'brazo_der'                    => 'required|numeric',
            'tronco'                       => 'required|numeric',
            'pierna_izq'                   => 'required|numeric',
            'pierna_der'                   => 'required|numeric',
            'masa_muscular'                => 'required|numeric',
            'masa_muscular_brazo_izq'      => 'required|numeric',
            'masa_muscular_brazo_der'      => 'required|numeric',
            'masa_muscular_tronco'         => 'required|numeric',
            'masa_muscular_pierna_izq'     => 'required|numeric',
            'masa_muscular_pierna_der'     => 'required|numeric',
            'masa_osea'                    => 'required|numeric',
            'edad_metabolica'              => 'required|integer',
            'grasa_visceral'               => 'required|numeric',
        ]);

         // Obtener los valores del request
    $bonoid = $request->bonoid;  // Bono seleccionado si existe
    $nuevoBonoId = $request->nuevo_bono;  // Nuevo bono seleccionado si se elige
    $pagado = $request->pagado;  // Indica si la cita está pagada
    $pagadoManual = $request->pagado_manual;  // Si el pago es manual
    $precioManual = $request->precio_manual;  // Precio del pago manual
    $precio = 0;
    
    
    
     // Si se ha seleccionado un bono nuevo
     if ($nuevoBonoId) {
        // Buscar el bono seleccionado
        $nuevoBono = Bono::find($nuevoBonoId);
        $precio = $nuevoBono->precio;
        $sesiones = $nuevoBono->sesiones;
        $nombre = $nuevoBono->nombre;

        if($pagadoManual==="no"){
            $pagado ="si";
        }else{
            $pagado = $pagadoManual;
            $precio = $precioManual;
        }
        // Crear el nuevo bono para el usuario
        $usuarioBono = new UsuarioBono();
        $usuarioBono->bono_id = $nuevoBono->id;
        $usuarioBono->user_id = $user_id; // Usar el ID del usuario logueado
        $usuarioBono->sesiones_restantes = $sesiones-1; // O el número de sesiones que corresponda
        $usuarioBono->pagado = $pagado;
        $usuarioBono->save();
        $tenants = session('tenant');
       
        Transaccion::create([
            'tipo' => 'ingreso',
            'monto' => $precio,
            'descripcion' => "Pago de bono: " . $nombre, // Suponiendo que el bono tiene un campo 'nombre'
            'fecha' => now(),
            'id_cita' => $cod_cita,
            'id_tenant' => $tenants->id,
        ]);
    }
    if($pagadoManual==="si"){
        $tenants = session('tenant');
        Transaccion::create([
            'tipo' => 'ingreso',
            'monto' => $precioManual,
            'descripcion' => "Pago de consulta", // Suponiendo que el bono tiene un campo 'nombre'
            'fecha' => now(),
            'id_cita' => $cod_cita,
            'id_tenant' => $tenants->id,
        ]);

    }
    if($bonoid){

        $bonos = UsuarioBono::where('bono_id',$bonoid)->get();
        foreach ($bonos as $bono) {
            if ($bono->sesiones_restantes > 0) {
                $bono->decrement('sesiones_restantes'); // Resta 1 en la base de datos
            }
            $pagado="Si";
            
        }
        
       
        // Buscamos la cita, en caso de no existir retornamos error.
        $cita = Cita::findOrFail($citaId);
        $cita->Bono = $bonoid;
        $cita->pagado = $pagado;
        $cita->save(); // Guardar cambios
    }
        // Asignamos el cita_id y creamos el registro.
        $validatedData['cita_id'] =$cod_cita;
        $citaDato = CitaDato::create($validatedData);
        
        $cuestionario = new Cuestrionariosegunda();
        $cuestionario->descripcion = $request->cuestionario_texto;
        $cuestionario->cita_id = $cod_cita;
        $cuestionario->user_id = $user_id;
        $cuestionario->save();
        //usuarios
        $user = User::find($user_id);
        $user->consultas = $user->consultas + 1;  // O simplemente $user->consulta += 1;
        $user->save();
        // Obtener el rango de fechas de la semana actual
            $startOfWeek = Carbon::now()->startOfWeek(); // Lunes
            $endOfWeek = Carbon::now()->endOfWeek(); // Domingo
    
            $citas = DB::select("SELECT * FROM VW_CITAS_USER");

    return view('calendario.semanales', compact('citas'));


    }

    public function show($id)
        {
            $citaDato = CitaDato::where('cita_id', $id)->firstOrFail();
            return view('cita_datos.show', compact('citaDato'));
        }

public function exportPDF($id)
{
    // Encuentra el registro de CitaDato con el ID proporcionado
    $citaDato = CitaDato::findOrFail($id);

    // Crear una instancia de Dompdf
    $dompdf = new Dompdf();

    // Configurar las opciones (si es necesario)
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);  // Activar el análisis de HTML5
    $dompdf->setOptions($options);

    // Cargar la vista Blade en HTML con los datos
    $html = view('cita_datos.pdf', compact('citaDato'))->render();  // Cargar vista en HTML

    // Cargar el HTML en Dompdf
    $dompdf->loadHtml($html);

    // Configurar el tamaño de la página y la orientación (opcional)//horizontal seria(landscape)
    $dompdf->setPaper('A4', 'portrait');

    // Renderizar el HTML como PDF
    $dompdf->render();

    // Descargar el PDF
    return $dompdf->stream('Cita_Datos_'.$citaDato->id.'.pdf');
}

public function edit($id)
    {
        // Obtener la cita y los datos de la cita asociados
      
        $citaDatos = CitaDato::where('id', $id)->first();  // Suponiendo que CitaDatos tiene una relación con Cita

        return view('cita_datos.edit', compact('citaDatos'));
    }
public function update(Request $request, $id)
{
    // Validar los datos recibidos desde el formulario
    $validatedData = $request->validate([
        'peso' => 'required|numeric',
        'altura' => 'required|numeric',
        'imc' => 'required|numeric',
        'cansado' => 'required|string',
        'agua' => 'required|numeric',
        'grasa' => 'required|numeric',
        'brazo_izq' => 'nullable|numeric',
        'brazo_der' => 'nullable|numeric',
        'tronco' => 'nullable|numeric',
        'pierna_izq' => 'nullable|numeric',
        'pierna_der' => 'nullable|numeric',
        'masa_muscular' => 'nullable|numeric',
        'masa_muscular_brazo_izq' => 'nullable|numeric',
        'masa_muscular_brazo_der' => 'nullable|numeric',
        'masa_muscular_tronco' => 'nullable|numeric',
        'masa_muscular_pierna_izq' => 'nullable|numeric',
        'masa_muscular_pierna_der' => 'nullable|numeric',
        'masa_osea' => 'nullable|numeric',
        'edad_metabolica' => 'nullable|numeric',
        'grasa_visceral' => 'nullable|numeric',
    ]);

    // Buscar el registro de la cita en la base de datos
    $citaDatos = CitaDato::findOrFail($id);

    // Actualizar los campos con los datos validados
    $citaDatos->update([
        'peso' => $validatedData['peso'],
        'altura' => $validatedData['altura'],
        'imc' => $validatedData['imc'],
        'cansado' => $validatedData['cansado'],
        'agua' => $validatedData['agua'],
        'grasa' => $validatedData['grasa'],
        'brazo_izq' => $validatedData['brazo_izq'] ?? null,
        'brazo_der' => $validatedData['brazo_der'] ?? null,
        'tronco' => $validatedData['tronco'] ?? null,
        'pierna_izq' => $validatedData['pierna_izq'] ?? null,
        'pierna_der' => $validatedData['pierna_der'] ?? null,
        'masa_muscular' => $validatedData['masa_muscular'] ?? null,
        'masa_muscular_brazo_izq' => $validatedData['masa_muscular_brazo_izq'] ?? null,
        'masa_muscular_brazo_der' => $validatedData['masa_muscular_brazo_der'] ?? null,
        'masa_muscular_tronco' => $validatedData['masa_muscular_tronco'] ?? null,
        'masa_muscular_pierna_izq' => $validatedData['masa_muscular_pierna_izq'] ?? null,
        'masa_muscular_pierna_der' => $validatedData['masa_muscular_pierna_der'] ?? null,
        'masa_osea' => $validatedData['masa_osea'] ?? null,
        'edad_metabolica' => $validatedData['edad_metabolica'] ?? null,
        'grasa_visceral' => $validatedData['grasa_visceral'] ?? null,
    ]);

        // Obtener el rango de fechas de la semana actual
    $startOfWeek = Carbon::now()->startOfWeek(); // Lunes
    $endOfWeek = Carbon::now()->endOfWeek(); // Domingo
    
$citas = DB::select("SELECT * FROM VW_CITAS_USER");
    return view('calendario.semanales', compact('citas'))->with('success', 'Datos de cita actualizados correctamente.');
}

public function form($id)
{
    $cita = Cita::findOrFail($id);
    return view('cita_datos.form',compact('cita'));
}

public function storeform(Request $request)
    {
  
       

        $cuestionario = new CuestionarioHabitosGenerales();
        $cuestionario->comidas_por_dia = $request->comidas_por_dia;
        $cuestionario->desayuno = $request->desayuno;
        $cuestionario->media_manana = $request->media_manana;
        $cuestionario->comida = $request->comida;
        $cuestionario->merienda = $request->merienda;
        $cuestionario->cena = $request->cena;
        $cuestionario->comida_preferida = $request->comida_preferida;
        $cuestionario->tiene_habito_especial = $request->tiene_habito_especial;
        $cuestionario->descripcion_habito_especial = $request->descripcion_habito_especial;
        $cuestionario->primera_ingesta_dia = $request->primera_ingesta_dia;
        $cuestionario->personas_en_casa = $request->personas_en_casa;
        $cuestionario->quien_cocina_compra = $request->quien_cocina_compra;
        $cuestionario->supermercado = $request->supermercado;
        $cuestionario->come_fuera_lunes_a_viernes = $request->come_fuera_lunes_a_viernes;
        $cuestionario->come_fuera_fin_de_semana = $request->come_fuera_fin_de_semana;
        $cuestionario->comidas_fuera_fin_de_semana = $request->comidas_fuera_fin_de_semana;
        $cuestionario->bebida_semana = $request->bebida_semana;
        $cuestionario->bebida_fin_de_semana = $request->bebida_fin_de_semana;
        $cuestionario->uso_sal = $request->uso_sal;
        $cuestionario->uso_azucar = $request->uso_azucar;
        $cuestionario->uso_edulcorante = $request->uso_edulcorante;
        $cuestionario->tipo_grasa_cocinar = $request->tipo_grasa_cocinar;
        $cuestionario->aliño_ensalada = $request->aliño_ensalada;
        $cuestionario->picoteo_entre_horas = $request->picoteo_entre_horas;
        $cuestionario->cambios_alimentacion_ult_3_meses = $request->cambios_alimentacion_ult_3_meses;
        $cuestionario->alergias = $request->alergias;
        $cuestionario->intolerancias = $request->intolerancias;
        $cuestionario->medicacion = $request->medicacion;
        $cuestionario->hace_ejercicio = $request->hace_ejercicio;
        $cuestionario->dias_ejercicio_semana = $request->dias_ejercicio_semana;
        $cuestionario->duracion_ejercicio = $request->duracion_ejercicio;
        $cuestionario->tipo_entrenamiento = $request->tipo_entrenamiento;
        $cuestionario->hora_entrenamiento = $request->hora_entrenamiento;
        $cuestionario->cita_id = $request->cita_id;
        $cuestionario->user_id = $request->user_id;
        $cuestionario->save();
    // Buscar al usuario por su ID
    $user = User::find($request->user_id);
    $user->consultas = $user->consultas + 1;  // O simplemente $user->consulta += 1;
    $user->save();

        return redirect()->route('citas.semanales')->with('success', 'Formulario enviado con éxito');
    }
}
