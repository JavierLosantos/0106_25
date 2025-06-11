@extends('layouts.app')
@section('title', 'Planificador de Comidas')
@php
    use Stichoza\GoogleTranslate\GoogleTranslate;
    $translator = new GoogleTranslate('es');
@endphp
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container mt-4">
            <h2 class="text-center mb-4">🍽 Planificador de Comidas Semanal</h2>

            <form method="POST" action="{{ route('menu-auto.generate') }}" class="mb-5">
                @csrf
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label>Calorías por día (opcional)</label>
                        <input type="number" name="targetCalories" class="form-control" value="{{ old('targetCalories', $targetCalories ?? '') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Tipo de dieta</label>
                        <select name="diet" class="form-control">
                            <option value="">Cualquiera</option>
                            <option value="vegetarian">Vegetariana</option>
                            <option value="vegan">Vegana</option>
                            <option value="ketogenic">Keto</option>
                            <option value="gluten free">Sin gluten</option>
                            <option value="paleo">Paleo</option>
                            <option value="low carb">Baja en carbohidratos</option>
                            <option value="low fat">Baja en grasas</option>
                            <option value="dairy free">Sin lácteos</option>
                            <option value="lacto ovo vegetarian">Lacto-ovo vegetariana</option>
                            <option value="whole30">Whole30</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Tipo de cocina</label>
                        <select name="cuisine" class="form-control">
                            <option value="">Cualquiera</option>
                            <option value="italian">Italiana</option>
                            <option value="mexican">Mexicana</option>
                            <option value="asian">Asiática</option>
                            <option value="american">Americana</option>
                            <option value="indian">India</option>
                            <option value="chinese">China</option>
                            <option value="french">Francesa</option>
                            <option value="thai">Tailandesa</option>
                            <option value="japanese">Japonesa</option>
                            <option value="mediterranean">Mediterránea</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Tipo de comida</label>
                        <select name="mealType" class="form-control">
                            <option value="">Cualquiera</option>
                            <option value="breakfast">Desayuno</option>
                            <option value="lunch">Almuerzo</option>
                            <option value="dinner">Cena</option>
                            <option value="snack">Merienda</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Intolerancias (separadas por coma)</label>
                        <input type="text" name="intolerances" class="form-control" placeholder="gluten,dairy,nuts" value="{{ old('intolerances', $intolerances ?? '') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Excluir ingredientes (separados por coma)</label>
                        <input type="text" name="excludeIngredients" class="form-control" placeholder="peanuts,shrimp,soy" value="{{ old('excludeIngredients', $excludeIngredients ?? '') }}">
                    </div>

                    <div class="col-md-4 align-self-end">
                        <button type="submit" class="btn btn-primary btn-block">Generar Plan</button>
                    </div>
                </div>
            </form>

            @if(!empty($mealPlan))
                <div class="row">
                    @foreach($mealPlan as $day => $meals)
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0 text-uppercase">{{ $translator->translate($day) }}</h5>
                                </div>
                                <div class="card-body">
                                    @foreach($meals['meals'] as $meal)
                                        <div class="mb-3">
                                            <h6>{{$translator->translate( $meal['title']) }}</h6>
                                            <p>Tiempo: {{ $meal['readyInMinutes'] }} min | Porciones: {{ $meal['servings'] }}</p>
                                            <a href="{{ route('menu-auto.recipe', ['id' => $meal['id']]) }}" class="btn btn-sm btn-success">Ver receta</a>
                                            <hr>
                                        </div>
                                    @endforeach
                                    <p><strong>Calorías estimadas:</strong> {{ round($meals['nutrients']['calories']) }} kcal</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
