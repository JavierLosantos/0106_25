@extends('layouts.app')
@section('css')
<style>
.table-responsive-custom {
  overflow-x: auto;
  max-width: 100%;
  margin-bottom: 1rem;
}

.table-responsive-custom table {
  width: 100%;
  table-layout: fixed; /* distribución uniforme */
  min-width: 800px; /* para evitar compresión extrema */
}

.table-responsive-custom th,
.table-responsive-custom td {
  white-space: nowrap;
}

/* Para la columna Fecha (por ejemplo, segunda columna) que se ajuste al contenido */
.table-responsive-custom th:nth-child(2),
.table-responsive-custom td:nth-child(2) {
  width: auto !important;
  white-space: normal; /* permitir que pueda romper si es necesario */
  table-layout: auto;
}


    
</style>
@endsection
@section('content')
<div class="content-wrapper">
    <div class="container py-4">
        <h2 class="mb-4 text-center">Resumen de Evolución del Paciente</h2>

        @if(isset($mensaje))
            <div class="alert alert-warning">{{ $mensaje }}</div>
        @else

            @if (!function_exists('comparacionIcono'))
                @php
                function comparacionIcono($valorNuevo, $valorViejo) {
                    if ($valorNuevo > $valorViejo) {
                        return '<span style="color:green;" title="Mejoró">&#9650;</span>'; // Flecha arriba verde
                    } elseif ($valorNuevo < $valorViejo) {
                        return '<span style="color:red;" title="Empeoró">&#9660;</span>'; // Flecha abajo roja
                    }
                    return '<span style="color:gray;" title="Sin cambio">&#8212;</span>'; // Guion gris
                }
                @endphp
            @endif
    <div class="table-responsive-custom">
            <table class="table table-striped table-hover text-center align-middle shadow-sm">
                <thead class="table-success">
                    <tr>
                        <th>Sesión</th>
                        <th>Fecha</th>
                        <th>Peso (kg)</th>
                        <th></th>
                        <th>IMC</th>
                        <th></th>
                        <th>Agua (%)</th>
                        <th></th>
                        <th>Grasa (%)</th>
                        <th></th>
                        <th>Edad Metabólica</th>
                        <th></th>
                        <th>Masa Muscular (kg)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($citas as $i => $cita)
                        @php 
                            $datos = $cita->datos;
                            $peso = $datos->peso ?? 0;
                            $imc = $datos->imc ?? 0;
                            $agua = $datos->agua ?? 0;
                            $grasa = $datos->grasa ?? 0;
                            $edadMetabolica = $datos->edad_metabolica ?? 0;
                            $masaMuscular = $datos->masa_muscular ?? 0;

                            if ($i > 0) {
                                $datosPrev = $citas[$i - 1]->datos;
                                $pesoPrev = $datosPrev->peso ?? 0;
                                $imcPrev = $datosPrev->imc ?? 0;
                                $aguaPrev = $datosPrev->agua ?? 0;
                                $grasaPrev = $datosPrev->grasa ?? 0;
                                $edadMetabolicaPrev = $datosPrev->edad_metabolica ?? 0;
                                $masaMuscularPrev = $datosPrev->masa_muscular ?? 0;
                            } else {
                                $pesoPrev = $imcPrev = $aguaPrev = $grasaPrev = $edadMetabolicaPrev = $masaMuscularPrev = 0;
                            }
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $cita->created_at->format('d/m/Y') }}</td>

                            <td>{{ $peso }}</td>
                            <td>{!! $i > 0 ? comparacionIcono($peso, $pesoPrev) : '' !!}</td>

                            <td>{{ $imc }}</td>
                            <td>{!! $i > 0 ? comparacionIcono($imc, $imcPrev) : '' !!}</td>

                            <td>{{ $agua }}</td>
                            <td>{!! $i > 0 ? comparacionIcono($agua, $aguaPrev) : '' !!}</td>

                            <td>{{ $grasa }}</td>
                            <td>{!! $i > 0 ? comparacionIcono($grasa, $grasaPrev) : '' !!}</td>

                            <td>{{ $edadMetabolica }}</td>
                            <td>{!! $i > 0 ? comparacionIcono($edadMetabolica, $edadMetabolicaPrev) : '' !!}</td>

                            <td>{{ $masaMuscular }}</td>
                            <td>{!! $i > 0 ? comparacionIcono($masaMuscular, $masaMuscularPrev) : '' !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
            <div class="row mt-5">
                <div class="col-md-6 mb-4">
                    <canvas id="pesoChart" style="width:100%; height:350px;"></canvas>
                </div>
                <div class="col-md-6 mb-4">
                    <canvas id="grasaChart" style="width:100%; height:350px;"></canvas>
                </div>
                <div class="col-md-6 mb-4">
                    <canvas id="musculoChart" style="width:100%; height:350px;"></canvas>
                </div>
                <div class="col-md-6 mb-4">
                    <canvas id="imcChart" style="width:100%; height:350px;"></canvas>
                </div>
            </div>

            <div class="mt-4 p-3 bg-light rounded shadow-sm">
                <h4>Resumen final:</h4>
                <p>
                    El paciente comenzó el tratamiento el <strong>{{ $citas->first()->created_at->format('d/m/Y') }}</strong>
                    con un peso de <strong>{{ $citas->first()->datos->peso ?? 0 }} kg</strong>, IMC de <strong>{{ $citas->first()->datos->imc ?? 0 }}</strong>,
                    y grasa corporal de <strong>{{ $citas->first()->datos->grasa ?? 0 }}%</strong>.<br>
                    En la última sesión ({{ $citas->last()->created_at->format('d/m/Y') }}),
                    su peso es <strong>{{ $citas->last()->datos->peso ?? 0 }} kg</strong>, IMC <strong>{{ $citas->last()->datos->imc ?? 0 }}</strong>,
                    y grasa <strong>{{ $citas->last()->datos->grasa ?? 0 }}%</strong>.<br>
                    Se observa una mejora significativa en su composición corporal.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.onload = function() {
    var fechas = {!! json_encode($citas->pluck('created_at')->map(function($d) {
        return $d->format('d/m');
    })) !!};

    function normalizar(arr) {
        var res = [];
        for(var i = 0; i < arr.length; i++) {
            res.push(arr[i] === null ? 0 : arr[i]);
        }
        return res;
    }

    var pesos = normalizar({!! json_encode($citas->pluck('datos.peso')) !!});
    var grasas = normalizar({!! json_encode($citas->pluck('datos.grasa')) !!});
    var musculos = normalizar({!! json_encode($citas->pluck('datos.masa_muscular')) !!});
    var imcs = normalizar({!! json_encode($citas->pluck('datos.imc')) !!});

    function crearGrafico(id, label, data, color) {
        var ctx = document.getElementById(id).getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: fechas,
                datasets: [{
                    label: label,
                    data: data,
                    borderColor: color,
                    backgroundColor: color , // Más claro para relleno
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#333',
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                interaction: {
                    mode: 'nearest',
                    intersect: false
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#666'
                        }
                    },
                    y: {
                        beginAtZero: false,
                        ticks: {
                            color: '#666'
                        }
                    }
                }
            }
        });
    }

crearGrafico('pesoChart', 'Peso (kg)', pesos, '#0000FF');  // azul
crearGrafico('grasaChart', 'Grasa (%)', grasas, '#FF0000');  // rojo
crearGrafico('musculoChart', 'Masa Muscular (kg)', musculos, '#008000');  // verde
crearGrafico('imcChart', 'IMC', imcs, '#FFA500');  // naranja

}
</script>
@endsection




