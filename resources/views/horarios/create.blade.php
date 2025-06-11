@extends('layouts.app')

@section('title', 'Crear Horario Disponible')

@section('content_header')
    <h1>Crear Nuevo Horario Disponible</h1>
@stop

@section('content')
<div class="content-wrapper">
   
   
    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-center">Crear Nuevo Horario</h1>
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
            <form action="{{ route('horarios.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="hora_inicio">Hora de Inicio</label>
                    <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="hora_fin">Hora de Fin</label>
                    <input type="time" name="hora_fin" id="hora_fin" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Horario</button>
            </form>
        </div>
    </div>
@endsection
