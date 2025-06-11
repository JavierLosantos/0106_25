@extends('layouts.app')

@section('title', 'Menús de ' . $usuario->name)

@section('content_header')
    <h1>Menús de {{ $usuario->name }}</h1>
@stop

@section('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@stop

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <a href="{{ route('usuarios.consultas') }}" class="btn btn-secondary mb-3">Volver a Usuarios</a>
            <a href="{{ route('menu.semanal', ['usuario_id' => $usuario->id]) }}" class="btn btn-primary mb-3">+ Crear Menu</a>


            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de Menús</h3>
                </div>
                <div class="card-body">
                    <table id="menusTable" class="table table-striped dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Semana</th>
                                <th>Fecha de Creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                                <tr>
                                    <td>{{ $menu->id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($menu->week_start)->translatedFormat('d/m/Y') }}</td>
                                    <td>{{ $menu->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        
                                            <a href="{{ route('menu.edit', ['menu' => $menu->id]) }}" class="btn btn-primary btn-sm">
                                               Ver Menú
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
    <!-- jQuery y DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#menusTable').DataTable({
                responsive: true,
                autoWidth: false,
                order: [[1, 'desc']],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                }
            });
        });
    </script>
@stop
