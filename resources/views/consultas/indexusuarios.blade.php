@extends('layouts.app')

@section('title', 'Consultas de ' . $user->name)

@section('content_header')
    <h1>Consultas de {{ $user->name }}</h1>
@stop

@section('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@stop

@section('content')
<style>
/* Estilo para el contenedor del checkbox */
.table .form-check-input {
    width: 20px; /* Ancho del checkbox */
    height: 20px; /* Alto del checkbox */
    margin-top: -1px; /* Ajuste del margen superior */
    margin-left: 0; /* Ajuste del margen izquierdo */
}
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
    <a href="{{ route('usuarios.consultas') }}" class="btn btn-secondary mb-3">Volver a Usuarios</a>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Consultas</h3>
        </div>
        <div class="card-body">
             <form id="update-form" action="{{ route('consultas.validar') }}" method="POST">
                            @csrf
                            @method('PATCH')
            <table id="consultasTable" class="table table-striped dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Imágenes</th>
                       <th><input type="checkbox" id="select-all"> Validar</th> 
                    </tr>
                </thead>
                <tbody>
                    @foreach($consultas as $consulta)
                        <tr>
                            <td>{{ $consulta->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $consulta->texto_largo }}</td>
                            <td>
                            @foreach($consulta->imagenes as $imagen)
                            <a href="{{ asset(str_replace('public/', '', $imagen->imagen_path)) }}" download>
    <img src="{{ asset(str_replace('public/', '', $imagen->imagen_path)) }}" width="80" class="img-thumbnail">
</a>

    </a>
@endforeach

                            </td>
                          <td>
                                @if ($consulta->Revisado == 1)
                                    <span class="text-success">✔️</span> {{-- o usa un icono con Bootstrap --}}
                                @else
                                    <input class="form-check-input" type="checkbox" name="selected[]" value="{{ $consulta->id }}">
                                @endif
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
              <button type="submit" class="btn btn-primary mt-3">Realizar Validacion de Seguimiento</button>
               </form>
        </div>
    </div>
@endsection

@section('js')
    <!-- jQuery y DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    // Checkbox para seleccionar todo
    $('#select-all').on('click', function() {
        $('input[name="selected[]"]').prop('checked', this.checked);
    });
</script>
    <script>
        
        $(document).ready(function() {
            $('#consultasTable').DataTable({
                responsive: true,
                autoWidth: false,
                order: [[0, 'desc']], // Ordena por fecha descendente
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" // Traducción a español
                }
            });
        });
    </script>
@stop


