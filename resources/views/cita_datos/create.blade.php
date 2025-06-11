@extends('layouts.app')

@section('title', 'Agregar Datos de Cita')

@section('content_header')
    <h1 class="mb-4 text-success text-center">Agregar Datos de Cita</h1>
@stop

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
               
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <a href="{{ route('horarios.index') }}" class="btn btn-secondary mb-3">Volver a Horarios</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Formulario de Horario</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('citas.datos.store', $cita->id) }}" method="POST">
                @csrf
                @if($bonos->isNotEmpty())
                    @foreach($bonos as $bono)
                        <p>
                            <strong>ACTIVO:</strong> <span class="badge badge-info">{{ $bono->bono->nombre }}</span>
                            <input type="hidden" name="bonoid" value="{{ $bono->bono->id }}">

                            <strong>RESTAN:</strong> <span class="badge badge-warning">{{ $bono->sesiones_restantes }}</span>
                            <strong>PAGADO: </strong> 
                            <select class="form-control d-inline-block w-auto ml-2" name="pagado" disabled>
                                <option value="si" {{ $bono->sesiones_restantes > 0 ? 'selected' : '' }}>Si</option>
                                <option value="no" {{ $bono->sesiones_restantes == 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </p>
                    @endforeach
                @else
                    <!-- Si NO hay bonos activos, permitir agregar uno nuevo o ingresar pago manual -->
                    <div class="alert alert-warning">
                        <p><strong>No hay bonos activos.</strong></p>
                        <p>Seleccione un bono o indique el pago manualmente.</p>

                        <!-- Seleccionar un nuevo bono -->
                        <label for="nuevo_bono" class="fw-bold">Asignar Bono:</label>
                        <select name="nuevo_bono" id="nuevo_bono" class="form-control">
                            <option value="">-- Seleccionar Bono --</option>
                            @foreach($todosLosBonos as $bono)
                                <option value="{{ $bono->id }}" data-precio="{{ $bono->precio }}">{{ $bono->nombre }} - €{{ $bono->precio }}</option>
                            @endforeach
                        </select>

                        <!-- Ingresar pago manual -->
                        <div class="mt-3">
                            <label for="pagado_manual" class="fw-bold">Pagar sin bono:</label>
                            <select class="form-control w-auto d-inline-block" name="pagado_manual" id="pagado_manual">
                                <option value="no" selected>No</option>
                                <option value="si">Sí</option>
                                <option value="Síempre">Síempre</option>
                            </select>
                            <input type="number" step="0.01" name="precio_manual" id="precio_manual" class="form-control w-auto d-inline-block ml-2" placeholder="Precio (€)" disabled>
                        </div>
                    </div>
                @endif

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
                    <!-- Nuevo Tab Cuestionario -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cuestionario-tab" data-bs-toggle="tab" data-bs-target="#cuestionario" type="button" role="tab" aria-controls="cuestionario" aria-selected="false">
                            Cuestionario
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
                                <input type="number" step="0.01" class="form-control" id="peso" name="peso" required>
                            </div>
                            <div class="col-md-6">
                                <label for="altura" class="form-label fw-bold">Altura (m):</label>
                                <input type="number" step="0.01" class="form-control" id="altura" name="altura" required>
                            </div>
                            <div class="col-md-6">
                                <label for="imc" class="form-label fw-bold">IMC/BMI:</label>
                                <input type="number" step="0.01" class="form-control" id="imc" name="imc" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cansado" class="form-label fw-bold">¿Está cansado?</label>
                                <select class="form-control" id="cansado" name="cansado" required>
                                    <option value="no">No</option>
                                    <option value="sí">Sí</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Porcentajes -->
                    <div class="tab-pane fade" id="porcentajes" role="tabpanel" aria-labelledby="porcentajes-tab">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="agua" class="form-label fw-bold">% de Agua:</label>
                                <input type="number" step="0.01" class="form-control" id="agua" name="agua" required>
                            </div>
                            <div class="col-md-6">
                                <label for="grasa" class="form-label fw-bold">% Grasa Total:</label>
                                <input type="number" step="0.01" class="form-control" id="grasa" name="grasa" required>
                            </div>
                            <div class="col-md-6">
                                <label for="brazo_izq" class="form-label fw-bold">% Brazo Izquierdo:</label>
                                <input type="number" step="0.01" class="form-control" id="brazo_izq" name="brazo_izq" required>
                            </div>
                            <div class="col-md-6">
                                <label for="brazo_der" class="form-label fw-bold">% Brazo Derecho:</label>
                                <input type="number" step="0.01" class="form-control" id="brazo_der" name="brazo_der" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tronco" class="form-label fw-bold">% Tronco:</label>
                                <input type="number" step="0.01" class="form-control" id="tronco" name="tronco" required>
                            </div>
                            <div class="col-md-6">
                                <label for="pierna_izq" class="form-label fw-bold">% Pierna Izquierda:</label>
                                <input type="number" step="0.01" class="form-control" id="pierna_izq" name="pierna_izq" required>
                            </div>
                            <div class="col-md-6">
                                <label for="pierna_der" class="form-label fw-bold">% Pierna Derecha:</label>
                                <input type="number" step="0.01" class="form-control" id="pierna_der" name="pierna_der" required>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Masa Muscular y Ósea -->
                    <div class="tab-pane fade" id="masa" role="tabpanel" aria-labelledby="masa-tab">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="masa_muscular" class="form-label fw-bold">Masa Muscular Total (kg):</label>
                                <input type="number" step="0.01" class="form-control" id="masa_muscular" name="masa_muscular" required>
                            </div>
                            <div class="col-md-6">
                                <label for="masa_muscular_brazo_izq" class="form-label fw-bold">Masa Muscular Brazo Izq (kg):</label>
                                <input type="number" step="0.01" class="form-control" id="masa_muscular_brazo_izq" name="masa_muscular_brazo_izq" required>
                            </div>
                            <div class="col-md-6">
                                <label for="masa_muscular_brazo_der" class="form-label fw-bold">Masa Muscular Brazo Der (kg):</label>
                                <input type="number" step="0.01" class="form-control" id="masa_muscular_brazo_der" name="masa_muscular_brazo_der" required>
                            </div>
                            <div class="col-md-6">
                                <label for="masa_muscular_tronco" class="form-label fw-bold">Masa Muscular Tronco (kg):</label>
                                <input type="number" step="0.01" class="form-control" id="masa_muscular_tronco" name="masa_muscular_tronco" required>
                            </div>
                            <div class="col-md-6">
                                <label for="masa_muscular_pierna_izq" class="form-label fw-bold">Masa Muscular Pierna Izq (kg):</label>
                                <input type="number" step="0.01" class="form-control" id="masa_muscular_pierna_izq" name="masa_muscular_pierna_izq" required>
                            </div>
                            <div class="col-md-6">
                                <label for="masa_muscular_pierna_der" class="form-label fw-bold">Masa Muscular Pierna Der (kg):</label>
                                <input type="number" step="0.01" class="form-control" id="masa_muscular_pierna_der" name="masa_muscular_pierna_der" required>
                            </div>
                            <div class="col-md-6">
                                <label for="masa_osea" class="form-label fw-bold">Masa Ósea (kg):</label>
                                <input type="number" step="0.01" class="form-control" id="masa_osea" name="masa_osea" required>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Otros -->
                  <!-- Tab Otros Datos -->
                <div class="tab-pane fade" id="otros" role="tabpanel" aria-labelledby="otros-tab">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="edad_metabolica" class="form-label fw-bold">Edad Metabólica:</label>
                            <input type="number" step="0.01" class="form-control" id="edad_metabolica" name="edad_metabolica" required>
                        </div>
                        <div class="col-md-6">
                            <label for="grasa_visceral" class="form-label fw-bold">Nivel de Grasa Visceral:</label>
                            <input type="number" step="0.01" class="form-control" id="grasa_visceral" name="grasa_visceral" required>
                        </div>
                    </div>
                </div>

                    <!-- Tab Cuestionario -->
                    <div class="tab-pane fade" id="cuestionario" role="tabpanel" aria-labelledby="cuestionario-tab">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label for="cuestionario_texto" class="form-label fw-bold">Pregunta del cuestionario:</label>
                                <textarea class="form-control" id="cuestionario_texto" name="cuestionario_texto" rows="5" required></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-success px-5">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
    <!-- Incluye Bootstrap 5 CSS si aún no está cargado -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let triggerTabList = [].slice.call(document.querySelectorAll('#datosTab button'));
            triggerTabList.forEach(function (tab) {
                tab.addEventListener('click', function (event) {
                    event.preventDefault();
                    let tabInstance = new bootstrap.Tab(this);
                    tabInstance.show();
                });
            });
        });

        document.getElementById('pagado_manual').addEventListener('change', function() {
            let precioInput = document.getElementById('precio_manual');
            precioInput.disabled = this.value === 'no';
        });

        document.getElementById('nuevo_bono').addEventListener('change', function() {
            let precioInput = document.getElementById('precio_manual');
            let precioBono = this.options[this.selectedIndex].getAttribute('data-precio');
            precioInput.value = precioBono || '';
        });
    </script>
@stop

