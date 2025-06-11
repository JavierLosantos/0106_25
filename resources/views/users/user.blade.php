@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
    <!-- Container -->
   
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-center">Usuarios</h1>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right">
                            <a href="{{ url('/crear')}}" class="btn btn-info">Nuevo Usuario</a>
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
                                    <h3 class="card-title">Gestión de Usuarios</h3>
                                </div>

                                <div class="card-body">
                                    <!-- Table Wrapper with Responsive Class -->
                                    <div class="table-responsive">
                                        <table id="temp1" class="table table-striped table-bordered" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nombre</th>
                                                  
                                                    <th>Correo</th>
                                                    <th>Rol Asignado</th>
                                                    <th>Acción</th>
                                                    <th>Desactivar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($resultados as $key => $resultado)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                     
                                                        <td>{{ $resultado->name }}</td>
                                                        <td>{{ $resultado->email }}</td>
                                                        <td>{{ $resultado->role }}</td>
                                                        <td>
                                                            <a href="{{ url('user/perfil/'.$resultado['user_id'])}}" class="btn btn-warning btn-sm">Consultar</a>
                                                            <a href="{{ url('user/edit/'.$resultado['user_id'])}}" class="btn btn-info btn-sm">Editar</a>
                                                            <a href="{{ url('/users/assign-bonos/'.$resultado['user_id'])}}" class="btn btn-success btn-sm">Asignar Bono</a>
                                                            <a href="{{ url('user/delete/'.$resultado['user_id'])}}" class="btn btn-danger btn-sm">Eliminar</a>
                                                        </td>
                                                        <td>
                                                            <form action="{{ route('users.toggleStatus', $resultado->user_id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm {{ $resultado->status === 'activo' ? 'btn-warning' : 'btn-success' }}">
                                                                    {{ $resultado->status === 'activo' ? '🔴 Desactivar' : '🟢 Activar' }}
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
   
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    
@endsection

@section('js')
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
            $('#temp1').DataTable({
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
