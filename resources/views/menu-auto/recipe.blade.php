@extends('layouts.app')
@section('title', $recipe['title'])

@php
    use Stichoza\GoogleTranslate\GoogleTranslate;
    $translator = new GoogleTranslate('es');
@endphp

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container mt-4">
            <h2 class="text-center mb-4">{{ $translator->translate($recipe['title']) }}</h2>

            <div class="row">
                <!-- Información principal de la receta -->
                <div class="col-md-8">
                    <!-- Instrucciones -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $translator->translate('Instrucciones') }}</h5><br>
                            <div>
                                @php
                                    $cleanInstructions = strip_tags($recipe['instructions'] ?? '');
                                    $translatedInstructions = $translator->translate($cleanInstructions);
                                    $lines = preg_split('/(?<=[.?!])\s+/', $translatedInstructions); // divide en frases
                                @endphp
                    
                                @foreach($lines as $line)
                                    <p>{{ $line }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    

                    <!-- Ingredientes -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $translator->translate('Ingredientes') }}:</h5><br>
                            <ul>
                                @foreach($recipe['extendedIngredients'] as $ingredient)
                                    <li>{{ $translator->translate($ingredient['original'] ?? 'Ingrediente sin descripción') }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Información Nutricional -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $translator->translate('Información Nutricional') }}:</h5><br>
                            <ul>
                                <li><strong>{{ $translator->translate('Calorías') }}:</strong> {{ $recipe['nutrition']['nutrients'][0]['amount'] ?? 'No disponible' }} kcal</li>
                                <li><strong>{{ $translator->translate('Grasas') }}:</strong> {{ $recipe['nutrition']['nutrients'][1]['amount'] ?? 'No disponible' }} g</li>
                                <li><strong>{{ $translator->translate('Proteínas') }}:</strong> {{ $recipe['nutrition']['nutrients'][8]['amount'] ?? 'No disponible' }} g</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Más Información -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $translator->translate('Más Información') }}:</h5><br>
                            <ul>
                                <li><strong>{{ $translator->translate('Tiempo de preparación') }}:</strong> {{ $recipe['readyInMinutes'] }} minutos</li>
                                <li><strong>{{ $translator->translate('Porciones') }}:</strong> {{ $recipe['servings'] }} {{ $translator->translate('porciones') }}</li>
                                <li><strong>{{ $translator->translate('Fuente') }}:</strong> <a href="{{ $recipe['sourceUrl'] }}" target="_blank">{{ $recipe['creditsText'] }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Imagen de la receta -->
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <img src="{{ $recipe['image'] }}" alt="{{ $recipe['title'] }}" class="card-img-top">
                        <div class="card-body text-center">
                            <p><strong>{{ $translator->translate('Imagen de la receta') }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
