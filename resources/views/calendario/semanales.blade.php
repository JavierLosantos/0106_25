@extends('layouts.app')


@section('title', 'Listado de Citas')



@section('css')
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <!-- DataTables CSS para Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
@stop

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                
                <div class="col-sm-right-6">
                    <div class="float-sm-right">
                        <a href="{{ route('cita.createnew') }}" class="btn btn-primary">Crear Nueva Cita</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Filtro por fechas -->
    <form action="{{ route('citas.listar') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <label for="fechaInicio" class="form-label">Fecha de Inicio:</label>
                <input type="date" id="fechaInicio" name="fechaInicio" class="form-control" value="{{ request('fechaInicio') }}">
            </div>
            <div class="col-md-4">
                <label for="fechaFin" class="form-label">Fecha de Fin:</label>
                <input type="date" id="fechaFin" name="fechaFin" class="form-control" value="{{ request('fechaFin') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Tabla de Citas -->
    <div class="table-responsive">
        
        <table id="citasTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID Cita</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
              
                @foreach ($citas as $cita)
                    <tr>
                        <td>{{ $cita->ID_Cita }}</td>
                        <td>{{ $cita->name }}</td>
                        <td>{{ $cita->fecha }}</td>
                        <td>{{ $cita->hora_inicio }}</td>
                        <td>{{ $cita->hora_fin }}</td>
                        <td>{{ $cita->citaestado }}</td>
                         <td>
                             <a href="{{ route('citas.datos.show', $cita->ID_Cita )}}" class="btn btn-success btn-sm">Ver Datos</a>
                            <!-- Mostrar el botón "Añadir Datos" solo si no existe un control realizado -->
                         @if(!$cita->cita_dato_id)
                            <a href="{{ route('citas.datos.create', $cita->ID_Cita )}}" class="btn btn-info btn-sm">Añadir Datos</a>
                        @endif
                       @if($cita->consultas === null)
                        <a href="{{ route('citas.datos.form', $cita->ID_Cita )}}" class="btn btn-secondary btn-sm">Formulario</a>
                        @endif
                            <!-- Botón para cambiar el estado -->
                            @if($cita->citaestado != 'Finalizado')
                                <form action="{{ route('citas.finalizar', $cita->ID_Cita) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm">Finalizar</button>
                                </form>
                            @else
                                <span class="text-success">Finalizado</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

   
@endsection

@section('js')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS para Bootstrap 5 -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <!-- Librerías para exportar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <!-- Font Awesome para íconos en botones -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
          
            console.log("Documento cargado y listo para ejecutar el DataTable");
            $('#citasTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Exportar Excel',
                        className: 'btn btn-success'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> Exportar PDF',
                        className: 'btn btn-danger'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir',
                        className: 'btn btn-info'
                    }
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-MX.json"
                }
            });
             @if(Session::has('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: "{{ Session::get('success') }}",
                    showConfirmButton: true
                });
            @elseif(Session::has('error'))
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: "{{ Session::get('error') }}",
                    showConfirmButton: true
                });
            @endif
        });
    </script>

   
@stop



