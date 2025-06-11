@extends('layouts.app')

@section('title', 'Lista de Tenants')

@section('content')
    <!-- Container -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-center">Lista de Tenants</h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#createTenantModal">
                            Nuevo Espacio de trabajo
                        </button>
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
                                <h3 class="card-title">Gestión de Tenants</h3>
                            </div>

                            <div class="card-body">
                                <!-- Table Wrapper with Responsive Class -->
                                <div class="table-responsive">
                                    <table id="temp1" class="table table-striped table-bordered" style="width: 100%;">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Nombre</th>
                                                <th>Fecha de Creación</th>
                                                <th>Acción</th>
                                                <th>Eliminar</th> <!-- Nueva columna para el botón de eliminar -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tenants as $key => $tenant)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $tenant->name }}</td>
                                                    <td>{{ $tenant->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        <a href="{{ url('/admin/dashboard?tenant_id=' . $tenant->id) }}" class="btn btn-primary btn-sm">
                                                            Seleccionar
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <!-- Formulario para eliminar el tenant -->
                                                        <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este tenant?');">
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

    <!-- Modal -->
    <div class="modal fade" id="createTenantModal" tabindex="-1" aria-labelledby="createTenantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTenantModalLabel">Crear Nuevo Espacio de trabajo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form to create new tenant -->
                    <form id="createTenantForm" action="{{ route('tenants.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="tenantName">Nombre del Espacio de trabajo</label>
                            <input type="text" class="form-control" id="tenantName" name="name" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Crear Entorno</button>
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
            $('#temp1').DataTable({
                responsive: true, // Activar la capacidad de respuesta
                autoWidth: false, // Desactivar ajuste automático de columnas
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
        });
    </script>
@endsection
