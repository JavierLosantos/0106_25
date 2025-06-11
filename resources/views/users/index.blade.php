@extends('layouts.app')

@section('title', 'Lista de Usuarios&File')

@section('css')
    <!-- CSS para DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
@stop

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-center">Gestion de Pacientes</h1>
            </div>
        </div>
    </div>
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h3 class="card-title">Gestión de Usuarios</h3>
                    </div>

                    <div class="card-body">
                        <!-- Table Wrapper with Responsive Class -->
                        <div class="table-responsive">
                            <table id="usersTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Archivos</th>
                                        <th>Subir Archivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <ul class="list-unstyled">
                                                    @foreach ($user->files as $file)
                                                        <li class="d-flex align-items-center w-100">
                                                            <a href="{{ asset($file->file_path) }}" target="_blank">
                                                                {{ $file->file_name }}
                                                            </a>
                                                            <form action="{{ route('users.deleteFile', $file->id) }}" method="POST" class="d-inline ms-auto">   
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm small-delete-btn ms-2">❌</button>
                                                            </form>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                <form action="{{ route('users.upload', $user->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="file" name="file" class="form-control form-control-sm mb-2">
                                                    <button type="submit" class="btn btn-success btn-sm w-100">📤 Subir</button>
                                                </form>
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
    </div>
</div>
</section>
@stop

@section('js')
    <!-- jQuery primero (necesario para DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Botones DataTables -->
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                responsive: true,
                autoWidth: false,
                dom: 'Bfrtip', // Esto es para los botones
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: '📋 Copiar'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '📊 Excel'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '📄 PDF'
                    },
                    {
                        extend: 'print',
                        text: '🖨️ Imprimir'
                    }
                ],
                language: {
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    zeroRecords: "No se encontraron resultados",
                    info: "Mostrando página _PAGE_ de _PAGES_",
                    infoEmpty: "No hay registros disponibles",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    search: "Buscar:",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    },
                    buttons: {
                        copy: "Copiar",
                        excel: "Exportar a Excel",
                        pdf: "Exportar a PDF",
                        print: "Imprimir"
                    }
                }
            });
        });
    </script>
@stop
