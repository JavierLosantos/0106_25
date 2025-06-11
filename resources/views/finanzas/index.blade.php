@extends('layouts.app')

@section('title', 'Dashboard Finanzas')

@section('content_header')
    <h1>Dashboard de Finanzas</h1>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row">
                <!-- Tarjeta de Ingresos -->
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>${{ number_format($ingresos, 2) }}</h3>
                            <p>Ingresos Totales</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Gastos -->
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>${{ number_format($gastos, 2) }}</h3>
                            <p>Gastos Totales</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Balance -->
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>${{ number_format($balance, 2) }}</h3>
                            <p>Balance Disponible</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>

           
     <!-- Filtro por mes -->
     <div class="form-group">
        <label for="filtroMes">Filtrar por mes:</label>
        <select id="filtroMes" class="form-control">
            <option value="">Todos</option>
            <option value="1">Enero</option>
            <option value="2">Febrero</option>
            <option value="3">Marzo</option>
            <option value="4">Abril</option>
            <option value="5">Mayo</option>
            <option value="6">Junio</option>
            <option value="7">Julio</option>
            <option value="8">Agosto</option>
            <option value="9">Septiembre</option>
            <option value="10">Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
        </select>
    </div>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalAgregarGasto">
        <i class="fas fa-plus"></i> Añadir Gasto
    </button>
    

    <!-- Tabla de transacciones -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Historial de Transacciones</h3>
        </div>
        <div class="card-body">
            <table id="tablaTransacciones" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Usuario</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transacciones as $transaccion)
                        <tr data-mes="{{ \Carbon\Carbon::parse($transaccion->created_at)->format('m') }}">
                            <td>{{ $transaccion->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaccion->created_at)->format('d/m/Y') }}</td>
                            <td>{{ ucfirst($transaccion->tipo) }}</td>
                            <td>{{ $transaccion->descripcion }}</td>
                            @if($transaccion->tipo === "gasto")
                            <td>Nazaret</td>
                            @else
                            <td>{{ $transaccion->nombre }}</td>
                            @endif
                            <td>${{ number_format($transaccion->monto, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
 <!-- Gráfico de ingresos y gastos -->
 <div class="card">
    <div class="card-header">
        <h3 class="card-title">Gráfico de Ingresos vs Gastos</h3>
    </div>
    <div class="card-body">
        <canvas id="finanzasChart"></canvas>
    </div>
</div>
<div class="modal fade" id="modalAgregarGasto" tabindex="-1" aria-labelledby="modalAgregarGastoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarGastoLabel">Añadir Gasto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAgregarGasto">
                    @csrf
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                    </div>
                    <div class="form-group">
                        <label for="monto">Monto</label>
                        <input type="number" step="0.01" class="form-control" id="monto" name="monto" required>
                    </div>
                    <input type="hidden" name="tipo" value="gasto">
                    <input type="hidden" name="usuario" value="{{ auth()->user()->name }}">
                    <button type="submit" class="btn btn-danger">Guardar Gasto</button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
</div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<!-- DataTables y exportación -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
    // Datos desde Laravel a JavaScript
    var ingresosPorMes = @json($ingresosPorMes);
    var gastosPorMes = @json($gastosPorMes);

    // Nombres de los meses
    var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    // Convertir datos para el gráfico
    var ingresosData = Array(12).fill(0);
    var gastosData = Array(12).fill(0);

    for (var mes in ingresosPorMes) {
        ingresosData[mes - 1] = ingresosPorMes[mes];
    }

    for (var mes in gastosPorMes) {
        gastosData[mes - 1] = gastosPorMes[mes];
    }

    // Generar gráfico dinámico
    var ctx = document.getElementById('finanzasChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [
                {
                    label: 'Ingresos',
                    data: ingresosData,
                    backgroundColor: 'rgba(40, 167, 69, 0.7)'
                },
                {
                    label: 'Gastos',
                    data: gastosData,
                    backgroundColor: 'rgba(220, 53, 69, 0.7)'
                }
            ]
        }
    });

</script>
<script>
    $(document).ready(function() {
        var table = $('#tablaTransacciones').DataTable({
            dom: 'Bfrtip', // Agrega los botones
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Exportar a Excel',
                    className: 'btn btn-success',
                    title: 'Transacciones Finanzas',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4] // Exporta solo estas columnas
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> Exportar a PDF',
                    className: 'btn btn-danger',
                    title: 'Transacciones Finanzas',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4] // Exporta solo estas columnas
                    },
                    customize: function (doc) {
                        doc.styles.title = {
                            fontSize: 14,
                            bold: true,
                            alignment: 'center'
                        };
                        doc.styles.tableHeader = {
                            bold: true,
                            fontSize: 12,
                            color: 'white',
                            fillColor: '#343a40'
                        };
                        doc.styles.tableBodyEven = {
                            fillColor: '#f3f3f3'
                        };
                        doc.styles.tableBodyOdd = {
                            fillColor: '#ffffff'
                        };
                    }
                }
            ]
        });

        // Agregar eventos a los botones fuera de DataTables
        $('#exportExcel').on('click', function() {
            table.button('.buttons-excel').trigger();
        });

        $('#exportPDF').on('click', function() {
            table.button('.buttons-pdf').trigger();
        });

        // Filtrar por mes
        $('#filtroMes').on('change', function() {
            var mes = $(this).val();
            table.columns(1).search(mes ? '^' + mes + '.*' : '', true, false).draw();
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#formAgregarGasto').on('submit', function(e) {
            e.preventDefault();

            let formData = {
                _token: $('input[name="_token"]').val(),
                descripcion: $('#descripcion').val(),
                monto: $('#monto').val(),
                tipo: 'gasto',
                usuario: $('input[name="usuario"]').val()
            };

            $.ajax({
                url: "{{ route('transacciones.store') }}", // Asegúrate de tener esta ruta en web.php
                type: "POST",
                data: formData,
                success: function(response) {
                    $('#modalAgregarGasto').modal('hide');
                    $('#tablaTransacciones').DataTable().row.add([
                        response.id,
                        response.fecha,
                        'Gasto',
                        response.descripcion,
                        response.usuario,
                        `$${parseFloat(response.monto).toFixed(2)}`
                    ]).draw();

                    alert('Gasto añadido con éxito');
                },
                error: function(xhr) {
                    alert('Error al guardar el gasto');
                }
            });
        });
    });
</script>

@endsection
