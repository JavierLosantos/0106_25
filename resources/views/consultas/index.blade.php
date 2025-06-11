@extends('layouts.app')

@section('title', 'Lista de Consultas')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-center">Lista de Consultas</h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        <a href="{{ route('consultas.create') }}" class="btn btn-primary">Crear Nueva Consulta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <h3 class="card-title">Consultas Registradas</h3>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="consultasTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Texto</th>
                                            <th>Fecha y Hora</th>
                                         
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($consultas as $consulta)
                                        <tr>
                                            <td>{{ $consulta->texto_largo }}</td>
                                            <td>{{ $consulta->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
    .small-delete-btn {
        padding: 2px 6px;
        font-size: 12px;
        line-height: 1;
        margin-left: 10px;
    }
</style>
@endsection

@section('js')
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
@endsection