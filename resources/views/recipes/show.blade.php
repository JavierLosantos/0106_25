@extends('layouts.app')
@section('title', $recipe['title'])

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" >
@endsection

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary mb-4">← Volver a la búsqueda</a>
                    <div class="card shadow">
                        <img src="{{ $recipe['image'] ?? 'https://via.placeholder.com/600' }}" class="card-img-top" alt="{{ $recipe['title'] }}">
                        <div class="card-body">
                            <h2 class="card-title">{{ $recipe['title'] }}</h2>
                            <p class="card-text">{!! $recipe['summary'] !!}</p>

                            <h5>Instrucciones:</h5>
                            <p>{!! $recipe['instructions'] ?? 'No hay instrucciones disponibles.' !!}</p>

                            <a href="{{ $recipe['sourceUrl'] ?? '#' }}" target="_blank" class="btn btn-primary">
                                Ver en sitio original
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
