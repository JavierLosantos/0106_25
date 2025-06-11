<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Semanal PDF</title>
    <style>
    
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #999;
            padding: 4px;
            word-wrap: break-word;
        }
        th {
            background-color: #d4edda;
            text-align: center;
        }
        .meal-label {
            background-color: #28a745;
            color: white;
            text-align: center;
            font-weight: bold;
        }
        .day-total, .meal-total {
            background-color: #bee5eb;
            font-weight: bold;
            text-align: right;
            font-size: 11px;
        }
        .nutrition-summary {
            font-size: 10px;
            color: #555;
            text-align: right;
            margin-top: 2px;
        }
        .title {
            margin-bottom: 10px;
        }
      .watermark {
            position: fixed;
            top: 35%;
            left: 15%;
            font-size: 140px; /* Aumentado */
            color: rgba(0, 128, 0, 0.06); /* Verde clarito con un poco más de opacidad */
            transform: rotate(-30deg);
            z-index: -1;
            pointer-events: none;
            white-space: nowrap;
        }


    </style>
</head>
<body>
    <div class="watermark">Nutrinaza</div>

    <h2 class="title">Menú Semanal</h2>

    @if(isset($usuario))
        <p><strong>Usuario:</strong> {{ $usuario->name }}</p>
    @endif
    <p><strong>Semana:</strong> {{ \Carbon\Carbon::parse($weekStart)->translatedFormat('d/m/Y') }}</p>
    <p><strong>Tipo:</strong> {{ $tipo }}</p>

    @php
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $dayNames = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        $meals = ['breakfast', 'lunch', 'dinner', 'snack'];
        $mealNames = ['Desayuno', 'Almuerzo', 'Cena', 'Snack'];
        $totalesDia = [];
        $totalesComida = [];
    @endphp

    <table>
        <thead>
            <tr>
                <th>Comida / Día</th>
                @foreach($dayNames as $name)
                    <th>{{ $name }}</th>
                @endforeach
                <th>Totales</th>
            </tr>
        </thead>
        <tbody>
            @foreach($meals as $mIndex => $meal)
                <tr>
                    <td class="meal-label">{{ $mealNames[$mIndex] }}</td>
                    @foreach($days as $day)
                        @php
                            $key = $day . '_' . $meal;
                            $menuDia = $menuDias[$key] ?? null;
                            $itemIds = $menuDia?->items ?? [];
                            $quantities = $menuDia?->quantities ?? [];

                            $totalCal = 0;
                            $totalProt = 0;
                        @endphp
                        <td>
                        @foreach($itemIds as $i => $itemId)
                            @php
                                $qty = $quantities[$i] ?? 0;
                                if ($tipo === 'Producto') {
                                    $food = $foods[$itemId] ?? null;
                                    if ($food) {
                                        $cal = ($food->calories * $qty) / 100;
                                        $prot = ($food->proteina_g * $qty) / 100;
                                        $totalCal += $cal;
                                        $totalProt += $prot;
                                        echo "<div style='margin-left: 8px'>• {$food->name} ({$qty}g/ml)</div>";
                                    }
                                } else {
                                    $recipe = $recipes[$itemId] ?? null;
                                    if ($recipe) {
                                        $cal = ($recipe->total_calories * $qty) / 100;
                                        $prot = ($recipe->total_protein_g * $qty) / 100;
                                        $totalCal += $cal;
                                        $totalProt += $prot;
                                        echo "<div style='margin-left: 8px'>• {$recipe->name} ({$qty}g/ml)</div>";
                                    }
                                }
                        
                                if (!isset($totalesDia[$day])) $totalesDia[$day] = ['cal' => 0, 'prot' => 0];
                                $totalesDia[$day]['cal'] += $cal;
                                $totalesDia[$day]['prot'] += $prot;
                        
                                if (!isset($totalesComida[$meal])) $totalesComida[$meal] = ['cal' => 0, 'prot' => 0];
                                $totalesComida[$meal]['cal'] += $cal;
                                $totalesComida[$meal]['prot'] += $prot;
                            @endphp
                        @endforeach

                            <div class="nutrition-summary">
                                {{ round($totalCal) }} cal / {{ round($totalProt, 1) }}g
                            </div>
                        </td>
                    @endforeach
                    <td class="meal-total">
                        {{ round($totalesComida[$meal]['cal'] ?? 0) }} cal<br>
                        {{ round($totalesComida[$meal]['prot'] ?? 0, 1) }}g prot
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="meal-label">Total diario</td>
                @foreach($days as $day)
                    @php
                        $total = $totalesDia[$day] ?? ['cal' => 0, 'prot' => 0];
                    @endphp
                    <td class="day-total">
                        {{ round($total['cal']) }} cal<br>
                        {{ round($total['prot'], 1) }}g prot
                    </td>
                @endforeach
                <td></td>
            </tr>
        </tfoot>
    </table>
    <hr style="margin-top: 40px;">
<p style="font-size: 10px; color: #777; text-align: center;">
    © {{ now()->year }} Nutrinaza · Nutricionista colegiada NA00498 · Todos los derechos reservados.
</p>

</body>
</html>

