
@extends('layouts.app')

@section('title', 'Dashboard Nutricionista')

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
        }
    </style>
@stop

@section('content')
<div class="content-wrapper">
    <div class="row">
        <!-- Calendario de Citas -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Calendario de Citas</h3>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Consultas Realizadas Esta Semana -->
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Consultas Realizadas Esta Semana</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Texto</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($consultas as $consulta)
                                <tr>
                                    <td>{{ $consulta->id }}</td>
                                    <td>{{ $consulta->texto_largo }}</td>
                                    <td>{{ \Carbon\Carbon::parse($consulta->created_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/es.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        themeSystem: 'bootstrap',
        initialView: 'dayGridWeek', // Vista por semanas
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        validRange: {
            start: new Date().toISOString().split('T')[0] // Bloquea las fechas pasadas
        },
        events: @json($eventos), // Pasamos los eventos desde el backend a FullCalendar
        dateClick: function(info) {
            console.log('Fecha seleccionada:', info.dateStr);
        }
    });

    calendar.render();
});
</script>
@stop
