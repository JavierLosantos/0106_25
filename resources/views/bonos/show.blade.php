@extends('layouts.app')

@section('title', 'Bonos de Todos los Usuarios')

@section('content')
    <!-- Container -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="row mb-2">
                
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header text-center">
                                <h3 class="card-title">Lista de Bonos de Usuarios</h3>
                            </div>

                            <div class="card-body">
                                @if (empty($usuariosConBonos))
                                    <p>No hay bonos disponibles con sesiones restantes para ningún usuario.</p>
                                @else
                                    <div class="table-responsive">
                                        <table id="citasTable" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Usuario</th>
                                                    <th>Nombre del Bono</th>
                                                    <th>Sesiones Restantes</th>
                                                    <th>Estado de Pago</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($usuariosConBonos as $item)
                                                    @foreach ($item['bonos'] as $bono)
                                                        <tr>
                                                            <td>{{ $item['usuario']->name }}</td>
                                                            <td>{{ $bono->nombre }}</td>
                                                            <td>{{ $bono->pivot->sesiones_restantes }}</td>
                                                            <td>{{ $bono->pivot->pagado }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@stop

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
        </script>
@stop
