@extends('layouts.app')

@section('title', 'Detalles de la Cita')

@section('content_header')
    <h1 class="text-center text-success">Detalles de la Cita</h1>
@stop

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
               
            </div>
            
        </div>
    </div>
    <div class="card shadow-lg p-4 border-0 rounded-4 bg-light">
        <div class="text-center mt-4">
    <a href="{{ route('cita_datos.pdf', $citaDato->id) }}" class="btn btn-danger px-4 py-2 rounded-pill shadow-sm">
        <i class="fas fa-file-pdf"></i> Exportar a PDF
    </a>
      @if(in_array(auth()->id(), [1, 4]))
    <a href="{{ route('citas.datos.edit', $citaDato->id) }}" class="btn btn-success px-4 py-2 rounded-pill shadow-sm">
        <i class="fas fa-file-edit"></i> Editar
    </a>
@endif
</div>

        <div class="card-body">
            <h4 class="text-center text-primary mb-4">Información General</h4>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-white shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="text-success"><i class="fas fa-weight"></i> Peso:</h5>
                            <p class="fs-5">{{ $citaDato->peso }} kg</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bg-white shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="text-primary"><i class="fas fa-ruler"></i> Altura:</h5>
                            <p class="fs-5">{{ $citaDato->altura }} m</p>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="text-center text-primary mt-4">Composición Corporal</h4>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-white shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="text-warning"><i class="fas fa-calculator"></i> IMC:</h5>
                            <p class="fs-5">{{ $citaDato->imc }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-white shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="text-info"><i class="fas fa-tint"></i> % Agua:</h5>
                            <p class="fs-5">{{ $citaDato->agua }}%</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-white shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="text-danger"><i class="fas fa-percent"></i> % Grasa:</h5>
                            <p class="fs-5">{{ $citaDato->grasa }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="text-center text-primary mt-4">Distribución Corporal</h4>

            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-white shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="text-success"><i class="fas fa-hand-paper"></i> Brazo Izq:</h5>
                            <p class="fs-5">{{ $citaDato->brazo_izq }}%</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-white shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="text-success"><i class="fas fa-hand-paper"></i> Brazo Der:</h5>
                            <p class="fs-5">{{ $citaDato->brazo_der }}%</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-white shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="text-primary"><i class="fas fa-child"></i> Tronco:</h5>
                            <p class="fs-5">{{ $citaDato->tronco }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="text-center text-primary mt-4">Masa Muscular</h4>

            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-white shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="text-success"><i class="fas fa-dumbbell"></i> Total:</h5>
                            <p class="fs-5">{{ $citaDato->masa_muscular }} kg</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-white shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="text-primary"><i class="fas fa-hand-paper"></i> Brazo Izq:</h5>
                            <p class="fs-5">{{ $citaDato->masa_muscular_brazo_izq }} kg</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-white shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="text-primary"><i class="fas fa-hand-paper"></i> Brazo Der:</h5>
                            <p class="fs-5">{{ $citaDato->masa_muscular_brazo_der }} kg</p>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="text-center text-primary mt-4">Otros Datos</h4>

            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-white shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="text-warning"><i class="fas fa-bone"></i> Masa Ósea:</h5>
                            <p class="fs-5">{{ $citaDato->masa_osea }} kg</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bg-white shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="text-danger"><i class="fas fa-heartbeat"></i> Edad Metabólica:</h5>
                            <p class="fs-5">{{ $citaDato->edad_metabolica }} años</p>
                        </div>
                    </div>
                </div>
            </div>

          <div class="text-center mt-4">
    <a href="{{ in_array(auth()->id(), [1, 4]) ? route('citas.semanales') : route('dashboard') }}" 
       class="btn btn-secondary px-4 py-2 rounded-pill shadow-sm">
        <i class="fas fa-arrow-left"></i> Volver a la lista
    </a>
</div>

        </div>
    </div>
@stop

