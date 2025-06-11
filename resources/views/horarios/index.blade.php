@extends('layouts.app')

@section('title', 'Horarios Disponibles')

@section('css')
    <!-- Agregar el CSS de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">
@stop

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-center">Lista de Horario</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <a href="{{ route('horarios.create') }}" class="btn btn-primary mb-3">Crear Nuevo Horario</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Horarios</h3>
        </div>
        <div class="card-body">
            <table id="horariosTable" class="table table-striped table-bordered dt-responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora de Inicio</th>
                        <th>Hora de Fin</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($horarios as $horario)
                        <tr>
                            <td>{{ $horario->fecha }}</td>
                            <td>{{ $horario->hora_inicio }}</td>
                            <td>{{ $horario->hora_fin }}</td>
                            <td>
                                <form action="{{ route('horarios.destroy', $horario->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop


@section('js')
    <!-- jQuery y DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<!-- DataTables Responsive -->
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
<!-- Exportaciones -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#horariosTable').DataTable({
                responsive: true,
                autoWidth: false,
                
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-MX.json"
                }
            });
        });
    </script>
@stop
