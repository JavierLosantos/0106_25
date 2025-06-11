@extends('layouts.app')

@section('title', 'Dashboard Alimentos')

@section('content_header')
    <h1>Dashboard de Alimentos</h1>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Lista de Alimentos</h1>

            
                <form method="GET" action="{{ route('foods.index') }}" class="w-full max-w-md mb-2">
                    <input
                        name="search"
                        type="text"
                        value="{{ request('search') }}"
                        placeholder="🔍 Buscar alimento por nombre..."
                        class="w-full p-3 border border-gray-300 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                    >
                </form>

                <button type="button" onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded h-[50px]">+ Nuevo</button>
            
        </div>

        <table class="min-w-full text-sm border bg-white">
            <thead class="bg-gray-100 text-xs">
                <tr>
                    <th class="p-2">Nombre</th>
                    <th class="p-2">Categoría</th>
                    <th class="p-2">Calorías</th>
                    <th class="p-2">Proteína (g)</th>
                    <th class="p-2">Grasas (g)</th>
                    <th class="p-2">CHO Disp. (g)</th>
                    <th class="p-2">CHO Total (g)</th>
                    <th class="p-2">Fibra (g)</th>
                    <th class="p-2">Ca (mg)</th>
                    <th class="p-2">P (mg)</th>
                    <th class="p-2">Fe (mg)</th>
                    <th class="p-2">Mg (mg)</th>
                    <th class="p-2">Zn (mg)</th>
                    <th class="p-2">Cu (mg)</th>
                    <th class="p-2">Na (mg)</th>
                    <th class="p-2">K (mg)</th>
                    <th class="p-2">Vit. A (ER)</th>
                    <th class="p-2">β-caroteno</th>
                    <th class="p-2">Tiamina</th>
                    <th class="p-2">Riboflavina</th>
                    <th class="p-2">Niacina</th>
                    <th class="p-2">Vit. B6</th>
                    <th class="p-2">Vit. C</th>
                    <th class="p-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($foods as $food)
                <tr class="border-b" data-id="{{ $food->id }}">
                    <td contenteditable="true" onblur="saveEdit(this, 'name')">{{ $food->name }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'category')">{{ $food->category }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'calories')">{{ $food->calories }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'proteina_g')">{{ $food->proteina_g }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'grasas_g')">{{ $food->grasas_g }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'cho_d_g')">{{ $food->cho_d_g }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'cho_t_g')">{{ $food->cho_t_g }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'fibra_t_g')">{{ $food->fibra_t_g }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'calcio_mg')">{{ $food->calcio_mg }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'fosforo_mg')">{{ $food->fosforo_mg }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'hierro_mg')">{{ $food->hierro_mg }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'magnesio_mg')">{{ $food->magnesio_mg }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'zinc_mg')">{{ $food->zinc_mg }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'cobre_mg')">{{ $food->cobre_mg }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'sodio_mg')">{{ $food->sodio_mg }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'potasio_mg')">{{ $food->potasio_mg }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'vitamina_a_er')">{{ $food->vitamina_a_er }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'b_caroteno_et')">{{ $food->b_caroteno_et }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'tiamina_mg')">{{ $food->tiamina_mg }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'riboflavina_mg')">{{ $food->riboflavina_mg }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'niacina_mg')">{{ $food->niacina_mg }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'vitamina_b6_mg')">{{ $food->vitamina_b6_mg }}</td>
                    <td contenteditable="true" onblur="saveEdit(this, 'acido_asc_mg')">{{ $food->acido_asc_mg }}</td>
                    <td>
                        <button onclick="deleteFood({{ $food->id }}, this)" class="text-red-600 hover:underline text-sm">Eliminar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $foods->appends(['search' => request('search')])->links() }}
    </div>

    <!-- Modal -->
    <div id="foodModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-3xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl mb-4 font-semibold">Añadir nuevo alimento</h2>
            <form id="createForm" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                @foreach ([
                    'name', 'category', 'calories', 'proteina_g', 'grasas_g', 'cho_d_g', 'cho_t_g', 'fibra_t_g',
                    'calcio_mg', 'fosforo_mg', 'hierro_mg', 'magnesio_mg', 'zinc_mg', 'cobre_mg', 'sodio_mg',
                    'potasio_mg', 'vitamina_a_er', 'b_caroteno_et', 'tiamina_mg', 'riboflavina_mg', 'niacina_mg',
                    'vitamina_b6_mg', 'acido_asc_mg'
                ] as $field)
                    <input name="{{ $field }}" placeholder="{{ ucwords(str_replace('_', ' ', $field)) }}" class="p-2 border" {{ in_array($field, ['name', 'category']) ? 'required' : '' }}>
                @endforeach

                <div class="col-span-full flex justify-end space-x-2 mt-4">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Guardar</button>
                    <button type="button" onclick="closeModal()" class="text-gray-600">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
 <script src="https://cdn.tailwindcss.com"></script>
<script>
   
    function openModal() {
        document.getElementById('foodModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('foodModal').classList.add('hidden');
    }

    function saveEdit(el, field) {
        const row = el.closest('tr');
        const id = row.dataset.id;
        const value = el.textContent;

        fetch(`/foods/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ [field]: value })
        })
        .then(res => res.json())
        .then(data => console.log(data.message));
    }

    document.getElementById('createForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch('/foods', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(food => {
            const tbody = document.querySelector('table tbody');
            const tr = document.createElement('tr');
            tr.classList.add('border-b');
            tr.setAttribute('data-id', food.id);
            tr.innerHTML = `
                <td contenteditable="true" onblur="saveEdit(this, 'name')">${food.name}</td>
                <td contenteditable="true" onblur="saveEdit(this, 'category')">${food.category}</td>
                <td contenteditable="true" onblur="saveEdit(this, 'calories')">${food.calories}</td>
                <td contenteditable="true" onblur="saveEdit(this, 'proteina_g')">${food.proteina_g}</td>
                <td class="text-gray-500 text-xs">Auto</td>
            `;
            tbody.appendChild(tr);
            closeModal();
            this.reset();
        });
    });

    function deleteFood(id, btn) {
        if (confirm('¿Estás seguro de que deseas eliminar este alimento?')) {
            fetch(`/foods/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = btn.closest('tr');
                    row.remove();
                } else {
                    alert('Error al eliminar el alimento.');
                }
            });
        }
    }
</script>
@endsection
