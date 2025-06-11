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
            <h3 class="card-title">Formulario de Cita Nueva</h3>
        </div>
        <div class="card-body">
            <!-- Modal -->
            <div class="modal fade" id="modalHorario" tabindex="-1" aria-labelledby="modalHorarioLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalHorarioLabel">Agregar Nuevo Horario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('horarios.storeN') }}" method="POST">
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
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-success">Guardar Horario</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('citas.storenew') }}" method="POST">
                @csrf

                <!-- Botón para abrir el modal -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalHorario">
                    Cargar Nueva Hora
                </button>

                
            <!-- Horas -->
            <div class="mb-3">
                <label for="user" class="form-label">Horas Disponibles</label>
                <select class="form-control" name="horario_id" id="horario_id" required>
                    <option value="">Selecciona un hora</option>
                    @foreach($horarios as $horario)
                    <option value="{{ $horario->id }}">{{ $horario->hora_inicio }} - {{ $horario->hora_fin }}</option>
                    @endforeach
                </select>
            </div>
                <!-- Usuario -->
                <div class="mb-3">
                    <label for="user" class="form-label">Usuario</label>
                    <select class="form-control" name="user_id" id="user" required>
                        <option value="">Selecciona un usuario</option>
                        @foreach($usuarios as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bono -->
                <div class="mb-3" id="bono-container" style="display: none;">
                    <label for="bono" class="form-label">Bono</label>
                    <select class="form-control" name="bono_id" id="bono">
                        <option value="">Selecciona un bono</option>
                    </select>
                </div>

                <!-- Botón de envío -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success px-5 py-2 rounded-pill">Guardar Datos</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#user').on('change', function () {
                var userId = $(this).val();
                
                if (userId) {
                    $.ajax({
                        url: '/get-bonos/' + userId,
                        type: 'GET',
                        success: function (response) {
                            $('#bono-container').show();
                            $('#bono').empty().append('<option value="">Selecciona un bono</option>');
                            response.bonos.forEach(function(bono) {
                                $('#bono').append('<option value="' + bono.id + '">' + bono.nombre + ' - Restantes: ' + bono.sesiones + '</option>');
                            });
                        }
                    });
                } else {
                    $('#bono-container').hide();
                }
            });
        });
    </script>
@stop
