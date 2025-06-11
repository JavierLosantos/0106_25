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
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Volver a Inicio</a>
                </div>
            </div>
        </div>
    </div>
        <div class="card-header bg-success text-white">
            <h3 class="card-title mb-0">Listado de Citas</h3>
        </div>
        <div class="card-body">
            @if($citas->count())
                <div class="table-responsive">
                    <table id="citasTable" class="table table-hover table-bordered dt-responsive nowrap" style="width:100%">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                              
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($citas as $cita)
                                <tr>
                                    <td>{{ $cita->id }}</td>
                                    <td>{{ $cita->fecha }}</td>
                                    <td>{{ $cita->hora_inicio }}</td>
                                   
                                    <td>
                                        @if($cita->estado == 'confirmada')
                                            <span class="badge badge-success">{{ ucfirst($cita->estado) }}</span>
                                        @elseif($cita->estado == 'pendiente')
                                            <span class="badge badge-warning">{{ ucfirst($cita->estado) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($cita->estado) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('citas.datos.show', $cita->ID_Cita) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Visualizar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                       
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    No se encontraron citas para este usuario.
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        /* Ajustes adicionales para móviles */
        @media (max-width: 576px) {
            .table-responsive {
                font-size: 0.9rem;
            }
            .btn {
                padding: 0.35rem 0.5rem;
                font-size: 0.8rem;
            }
        }
    </style>
@stop

@section('js')
    <!-- jQuery y Bootstrap JS ya incluidos en AdminLTE -->
    <!-- DataTables JS -->
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
        var userId = {{ auth()->id() }};
        
        // Inicializar DataTable
        $('#citasTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                @if(in_array(auth()->id(), [1, 4]))
                    {
                        extend: 'excelHtml5',
                        title: 'Citas del Usuario',
                        text: '<i class="fas fa-file-excel"></i> Excel'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Citas del Usuario',
                        text: '<i class="fas fa-file-pdf"></i> PDF'
                    },
                    {
                        extend: 'print',
                        title: 'Citas del Usuario',
                        text: '<i class="fas fa-print"></i> Imprimir'
                    },
                @endif
                {
                    extend: 'colvis',
                    text: '<i class="fas fa-columns"></i> Columnas'
                }
            ],
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            }
        });

        console.log('Vista de citas por usuario cargada');
    });
</script>

@stop

