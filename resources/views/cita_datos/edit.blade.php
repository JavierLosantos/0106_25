@extends('layouts.app')

@section('title', 'Editar Datos de Cita')

@section('content_header')
    <h1 class="mb-4 text-success text-center">Editar Datos de Cita</h1>
@stop

@section('content')
<div class="card shadow-lg p-4 border-0 rounded-4 bg-light">
    <div class="card-body">
        <form action="{{ route('citas.datos.update', $citaDatos->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Usamos PUT para indicar que estamos editando -->

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="datosTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="medidas-tab" data-bs-toggle="tab" data-bs-target="#medidas" type="button" role="tab" aria-controls="medidas" aria-selected="true">
                        Medidas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="porcentajes-tab" data-bs-toggle="tab" data-bs-target="#porcentajes" type="button" role="tab" aria-controls="porcentajes" aria-selected="false">
                        Porcentajes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="masa-tab" data-bs-toggle="tab" data-bs-target="#masa" type="button" role="tab" aria-controls="masa" aria-selected="false">
                        Masa Muscular y Ósea
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="otros-tab" data-bs-toggle="tab" data-bs-target="#otros" type="button" role="tab" aria-controls="otros" aria-selected="false">
                        Otros Datos
                    </button>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content mt-4" id="datosTabContent">
                <!-- Tab Medidas -->
                <div class="tab-pane fade show active" id="medidas" role="tabpanel" aria-labelledby="medidas-tab">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="peso" class="form-label fw-bold">Peso (kg):</label>
                            <input type="number" step="0.01" class="form-control" id="peso" name="peso" value="{{ old('peso', $citaDatos->peso ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="altura" class="form-label fw-bold">Altura (m):</label>
                            <input type="number" step="0.01" class="form-control" id="altura" name="altura" value="{{ old('altura', $citaDatos->altura ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="imc" class="form-label fw-bold">IMC/BMI:</label>
                            <input type="number" step="0.01" class="form-control" id="imc" name="imc" value="{{ old('imc', $citaDatos->imc ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="cansado" class="form-label fw-bold">¿Está cansado?</label>
                            <select class="form-control" id="cansado" name="cansado" required>
                                <option value="no" {{ (old('cansado', $citaDatos->cansado ?? '') == 'no') ? 'selected' : '' }}>No</option>
                                <option value="sí" {{ (old('cansado', $citaDatos->cansado ?? '') == 'sí') ? 'selected' : '' }}>Sí</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tab Porcentajes -->
                <div class="tab-pane fade" id="porcentajes" role="tabpanel" aria-labelledby="porcentajes-tab">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="agua" class="form-label fw-bold">% de Agua:</label>
                            <input type="number" step="0.01" class="form-control" id="agua" name="agua" value="{{ old('agua', $citaDatos->agua ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="grasa" class="form-label fw-bold">% Grasa Total:</label>
                            <input type="number" step="0.01" class="form-control" id="grasa" name="grasa" value="{{ old('grasa', $citaDatos->grasa ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="brazo_izq" class="form-label fw-bold">% Brazo Izquierdo:</label>
                            <input type="number" step="0.01" class="form-control" id="brazo_izq" name="brazo_izq" value="{{ old('brazo_izq', $citaDatos->brazo_izq ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="brazo_der" class="form-label fw-bold">% Brazo Derecho:</label>
                            <input type="number" step="0.01" class="form-control" id="brazo_der" name="brazo_der" value="{{ old('brazo_der', $citaDatos->brazo_der ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="tronco" class="form-label fw-bold">% Tronco:</label>
                            <input type="number" step="0.01" class="form-control" id="tronco" name="tronco" value="{{ old('tronco', $citaDatos->tronco ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="pierna_izq" class="form-label fw-bold">% Pierna Izquierda:</label>
                            <input type="number" step="0.01" class="form-control" id="pierna_izq" name="pierna_izq" value="{{ old('pierna_izq', $citaDatos->pierna_izq ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="pierna_der" class="form-label fw-bold">% Pierna Derecha:</label>
                            <input type="number" step="0.01" class="form-control" id="pierna_der" name="pierna_der" value="{{ old('pierna_der', $citaDatos->pierna_der ?? '') }}" required>
                        </div>
                    </div>
                </div>

                <!-- Tab Masa Muscular y Ósea -->
                <div class="tab-pane fade" id="masa" role="tabpanel" aria-labelledby="masa-tab">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="masa_muscular" class="form-label fw-bold">Masa Muscular Total (kg):</label>
                            <input type="number" step="0.01" class="form-control" id="masa_muscular" name="masa_muscular" value="{{ old('masa_muscular', $citaDatos->masa_muscular ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="masa_muscular_brazo_izq" class="form-label fw-bold">Masa Muscular Brazo Izq (kg):</label>
                            <input type="number" step="0.01" class="form-control" id="masa_muscular_brazo_izq" name="masa_muscular_brazo_izq" value="{{ old('masa_muscular_brazo_izq', $citaDatos->masa_muscular_brazo_izq ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="masa_muscular_brazo_der" class="form-label fw-bold">Masa Muscular Brazo Der (kg):</label>
                            <input type="number" step="0.01" class="form-control" id="masa_muscular_brazo_der" name="masa_muscular_brazo_der" value="{{ old('masa_muscular_brazo_der', $citaDatos->masa_muscular_brazo_der ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="masa_muscular_tronco" class="form-label fw-bold">Masa Muscular Tronco (kg):</label>
                            <input type="number" step="0.01" class="form-control" id="masa_muscular_tronco" name="masa_muscular_tronco" value="{{ old('masa_muscular_tronco', $citaDatos->masa_muscular_tronco ?? '') }}" required>
                        </div>
                         <div class="col-md-6">
                                                         <label for="masa_muscular_pierna_izq" class="form-label fw-bold">Masa Muscular Pierna Izq (kg):</label>
                            <input type="number" step="0.01" class="form-control" id="masa_muscular_pierna_izq" name="masa_muscular_pierna_izq" value="{{ old('masa_muscular_pierna_izq', $citaDatos->masa_muscular_pierna_izq ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="masa_muscular_pierna_der" class="form-label fw-bold">Masa Muscular Pierna Der (kg):</label>
                            <input type="number" step="0.01" class="form-control" id="masa_muscular_pierna_der" name="masa_muscular_pierna_der" value="{{ old('masa_muscular_pierna_der', $citaDatos->masa_muscular_pierna_der ?? '') }}" required>
                        </div>
                    </div>
                </div>

                <!-- Tab Otros Datos -->
                <div class="tab-pane fade" id="otros" role="tabpanel" aria-labelledby="otros-tab">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="masa_osea" class="form-label fw-bold">Masa Ósea (kg):</label>
                            <input type="number" step="0.01" class="form-control" id="masa_osea" name="masa_osea" value="{{ old('masa_osea', $citaDatos->masa_osea ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edad_metabolica" class="form-label fw-bold">Edad Metabólica (años):</label>
                            <input type="number" step="0.01" class="form-control" id="edad_metabolica" name="edad_metabolica" value="{{ old('edad_metabolica', $citaDatos->edad_metabolica ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="grasa_visceral" class="form-label fw-bold">Grasa Visceral:</label>
                            <input type="number" step="0.01" class="form-control" id="grasa_visceral" name="grasa_visceral" value="{{ old('grasa_visceral', $citaDatos->grasa_visceral ?? '') }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón de envío -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success px-5 py-2 rounded-pill">Guardar Cambios</button>
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
