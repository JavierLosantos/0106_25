@extends('layouts.app')

@section('title', 'Agregar Datos de Hábitos Generales')

@section('content_header')
    <h1 class="mb-4 text-success text-center">Agregar Datos de Hábitos Generales</h1>
@stop

@section('content')
<div class="content-wrapper">
   
    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <a href="{{ route('citas.semanales') }}" class="btn btn-secondary mb-3">Volver a Hábitos Generales</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Formulario de Hábitos Generales</h3>
        </div>
        <div class="card-body">
        <form action="{{ route('citas.datos.formstore') }}" method="POST">
            @csrf
            <input type="hidden" name="cita_id" value="{{ $cita->id }}">
            <input type="hidden" name="user_id" value="{{ $cita->user_id }}">
            <div class="row g-4">
                <div class="col-md-6">
                    <label for="comidas_por_dia" class="form-label fw-bold">Comidas por Día:</label>
                    <input type="number" class="form-control" id="comidas_por_dia" name="comidas_por_dia" required>
                </div>

                <div class="col-md-6">
                    <label for="desayuno" class="form-label fw-bold">Desayuno:</label>
                    <select class="form-control" id="desayuno" name="desayuno" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="media_manana" class="form-label fw-bold">Media Mañana:</label>
                    <select class="form-control" id="media_manana" name="media_manana" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="comida" class="form-label fw-bold">Comida:</label>
                    <select class="form-control" id="comida" name="comida" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="merienda" class="form-label fw-bold">Merienda:</label>
                    <select class="form-control" id="merienda" name="merienda" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="cena" class="form-label fw-bold">Cena:</label>
                    <select class="form-control" id="cena" name="cena" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="comida_preferida" class="form-label fw-bold">Comida Preferida:</label>
                    <input type="text" class="form-control" id="comida_preferida" name="comida_preferida" required>
                </div>

                <div class="col-md-6">
                    <label for="tiene_habito_especial" class="form-label fw-bold">¿Tiene Hábito Especial?</label>
                    <select class="form-control" id="tiene_habito_especial" name="tiene_habito_especial" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label for="descripcion_habito_especial" class="form-label fw-bold">Descripción del Hábito Especial:</label>
                    <textarea class="form-control" id="descripcion_habito_especial" name="descripcion_habito_especial" rows="4"></textarea>
                </div>

                <div class="col-md-6">
                    <label for="primera_ingesta_dia" class="form-label fw-bold">Primera Ingesta del Día:</label>
                    <input type="text" class="form-control" id="primera_ingesta_dia" name="primera_ingesta_dia" required>
                </div>

                <div class="col-md-6">
                    <label for="personas_en_casa" class="form-label fw-bold">Personas en Casa:</label>
                    <input type="number" class="form-control" id="personas_en_casa" name="personas_en_casa" required>
                </div>

                <div class="col-md-6">
                    <label for="quien_cocina_compra" class="form-label fw-bold">¿Quién Cocina o Compra?</label>
                    <input type="text" class="form-control" id="quien_cocina_compra" name="quien_cocina_compra" required>
                </div>

                <div class="col-md-6">
                    <label for="supermercado" class="form-label fw-bold">Supermercado Habitual:</label>
                    <input type="text" class="form-control" id="supermercado" name="supermercado" required>
                </div>

                <div class="col-md-6">
                    <label for="come_fuera_lunes_a_viernes" class="form-label fw-bold">¿Come Fuera de Lunes a Viernes?</label>
                    <select class="form-control" id="come_fuera_lunes_a_viernes" name="come_fuera_lunes_a_viernes" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="come_fuera_fin_de_semana" class="form-label fw-bold">¿Come Fuera Fin de Semana?</label>
                    <select class="form-control" id="come_fuera_fin_de_semana" name="come_fuera_fin_de_semana" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="comidas_fuera_fin_de_semana" class="form-label fw-bold">¿Cuántas Comidas Fuera en el Fin de Semana?</label>
                    <input type="number" class="form-control" id="comidas_fuera_fin_de_semana" name="comidas_fuera_fin_de_semana" required>
                </div>

                <div class="col-md-6">
                    <label for="bebida_semana" class="form-label fw-bold">Bebida de la Semana:</label>
                    <input type="text" class="form-control" id="bebida_semana" name="bebida_semana" required>
                </div>

                <div class="col-md-6">
                    <label for="bebida_fin_de_semana" class="form-label fw-bold">Bebida del Fin de Semana:</label>
                    <input type="text" class="form-control" id="bebida_fin_de_semana" name="bebida_fin_de_semana" required>
                </div>

                <!-- Otros campos adicionales aquí... -->
                <div class="col-md-6">
                    <label for="comidas_por_dia" class="form-label fw-bold">Comidas por Día:</label>
                    <input type="number" class="form-control" id="comidas_por_dia" name="comidas_por_dia" required>
                </div>
        
                <div class="col-md-6">
                    <label for="desayuno" class="form-label fw-bold">Desayuno:</label>
                    <select class="form-control" id="desayuno" name="desayuno" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
        
                <!-- Nuevos campos -->
                <div class="col-md-6">
                    <label for="uso_sal" class="form-label fw-bold">Uso de Sal:</label>
                    <input type="text" class="form-control" id="uso_sal" name="uso_sal" required>
                </div>
        
                <div class="col-md-6">
                    <label for="uso_azucar" class="form-label fw-bold">Uso de Azúcar:</label>
                    <input type="text" class="form-control" id="uso_azucar" name="uso_azucar" required>
                </div>
        
                <div class="col-md-6">
                    <label for="uso_edulcorante" class="form-label fw-bold">Uso de Edulcorante:</label>
                    <input type="text" class="form-control" id="uso_edulcorante" name="uso_edulcorante" required>
                </div>
        
                <div class="col-md-6">
                    <label for="tipo_grasa_cocinar" class="form-label fw-bold">Tipo de Grasa para Cocinar:</label>
                    <input type="text" class="form-control" id="tipo_grasa_cocinar" name="tipo_grasa_cocinar" required>
                </div>
        
                <div class="col-md-6">
                    <label for="aliño_ensalada" class="form-label fw-bold">Aliño para Ensalada:</label>
                    <input type="text" class="form-control" id="aliño_ensalada" name="aliño_ensalada" required>
                </div>
        
                <div class="col-md-6">
                    <label for="picoteo_entre_horas" class="form-label fw-bold">Picoteo entre Horas:</label>
                    <input type="text" class="form-control" id="picoteo_entre_horas" name="picoteo_entre_horas" required>
                </div>
        
                <div class="col-md-6">
                    <label for="cambios_alimentacion_ult_3_meses" class="form-label fw-bold">¿Cambios en la Alimentación en los Últimos 3 Meses?</label>
                    <select class="form-control" id="cambios_alimentacion_ult_3_meses" name="cambios_alimentacion_ult_3_meses" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
        
                <div class="col-md-6">
                    <label for="alergias" class="form-label fw-bold">Alergias:</label>
                    <input type="text" class="form-control" id="alergias" name="alergias" required>
                </div>
        
                <div class="col-md-6">
                    <label for="intolerancias" class="form-label fw-bold">Intolerancias:</label>
                    <input type="text" class="form-control" id="intolerancias" name="intolerancias" required>
                </div>
        
                <div class="col-md-6">
                    <label for="medicacion" class="form-label fw-bold">Medicación:</label>
                    <input type="text" class="form-control" id="medicacion" name="medicacion" required>
                </div>
        
                <div class="col-md-6">
                    <label for="hace_ejercicio" class="form-label fw-bold">Hace Ejercicio:</label>
                    <select class="form-control" id="hace_ejercicio" name="hace_ejercicio" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
        
                <div class="col-md-6">
                    <label for="dias_ejercicio_semana" class="form-label fw-bold">Días de Ejercicio por Semana:</label>
                    <input type="number" class="form-control" id="dias_ejercicio_semana" name="dias_ejercicio_semana" required>
                </div>
        
                <div class="col-md-6">
                    <label for="duracion_ejercicio" class="form-label fw-bold">Duración del Ejercicio:</label>
                    <input type="text" class="form-control" id="duracion_ejercicio" name="duracion_ejercicio" required>
                </div>
        
                <div class="col-md-6">
                    <label for="tipo_entrenamiento" class="form-label fw-bold">Tipo de Entrenamiento:</label>
                    <input type="text" class="form-control" id="tipo_entrenamiento" name="tipo_entrenamiento" required>
                </div>
        
                <div class="col-md-6">
                    <label for="hora_entrenamiento" class="form-label fw-bold">Hora de Entrenamiento:</label>
                    <input type="time" class="form-control" id="hora_entrenamiento" name="hora_entrenamiento" required>
                </div>
        
                <!-- Otros campos adicionales aquí... -->
        
            
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success px-5 py-2 rounded-pill">Guardar Datos</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
@stop
