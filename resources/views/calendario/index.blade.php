@extends('layouts.app')
@section('title', 'Calendario de Citas')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css">
    <style>
        #calendar {
            margin-top: 20px;
            border-radius: 8px;
        }
        .fc-toolbar {
            background-color: #007bff;
            color: white;
            border-radius: 8px;
        }
        .fc-daygrid-day-number {
            font-size: 1.2em;
        }
        .fc-daygrid-day.fc-day-today {
            background-color: #f0f8ff;
        }
        .fc-daygrid-day.fc-day-past {
            background-color: #f8f9fa !important;
        }
        .modal-header, .modal-footer {
            background-color: #007bff;
            color: white;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .modal-body {
            max-height: 500px;
            overflow-y: auto;
        }

        /* Estilos adicionales para la vista en dispositivos móviles */
        @media (max-width: 767px) {
            .fc-daygrid-day {
                padding: 5px;
            }

            .fc-daygrid-day-number {
                font-size: 1.1em;
            }

            /* Modificar el alto de los días para que se ajuste mejor */
            .fc-daygrid-day.fc-day-sun, .fc-daygrid-day.fc-day-sat {
                padding: 5px;
            }

            /* Modificar la vista de eventos en días con muchas citas */
            .fc-daygrid-day-top {
                position: relative;
            }

            .fc-daygrid-day-number {
                z-index: 1;
            }
        }
    </style>
@stop

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Calendario</h3>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Horarios Disponibles -->
    <div class="modal fade" id="horariosModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Horas Disponibles</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="reservaForm" action="{{ route('citas.crear') }}" method="POST">
                        @csrf
                        <input type="hidden" name="horario_disponible_id" id="horarioSeleccionado">
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        <ul id="horasDisponiblesList" class="list-group"></ul>
                        <button type="submit" class="btn btn-primary mt-3" id="confirmarReserva" disabled>Confirmar Reserva</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Citas Reservadas -->
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

@endsection



@section('js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        themeSystem: 'bootstrap',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        validRange: {
            start: new Date().toISOString().split('T')[0] // Bloquea las fechas pasadas
        },
        events: '/citas/obtener', // Carga las citas desde el backend
        dateClick: function(info) {
            var selectedDate = info.dateStr;
            fetch('/horarios/disponibles/' + selectedDate)
                .then(response => response.json())
                .then(data => {
                    mostrarModalHorarios(data.horas);
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar los horarios. Inténtalo más tarde.'
                    });
                });
        },
        eventClick: function(info) {
            let citaId = info.event.id;
            fetch('/citas/detalle/' + citaId)
                .then(response => response.json())
                .then(data => {
                    mostrarModalDetalles(data);
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar los detalles de la cita.'
                    });
                });
        },

        // Cambiar la vista para dispositivos móviles
        windowResize: function(view) {
            if (window.innerWidth <= 767) {
                calendar.changeView('listWeek'); // Cambia a vista lista en móvil
            } else {
                calendar.changeView('dayGridMonth'); // Vista mes en escritorio
            }
        }
    });

    calendar.render();
});

// Función para mostrar el modal de horarios disponibles
function mostrarModalHorarios(horas) {
    let list = document.getElementById('horasDisponiblesList');
    list.innerHTML = '';

    if (horas.length > 0) {
        horas.forEach(hora => {
            let li = document.createElement('li');
            li.classList.add('list-group-item', 'horario-disponible');
            li.textContent = `${hora.hora_inicio} - ${hora.hora_fin}`;
            li.dataset.id = hora.id;
            list.appendChild(li);
        });
    } else {
        list.innerHTML = '<p class="text-danger">No hay horas disponibles.</p>';
    }

    $('#horariosModal').modal('show');
}

// Función para mostrar el modal con detalles de la cita
function mostrarModalDetalles(cita) {
    let modalContent = `
        <p><strong>Fecha:</strong> ${cita.fecha}</p>
        <p><strong>Hora Inicio:</strong> ${cita.hora_inicio}</p>
        <p><strong>Hora Fin:</strong> ${cita.hora_fin}</p>
        <p><strong>Estado:</strong> ${cita.estado}</p>
        <form id="reservaForm" action="{{ route('citas.crear') }}" method="POST">
            @csrf
            <input type="hidden" name="horario_disponible_id" value="${cita.id}">
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <button type="submit" class="btn btn-primary mt-3">Reservar Cita</button>
        </form>
    `;

    document.querySelector('#horariosModal .modal-body').innerHTML = modalContent;
    $('#horariosModal').modal('show');
}

// Manejar la selección de horarios disponibles para confirmar la reserva
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('horario-disponible')) {
        document.querySelectorAll('.horario-disponible').forEach(item => {
            item.classList.remove('selected');
        });

        event.target.classList.add('selected');
        document.getElementById('horarioSeleccionado').value = event.target.dataset.id;
        document.getElementById('confirmarReserva').disabled = false;
    }
});
</script>
@stop


