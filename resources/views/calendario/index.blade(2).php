@extends('layouts.app2')
@section('title', 'Calendario de Citas')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css">
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
    <div class="card mt-4">
        <div class="card-header">
            <h3>Citas Reservadas</h3>
        </div>
        <div class="card-body">
            <table id="citasTable" class="table table-bordered table-striped table-responsive">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora Inicio</th>
                        <th>Hora Fin</th>
                        <th>Estado</th>
                        <th>Añadir Calendario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
@foreach($citas as $cita)
    <tr>
        <td>{{ $cita->fecha }}</td>
        <td>{{ $cita->hora_inicio }}</td>
        <td>{{ $cita->hora_fin }}</td>
        <td>{{ $cita->estado }}</td>
        <td>
            <a href="{{ route('citas.descargarICS', $cita->id) }}" class="btn btn-info btn-sm">
                <i class="fas fa-calendar-alt"></i> Agregar a Google Calendar
            </a>
        </td>
        <td>
            @php
                $horaCita = \Carbon\Carbon::parse($cita->fecha . ' ' . $cita->hora_inicio);
                $mañana = \Carbon\Carbon::now()->addHours(24);
            @endphp
            @if($horaCita < $mañana)
                <span class="text-danger">Cita inferior a 24 horas</span>
            @else
                <form action="{{ route('citas.cancelar', $cita->id) }}" method="POST" class="cancelar-cita-form">
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
        events: '/citas/obtener', // Carga las citas desde el backend
   // Manejo de clic en las fechas
   dateClick: function(info) {
            var selectedDate = info.dateStr;
            fetch('/horarios/disponibles/' + selectedDate )
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

     
        // Manejo de clic en los eventos (citas)
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
    // Asegurarse de que no se haga click en el calendario cuando ya se esté mostrando el modal de horarios
    if (event.target.classList.contains('horario-disponible')) {
        // Remover la clase de selección de cualquier otro elemento
        document.querySelectorAll('.horario-disponible').forEach(item => {
            item.classList.remove('selected');
        });

        // Agregar la clase al elemento seleccionado
        event.target.classList.add('selected');

        // Asignar el ID del horario seleccionado al input oculto
        document.getElementById('horarioSeleccionado').value = event.target.dataset.id;

        // Habilitar el botón de confirmar
        document.getElementById('confirmarReserva').disabled = false;
    }
});

// Estilos en línea o en tu CSS
const style = document.createElement('style');
style.innerHTML = `
    .horario-disponible.selected {
        background-color: #28a745 !important;
        color: white !important;
    }
`;
document.head.appendChild(style);


</script>
@stop








