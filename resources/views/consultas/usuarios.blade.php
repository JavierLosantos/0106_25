@extends('layouts.app')

@section('title', 'Usuarios con Consultas')

@section('content_header')
    <h1>Usuarios con Consultas</h1>
@stop

@section('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@stop

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="row mb-2">
            
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Usuarios</h3>
        </div>
        <div class="card-body">
            <table id="usuariosTable" class="table table-striped dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                  <a href="{{ url('/users/assign-bonos/'.$usuario->id)}}" class="btn btn-info btn-sm">Asignar Bono</a>
                                <a href="{{ route('consultas.usuario', $usuario->id) }}" class="btn btn-primary btn-sm">
                                    Seguimientos
                                </a>
                                <a href="{{ route('citas.datos.usuario', $usuario->id) }}" class="btn btn-success btn-sm">
                                    Ver Citas 
                                </a>
                                  <a href="{{ route('citas.menu.usuario', $usuario->id) }}" class="btn btn-warning btn-sm">
                                    Ver Menus
                                </a>
                                <a href="{{ route('citas.resumen', $usuario->id) }}" class="btn btn-info">
                                        📈 Ver evolución del paciente
                                    </a>

                               
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

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
            $('#usuariosTable').DataTable({
                responsive: true,
                autoWidth: false,
                
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-MX.json"
                }
            });
        });
    </script>
@stop

