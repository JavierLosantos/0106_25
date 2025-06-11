@extends('layouts.app')


@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container py-4">
            <!-- Bienvenida -->
            <div class="row">
                <div class="col-12">
                    <div class="bg-success text-white text-center py-4 rounded shadow-sm">
                        <h2 class="fw-bold">🥗 HOLA, {{ strtoupper($user->name) }}</h2>
                        <p class="fs-5">Hoy es <span class="fw-semibold">{{ $fechaHoy }}</span></p>
                        <p class="fs-6">¡Recuerda mantener una alimentación balanceada y beber suficiente agua! 💧</p>
                    </div>
                </div>
            </div>

            <!-- Contenedor en 3 columnas -->
            <div class="row mt-4">
                <!-- Columna 1: Datos corporales -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-body">
                            <h3 class="card-title text-center text-secondary">📌 Datos Corporales</h3>
                            <hr>
                            @if($citadatos)
                                <p class="text-dark"><strong>⚖️ Peso:</strong> {{ $citadatos->peso }} kg</p>
                                <p class="text-dark"><strong>💧 Agua:</strong> {{ $citadatos->agua }} %</p>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $citadatos->agua }}%;" aria-valuenow="{{ $citadatos->agua }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <p class="text-dark mt-2"><strong>🔥 Grasa:</strong> {{ $citadatos->grasa }} %</p>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $citadatos->grasa }}%;" aria-valuenow="{{ $citadatos->grasa }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <p class="text-dark mt-2"><strong>💪 Masa Muscular:</strong> {{ $citadatos->masa_muscular }} kg</p>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($citadatos->masa_muscular / 50) * 100 }}%;" aria-valuenow="{{ $citadatos->masa_muscular }}" aria-valuemin="0" aria-valuemax="50"></div>
                                </div>
                            @else
                                <p class="text-muted">No hay datos disponibles.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Columna 2: Objetivo -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 text-center">
                        <div class="card-body">
                            <h3 class="card-title text-secondary">🎯 Objetivo</h3>
                            <hr>
                            <p class="fs-5 text-dark">{{ $objetivo }}</p>
                        </div>
                    </div>
                </div>

                  
                   <!-- Contenedor para el bono activo -->
<div class="col-12 col-md-4 mb-4">
    <div class="card shadow-lg border-0 rounded-3" style="background: #f3f9f4; box-shadow: 0 6px 18px rgba(0, 255, 0, 0.2);">
        <div class="card-body text-center p-4">
            <h3 class="card-title text-secondary fw-bold">🎟️ Bono Activo</h3>
            <hr class="my-3" style="border-top: 1px solid #66a182;">
            
            @if($hayBonoActivo)
                @foreach($bono as $b)
                    <div class="d-flex flex-column align-items-center my-4">
                        <div class="icon-wrapper mb-3" style="font-size: 60px; color: #4caf50;">
                            <i class="fas fa-gift"></i>
                        </div>
                        <div>
                            <p class="text-dark fs-5 fw-bold">
                                ¡Tienes <span class="text-warning">{{ $b->pivot->sesiones_restantes }}</span> sesiones restantes en el bono "<span class="text-success">{{ $b->nombre }}</span>"!
                            </p>
                          
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-danger fw-bold fs-4">No tienes bonos activos 😔</p>
             
            @endif
        </div>
    </div>
</div>




            </div>

            <!-- Nueva Sección: Gráfico Visual -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-body">
                            <h3 class="card-title text-center text-secondary">📊 Estado Corporal</h3>
                            <hr>
                            <canvas id="graficoCita"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <!-- Contenedor para las citas reservadas -->
               <!-- Contenedor de Citas Reservadas -->
<div class="card mt-4">
    <div class="card-header">
        <h3 class="text-primary"><i class="fas fa-calendar-check"></i> Citas Reservadas</h3>
    </div>
    <div class="card-body">
        
        <!-- 🌐 Vista en Escritorio: Tabla Completa -->
        <div class="d-none d-md-block">
            <table id="citasTable" class="table table-bordered table-hover text-center">
                <thead class="table-success">
                    <tr>
                        <th>Fecha</th>
                        <th>Hora Inicio</th>
                        <th>Hora Fin</th>
                        <th>Estado</th>
                        <th>Añadir a Calendario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($citas as $cita)
                        <tr>
                            <td>{{ $cita->fecha }}</td>
                            <td>{{ $cita->hora_inicio }}</td>
                            <td>{{ $cita->hora_fin }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">{{ ucfirst($cita->estado) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('citas.descargarICS', $cita->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-calendar-alt"></i> Google Calendar
                                </a>
                            </td>
                            <td>
                                @php
                                    $horaCita = \Carbon\Carbon::parse($cita->fecha . ' ' . $cita->hora_inicio);
                                    $mañana = \Carbon\Carbon::now()->addHours(24);
                                @endphp
                                @if($horaCita < $mañana)
                                    <span class="text-danger">Cita en menos de 24h</span>
                                @else
                                    <form action="{{ route('citas.cancelar', $cita->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- 📱 Vista en Móviles: Solo Fecha y Google Calendar -->
        <div class="d-block d-md-none">
            @foreach($citas as $cita)
                <div class="card mb-2 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">📅 {{ $cita->fecha }}</h5>
                            <a href="{{ route('citas.descargarICS', $cita->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-calendar-alt"></i>
                            </a>
                        </div>
                        <button class="btn btn-link text-secondary mt-2 w-100 text-start" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#detalleCita{{ $cita->id }}" 
                            aria-expanded="false" 
                            aria-controls="detalleCita{{ $cita->id }}">
                            Ver detalles ▼
                        </button>
                        <div id="detalleCita{{ $cita->id }}" class="collapse mt-2">
                            <p class="mb-1"><strong>⏰ Inicio:</strong> {{ $cita->hora_inicio }}</p>
                            <p class="mb-1"><strong>⏳ Fin:</strong> {{ $cita->hora_fin }}</p>
                            <p class="mb-1"><strong>📌 Estado:</strong> 
                                <span class="badge bg-warning text-dark">{{ ucfirst($cita->estado) }}</span>
                            </p>
                            @if($horaCita >= $mañana)
                                <form action="{{ route('citas.cancelar', $cita->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm w-100 mt-2">Cancelar</button>
                                </form>
                            @else
                                <p class="text-danger mt-2">No cancelable (menos de 24h)</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>


            
            
        </div>
    </div>

    <!-- Scripts para Bootstrap y Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap JS (necesario para el botón "Ver detalles") -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let ctx = document.getElementById('graficoCita').getContext('2d');

            let data = {
                labels: ['Peso', 'Agua', 'Grasa', 'Masa Muscular'],
                datasets: [{
                    label: 'Última Cita',
                    data: [
                        {{ $citadatos->peso ?? 0 }},
                        {{ $citadatos->agua ?? 0 }},
                        {{ $citadatos->grasa ?? 0 }},
                        {{ $citadatos->masa_muscular ?? 0 }}
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.4)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                    pointBorderColor: 'rgba(255, 255, 255, 1)',
                    pointRadius: 6
                }]
            };

            new Chart(ctx, {
                type: 'radar',
                data: data,
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            beginAtZero: true,
                            angleLines: { display: true },
                            suggestedMin: 0,
                            suggestedMax: 100
                        }
                    }
                }
            });
        });
    </script>
</div>
@endsection


