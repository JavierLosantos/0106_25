<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Cita</title>
    <style>
        /* Estilo general */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            position: relative;
        }

        /* Marca de agua */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 250px;
            color: rgba(0, 128, 0, 0.1); /* Verde claro */
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
        }

        /* Cabecera */
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        header img {
            width: 150px; /* Ajusta el tamaño del logo */
            margin-bottom: 10px;
        }

        header h1 {
            color: #28a745;
            font-size: 30px;
            font-weight: bold;
        }

        .user-name {
            font-size: 18px;
            color: #28a745;
            margin-top: 10px;
        }

        /* Secciones */
        .section {
            margin: 20px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .section h3 {
            background-color: #28a745;
            color: white;
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .data {
            font-size: 18px;
            margin-left: 10px;
            line-height: 1.6;
        }

        .data strong {
            color: #28a745;
        }

        /* Pie de página */
        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #888;
        }

        /* Estilo para cada sección */
        .section p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

    <!-- Marca de agua -->
    <div class="watermark">Nutrinaza</div>

    <!-- Cabecera con el logo y el nombre del usuario -->
    <header>
        <h1>Detalles de la Cita</h1>
        <!-- Mostrar el nombre del usuario logueado -->
        <p class="user-name">Usuario: {{ Auth::user()->name }}</p>
    </header>

    <!-- Información General -->
    <div class="section">
        <h3>Información General</h3>
        <p class="data"><strong>Peso:</strong> {{ $citaDato->peso }} kg</p>
        <p class="data"><strong>Altura:</strong> {{ $citaDato->altura }} m</p>
        <p class="data"><strong>IMC:</strong> {{ $citaDato->imc }}</p>
    </div>

    <!-- Composición Corporal -->
    <div class="section">
        <h3>Composición Corporal</h3>
        <p class="data"><strong>% Agua:</strong> {{ $citaDato->agua }}%</p>
        <p class="data"><strong>% Grasa:</strong> {{ $citaDato->grasa }}%</p>
    </div>

    <!-- Masa Muscular -->
    <div class="section">
        <h3>Masa Muscular</h3>
        <p class="data"><strong>Total:</strong> {{ $citaDato->masa_muscular }} kg</p>
        <p class="data"><strong>Brazo Izq:</strong> {{ $citaDato->masa_muscular_brazo_izq }} kg</p>
        <p class="data"><strong>Brazo Der:</strong> {{ $citaDato->masa_muscular_brazo_der }} kg</p>
    </div>

    <!-- Otros Datos -->
    <div class="section">
        <h3>Otros Datos</h3>
        <p class="data"><strong>Masa Ósea:</strong> {{ $citaDato->masa_osea }} kg</p>
        <p class="data"><strong>Edad Metabólica:</strong> {{ $citaDato->edad_metabolica }} años</p>
    </div>

    <!-- Pie de página -->
    <footer>
        <p>Generado por NUTRINAZA. <br> Todos los derechos reservados &copy; {{ date('Y') }}</p>
    </footer>

</body>
</html>


