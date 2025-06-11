@extends('layouts.app')

@section('title', 'Editar Menú Semanal')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
.select-wrapper {
    width: 400px; /* o el tamaño que prefieras */
    min-width: 260px;
}
</style>

@stop

@section('content_header')
    <h1>Editar Menú Semanal</h1>
@endsection

@section('content')
<div class="container py-4">
    @if(isset($usuario))
        <h5 class="text-muted">Usuario: {{ $usuario->name }}</h5>
    @endif

    @if(session('success'))
        <div class="alert alert-success mt-2 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4 d-flex align-items-end gap-3 flex-wrap">
        <div class="d-flex flex-column">
            <label class="form-label">Semana:</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ \Carbon\Carbon::parse($weekStart)->translatedFormat('d/m/Y') }}">
        </div>

        <div class="d-flex flex-column">
            <label class="form-label">Tipo:</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ $tipo }}">
        </div>

        <a href="{{ route('usuarios.consultas') }}" class="btn btn-secondary">Volver</a>
    </div>
    <div class="mb-3 text-end">
    <a href="{{ route('menu.pdf', ['id' => $menu->id]) }}" target="_blank" class="btn btn-outline-danger">
        🧾 Descargar PDF
    </a>
</div>


    @php
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $meals = ['breakfast', 'lunch', 'dinner', 'snack'];
    @endphp

    <form method="post" action="{{ route('menu.item.update.bulk', $menu->id) }}">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-success text-center">
                    <tr>
                        <th>Comida / Día</th>
                        @foreach($days as $day)
                            <th>{{ ucfirst($day) }}</th>
                        @endforeach
                        <th class="bg-info text-white">Totales</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($meals as $meal)
                        <tr>
                            <td class="text-white bg-success text-center align-middle">{{ ucfirst($meal) }}</td>
                            @foreach($days as $day)
                                @php
                                    $key = $day . '_' . $meal;
                                    $menuDia = $menuDias[$key] ?? null;
                                    $itemIds = $menuDia?->items ?? [];
                                    $quantities = $menuDia?->quantities ?? [];
                                @endphp
                                <td>
                                    <div class="item-group d-flex flex-column gap-2" data-day="{{ $day }}" data-meal="{{ $meal }}">
                                        @foreach($itemIds as $index => $itemId)
                                            <div class="select-wrapper d-flex gap-2 align-items-center">
                                                <select name="items[{{ $day }}_{{ $meal }}][ids][]" class="form-select form-select-sm w-100">
                                                    <option value="">-- Seleccionar --</option>
                                                    @if($tipo === 'Producto')
                                                        @foreach($foods as $id => $food)
                                                            <option value="{{ $id }}"
                                                                data-calories="{{ $food->calories }}"
                                                                data-protein="{{ $food->proteina_g }}"
                                                                {{ $itemId == $id ? 'selected' : '' }}>
                                                                
                                                               {{ $food->name }} ({{ $food->calories }} cal, {{ $food->proteina_g }}g prot)
                                                              
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        @foreach($recipes as $recipe)
                                                            <option value="{{ $recipe->id }}"
                                                                data-calories="{{ $recipe->total_calories }}"
                                                                data-protein="{{ $recipe->total_protein_g }}"
                                                                {{ $itemId == $recipe->id ? 'selected' : '' }}>
                                                                {{ $recipe->name }} ({{ $recipe->total_calories }} cal, {{ $recipe->total_protein_g }}g prot)
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                                <input type="number" name="items[{{ $day }}_{{ $meal }}][quantities][]" class="form-control form-control-sm w-25" min="0" step="1" value="{{ $quantities[$index] ?? '' }}" placeholder="g/ml">

                                                <button type="button" class="btn btn-sm btn-outline-danger remove-select">❌</button>
                                            </div>
                                        @endforeach
                                    </div>

                                    <button type="button"
                                            class="btn btn-outline-success btn-sm mt-2 add-select"
                                            data-day="{{ $day }}"
                                            data-meal="{{ $meal }}"
                                            data-tipo="{{ $tipo }}">
                                        + Agregar {{ $tipo }}
                                    </button>
                                </td>
                            @endforeach
                            <td class="meal-total text-end small" data-meal="{{ $meal }}"></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <td class="text-center fw-bold">Total diario</td>
                        @foreach($days as $day)
                            <td class="day-total text-end small" data-day="{{ $day }}"></td>
                        @endforeach
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Guardar cambios</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const foods = @json($foods);
    const recipes = @json($recipes);

    function updateNutritionTotals() {
        const dailyTotals = {};
        const mealTotals = {};

        document.querySelectorAll('.item-group').forEach(group => {
            const day = group.dataset.day;
            const meal = group.dataset.meal;
            let totalCalories = 0;
            let totalProtein = 0;

           group.querySelectorAll('.select-wrapper').forEach(wrapper => {
    const select = wrapper.querySelector('select');
    const quantityInput = wrapper.querySelector('input[type="number"]');

    const selectedOption = select.options[select.selectedIndex];
    const calories = parseFloat(selectedOption.dataset.calories || 0);
    const protein = parseFloat(selectedOption.dataset.protein || 0);
    const quantity = parseFloat(quantityInput?.value || 0);

    totalCalories += (calories * quantity) / 100;
    totalProtein += (protein * quantity) / 100;
});


            let summary = group.querySelector('.nutrition-summary');
            if (!summary) {
                summary = document.createElement('div');
                summary.className = 'nutrition-summary text-end small text-muted mt-1';
                group.appendChild(summary);
            }
            summary.innerHTML = `Total: ${totalCalories.toFixed(0)} cal / ${totalProtein.toFixed(1)}g prot`;

            if (!dailyTotals[day]) dailyTotals[day] = { cal: 0, prot: 0 };
            if (!mealTotals[meal]) mealTotals[meal] = { cal: 0, prot: 0 };

            dailyTotals[day].cal += totalCalories;
            dailyTotals[day].prot += totalProtein;
            mealTotals[meal].cal += totalCalories;
            mealTotals[meal].prot += totalProtein;
        });

        for (const [day, { cal, prot }] of Object.entries(dailyTotals)) {
            const col = document.querySelector(`.day-total[data-day="${day}"]`);
            if (col) col.innerHTML = `${cal.toFixed(0)} cal<br>${prot.toFixed(1)}g`;
        }

        for (const [meal, { cal, prot }] of Object.entries(mealTotals)) {
            const row = document.querySelector(`.meal-total[data-meal="${meal}"]`);
            if (row) row.innerHTML = `${cal.toFixed(0)} cal<br>${prot.toFixed(1)}g`;
        }
    }

   function createSelect(day, meal, tipo) {
    const wrapper = document.createElement('div');
    wrapper.classList.add('select-wrapper', 'd-flex', 'gap-2', 'align-items-center');

    const select = document.createElement('select');
    select.name = `items[${day}_${meal}][ids][]`;
    select.classList.add('form-select', 'form-select-sm', 'w-100');

    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = '-- Seleccionar --';
    select.appendChild(defaultOption);

    if (tipo === 'Producto') {
        for (const [id, food] of Object.entries(foods)) {
            const opt = document.createElement('option');
            opt.value = id;
            opt.textContent = `${food.name} (${food.calories} cal, ${food.proteina_g}g prot)`;
            opt.dataset.calories = food.calories;
            opt.dataset.protein = food.proteina_g;
            select.appendChild(opt);
        }
    } else {
        for (const recipe of recipes) {
            const opt = document.createElement('option');
            opt.value = recipe.id;
            opt.textContent = `${recipe.name} (${recipe.total_calories} cal, ${recipe.total_protein_g}g prot)`;
            opt.dataset.calories = recipe.total_calories;
            opt.dataset.protein = recipe.total_protein_g;
            select.appendChild(opt);
        }
    }

    const quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.name = `items[${day}_${meal}][quantities][]`;
    quantityInput.classList.add('form-control', 'form-control-sm', 'w-25');
    quantityInput.min = '0';
    quantityInput.step = '1';
    quantityInput.value = '';
    quantityInput.placeholder = 'g/ml';
    quantityInput.value = 100;

    quantityInput.addEventListener('input', updateNutritionTotals);
    select.addEventListener('change', updateNutritionTotals);

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.classList.add('btn', 'btn-sm', 'btn-outline-danger', 'remove-select');
    removeBtn.textContent = '❌';
    removeBtn.addEventListener('click', () => {
        wrapper.remove();
        updateNutritionTotals();
    });

    wrapper.appendChild(select);
    wrapper.appendChild(quantityInput);
    wrapper.appendChild(removeBtn);

    return wrapper;
}


    document.querySelectorAll('.add-select').forEach(button => {
        button.addEventListener('click', function () {
            const day = this.dataset.day;
            const meal = this.dataset.meal;
            const tipo = this.dataset.tipo;
            const container = document.querySelector(`.item-group[data-day="${day}"][data-meal="${meal}"]`);
            const newSelect = createSelect(day, meal, tipo);
            container.appendChild(newSelect);
            updateNutritionTotals();
        });
    });

    document.querySelectorAll('.remove-select').forEach(btn => {
        btn.addEventListener('click', function () {
            this.closest('.select-wrapper').remove();
            updateNutritionTotals();
        });
    });

    group.querySelectorAll('select').forEach((select, i) => {
    const selectedOption = select.options[select.selectedIndex];
    const calories = parseFloat(selectedOption.dataset.calories || 0);
    const protein = parseFloat(selectedOption.dataset.protein || 0);

    const quantityInput = select.parentElement.querySelector('input[type="number"]');
    const quantity = parseFloat(quantityInput?.value || 0);

    // Suponiendo que los datos nutricionales son por 100g/ml
    totalCalories += (calories * quantity) / 100;
    totalProtein += (protein * quantity) / 100;
});


    updateNutritionTotals();
});
</script>
@endsection