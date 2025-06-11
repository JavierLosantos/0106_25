@extends('layouts.app')

@section('title', 'Menú Semanal')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .select-wrapper {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    width: 300px; /* Ancho fijo para cada bloque (ajusta a lo que necesites) */
}

.select-wrapper > div {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.select-wrapper select.form-select {
    flex: 1 1 auto; /* Ocupa el espacio restante */
    min-width: 0; /* Evita que se reduzca demasiado */
}

.select-wrapper input.weight-input {
    width: 70px; /* Ancho fijo para input de gramos */
    flex-shrink: 0; /* No se reduce */
}

.select-wrapper button.remove-select {
    flex-shrink: 0; /* No se reduce */
    width: 30px;
    padding: 0 5px;
    font-size: 1rem;
    line-height: 1;
}
.day-total, .meal-total {
    min-width: 110px;  /* Ancho mínimo para que no se achique */
    max-width: 130px;  /* Máximo para uniformidad */
    white-space: normal; /* Permitir salto de línea */
    word-break: break-word; /* Para que el texto se ajuste */
    vertical-align: middle;
    padding: 0.5rem;
    font-size: 0.85rem;
    text-align: center;
}
</style>
@stop

@section('content_header')
    <h1>Menú Semanal</h1>
@endsection

@section('content')
<div class="container py-4">
    @if(isset($usuario))
        <h5 class="text-muted">Usuario: {{ $usuario->name }}</h5>
    @endif
    @if($menu && $menu->exists)
        <div class="alert alert-success d-flex align-items-center gap-2">
    <i class="bi bi-check-circle-fill"></i>
    Menú ya creado para esta semana.
</div>
@endif



    @if(session('success'))
        <div class="alert alert-success mt-2 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="get" action="{{ route('menu.semanal') }}" class="mb-4 d-flex align-items-end gap-3 flex-wrap">
        <input type="hidden" name="usuario_id" value="{{ request('usuario_id', $usuario->id ?? auth()->id()) }}">

        <div class="d-flex flex-column">
            <label for="week_start" class="form-label">Semana que empieza:</label>
            <input type="date" id="week_start" name="week_start" value="{{ $weekStart }}" class="form-control">
        </div>

        <div class="d-flex flex-column">
            <label for="tipo" class="form-label">Tipo:</label>
            <select name="tipo" id="tipo" class="form-control">
                <option value="Producto" {{ $tipo === 'Producto' ? 'selected' : '' }}>Producto</option>
                <option value="Receta" {{ $tipo === 'Receta' ? 'selected' : '' }}>Receta</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Generar</button>
    </form>

    @php
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $meals = ['breakfast', 'lunch', 'dinner', 'snack'];
    @endphp

    <form method="post" action="{{ route('menu.item.store.bulk', $menu->id) }}">
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
                                <td>
                                    <div class="item-group d-flex flex-column gap-2" data-day="{{ $day }}" data-meal="{{ $meal }}">
                                        @foreach($menu->items->where('day', $day)->where('meal', $meal) as $item)
                                            <div class="select-wrapper d-flex flex-column gap-1">
                                                <div class="d-flex align-items-center gap-2">
                                                    <select name="items[{{ $day }}_{{ $meal }}][ids][]" class="form-select form-select-sm w-100">
                                                        <option value="">-- Seleccionar --</option>
                                                        @if($tipo === 'Producto')
                                                            @foreach($foods as $id => $food)
                                                                <option value="{{ $id }}"
                                                                    data-calories="{{ $food->calories }}"
                                                                    data-protein="{{ $food->proteina_g }}"
                                                                    data-fat="{{ $food->grasas_g }}"
                                                                    data-carb="{{ $food->cho_t_g }}"
                                                                    {{ $item->food_id == $id ? 'selected' : '' }}>
                                                                    {{ $food->name }} ({{ $food->calories }} cal, {{ $food->proteina_g }}g prot, {{ $food->grasas_g }}g grasa, {{ $food->cho_t_g }}g carb)
                                                                </option>
                                                            @endforeach
                                                        @else
                                                            @foreach($recipes as $recipe)
                                                                <option value="{{ $recipe->id }}"
                                                                    data-calories="{{ $recipe->total_calories }}"
                                                                    data-protein="{{ $recipe->total_protein_g }}"
                                                                    data-fat="{{ $recipe->total_fat_g ?? 0 }}"
                                                                    data-carb="{{ $recipe->total_carb_g ?? 0 }}"
                                                                    {{ $item->recipe_id == $recipe->id ? 'selected' : '' }}>
                                                                    {{ $recipe->name }} ({{ $recipe->total_calories }} cal, {{ $recipe->total_protein_g }}g prot, {{ $recipe->total_fat_g ?? 0 }}g grasa, {{ $recipe->total_carb_g ?? 0 }}g carb)
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                            
                                                    <input type="number" name="items[{{ $day }}_{{ $meal }}][weights][]" class="form-control form-control-sm weight-input" placeholder="g" min="0" step="1" style="width: 80px;" value="100">
                                            
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-select">❌</button>
                                                </div>
                                            
                                                <div class="nutrition-summary text-end small text-muted"></div>
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
        let dailyTotals = {};
        let mealTotals = {};

        document.querySelectorAll('.item-group').forEach(group => {
            const day = group.dataset.day;
            const meal = group.dataset.meal;

            let totalCalories = 0;
            let totalProtein = 0;
            let totalFat = 0;
            let totalCarb = 0;

            group.querySelectorAll('.select-wrapper').forEach(wrapper => {
                const select = wrapper.querySelector('select');
                const weightInput = wrapper.querySelector('.weight-input');
                const grams = parseFloat(weightInput?.value || 0);

                const selectedOption = select.options[select.selectedIndex];
                const calPer100 = parseFloat(selectedOption.dataset.calories || 0);
                const protPer100 = parseFloat(selectedOption.dataset.protein || 0);
                const fatPer100 = parseFloat(selectedOption.dataset.fat || 0);
                const carbPer100 = parseFloat(selectedOption.dataset.carb || 0);

                const scale = grams / 100;

                const itemCalories = calPer100 * scale;
                const itemProtein = protPer100 * scale;
                const itemFat = fatPer100 * scale;
                const itemCarb = carbPer100 * scale;

                totalCalories += itemCalories;
                totalProtein += itemProtein;
                totalFat += itemFat;
                totalCarb += itemCarb;

                const summary = wrapper.querySelector('.nutrition-summary');
                summary.innerHTML = `${itemCalories.toFixed(0)} cal / ${itemProtein.toFixed(1)}g prot / ${itemFat.toFixed(1)}g grasa / ${itemCarb.toFixed(1)}g carb`;
            });

            if (!dailyTotals[day]) dailyTotals[day] = { cal: 0, prot: 0, fat: 0, carb: 0 };
            if (!mealTotals[meal]) mealTotals[meal] = { cal: 0, prot: 0, fat: 0, carb: 0 };

            dailyTotals[day].cal += totalCalories;
            dailyTotals[day].prot += totalProtein;
            dailyTotals[day].fat += totalFat;
            dailyTotals[day].carb += totalCarb;

            mealTotals[meal].cal += totalCalories;
            mealTotals[meal].prot += totalProtein;
            mealTotals[meal].fat += totalFat;
            mealTotals[meal].carb += totalCarb;
        });

        for (const [day, { cal, prot, fat, carb }] of Object.entries(dailyTotals)) {
            const col = document.querySelector(`.day-total[data-day="${day}"]`);
            if (col) col.innerHTML = `${cal.toFixed(0)} cal<br>${prot.toFixed(1)}g prot<br>${fat.toFixed(1)}g grasa<br>${carb.toFixed(1)}g carb`;
        }

        for (const [meal, { cal, prot, fat, carb }] of Object.entries(mealTotals)) {
            const row = document.querySelector(`.meal-total[data-meal="${meal}"]`);
            if (row) row.innerHTML = `${cal.toFixed(0)} cal<br>${prot.toFixed(1)}g prot<br>${fat.toFixed(1)}g grasa<br>${carb.toFixed(1)}g carb`;
        }
    }

    function createSelect(day, meal, tipo) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('select-wrapper', 'd-flex', 'flex-column', 'gap-1');

        const row = document.createElement('div');
        row.classList.add('d-flex', 'align-items-center', 'gap-2');

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
                opt.textContent = `${food.name} (${food.calories} cal, ${food.proteina_g}g prot, ${food.grasas_g}g grasa, ${food.cho_t_g}g carb)`;
                opt.dataset.calories = food.calories;
                opt.dataset.protein = food.proteina_g;
                opt.dataset.fat = food.grasas_g;
                opt.dataset.carb = food.cho_t_g;
                select.appendChild(opt);
            }
        } else {
            for (const recipe of recipes) {
                const opt = document.createElement('option');
                opt.value = recipe.id;
                opt.textContent = `${recipe.name} (${recipe.total_calories} cal, ${recipe.total_protein_g}g prot, ${recipe.total_fat_g ?? 0}g grasa, ${recipe.total_carb_g ?? 0}g carb)`;
                opt.dataset.calories = recipe.total_calories;
                opt.dataset.protein = recipe.total_protein_g;
                opt.dataset.fat = recipe.total_fat_g ?? 0;
                opt.dataset.carb = recipe.total_carb_g ?? 0;
                select.appendChild(opt);
            }
        }

        const weightInput = document.createElement('input');
        weightInput.type = 'number';
        weightInput.name = `items[${day}_${meal}][weights][]`;
        weightInput.classList.add('form-control', 'form-control-sm', 'weight-input');
        weightInput.placeholder = 'g';
        weightInput.min = 0;
        weightInput.step = 1;
        weightInput.style.width = '80px';
        weightInput.value = 100;

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.classList.add('btn', 'btn-sm', 'btn-outline-danger', 'remove-select');
        removeBtn.textContent = '❌';

        row.appendChild(select);
        row.appendChild(weightInput);
        row.appendChild(removeBtn);

        const summary = document.createElement('div');
        summary.classList.add('nutrition-summary', 'text-end', 'small', 'text-muted');

        wrapper.appendChild(row);
        wrapper.appendChild(summary);

        return wrapper;
    }

    // Event delegation for add, remove, and inputs change
    document.body.addEventListener('click', e => {
        if (e.target.matches('.add-select')) {
            const day = e.target.dataset.day;
            const meal = e.target.dataset.meal;
            const tipo = e.target.dataset.tipo;

            const container = e.target.previousElementSibling; // .item-group
            const selectWrapper = createSelect(day, meal, tipo);
            container.appendChild(selectWrapper);
            updateNutritionTotals();
        }

        if (e.target.matches('.remove-select')) {
            const wrapper = e.target.closest('.select-wrapper');
            if (wrapper) {
                wrapper.remove();
                updateNutritionTotals();
            }
        }
    });

    document.body.addEventListener('change', e => {
        if (e.target.matches('select') || e.target.matches('.weight-input')) {
            updateNutritionTotals();
        }
    });

    // Inicializar totales al cargar
    updateNutritionTotals();
});
</script>
@endsection
