@extends('layouts.app')

@section('title', 'Lista de Bonos')

@section('content')
    <!-- Container -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-center">Lista de Bonos</h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#createBonoModal">
                            Nuevo Bono
                        </button>  
                        <div class="float-sm-right">
                            <a href="{{ route('bonos.show') }}" class="btn btn-primary mb-3">Ver Bonos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header text-center">
                                <h3 class="card-title">Gestión de Bonos</h3>
                            </div>

                            <div class="card-body">
                                <!-- Table Wrapper with Responsive Class -->
                                <div class="table-responsive">
                                    <table id="bonosTable" class="table table-striped table-bordered" style="width: 100%;">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                                <th>Precio</th>
                                                <th>Sesiones</th>
                                                <th>Acción</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bonos as $key => $bono)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $bono->nombre }}</td>
                                                    <td>{{ $bono->descripcion }}</td>
                                                    <td>{{ $bono->precio }}</td>
                                                    <td>{{ $bono->sesiones }}</td>
                                                    <td>
                                                        <!-- Button to edit the bono -->
                                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editBonoModal" data-id="{{ $bono->id }}" data-nombre="{{ $bono->nombre }}" data-descripcion="{{ $bono->descripcion }}" data-precio="{{ $bono->precio }}" data-sesiones="{{ $bono->sesiones }}">
                                                            Editar
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <!-- Form to delete the bono -->
                                                        <form action="{{ route('bonos.destroy', $bono->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este bono?');">
                                                                Eliminar
                                                            </button>
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
        </section>
    </div>

    <!-- Modal para Crear Bono -->
    <div class="modal fade" id="createBonoModal" tabindex="-1" aria-labelledby="createBonoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createBonoModalLabel">Crear Nuevo Bono</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form to create new bono -->
                    <form action="{{ route('bonos.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nombre">Nombre del Bono</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio</label>
                            <input type="number" class="form-control" id="precio" name="precio" required>
                        </div>
                        <div class="form-group">
                            <label for="sesiones">Sesiones</label>
                            <input type="number" class="form-control" id="sesiones" name="sesiones" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Crear Bono</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Bono -->
    <div class="modal fade" id="editBonoModal" tabindex="-1" aria-labelledby="editBonoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBonoModalLabel">Editar Bono</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form to edit bono -->
                    <form id="editBonoForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="editNombre">Nombre del Bono</label>
                            <input type="text" class="form-control" id="editNombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="editDescripcion">Descripción</label>
                            <input type="text" class="form-control" id="editDescripcion" name="descripcion" required>
                        </div>
                        <div class="form-group">
                            <label for="editPrecio">Precio</label>
                            <input type="number" class="form-control" id="editPrecio" name="precio" required>
                        </div>
                        <div class="form-group">
                            <label for="editSesiones">Sesiones</label>
                            <input type="number" class="form-control" id="editSesiones" name="sesiones" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Bono</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
@endsection

@section('adminlte_js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- Bootstrap 4 JS (needed for modals) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#bonosTable').DataTable({
                responsive: true,
                autoWidth: false,
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
                    }
                }
            });

            // Rellenar el modal de edición con los datos del bono
            $('#editBonoModal').on('show.bs.modal', function (e) {
                var button = $(e.relatedTarget);
                var bonoId = button.data('id');
                var bonoNombre = button.data('nombre');
                var bonoDescripcion = button.data('descripcion');
                var bonoPrecio = button.data('precio');
                var bonoSesiones = button.data('sesiones');

                var modal = $(this);
                modal.find('#editBonoForm').attr('action', '/bonos/' + bonoId);
                modal.find('#editNombre').val(bonoNombre);
                modal.find('#editDescripcion').val(bonoDescripcion);
                modal.find('#editPrecio').val(bonoPrecio);
                modal.find('#editSesiones').val(bonoSesiones);
            });
        });
    </script>
@endsection
