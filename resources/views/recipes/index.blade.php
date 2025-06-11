@extends('layouts.app')
@section('title', 'Buscar Recetas')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" >
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css" >
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css" >
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css" >
@endsection

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h2 class="text-center mb-4">Buscar Recetas</h2>

                    <form method="GET" action="{{ route('recipes.index') }}" class="mb-5">
                        <div class="input-group">
                            <input type="text" name="query" class="form-control" placeholder="Ej: pollo, pasta, arroz..." value="{{ $query }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Buscar</button>
                            </div>
                        </div>
                    </form>

                    @if(count($recipes) > 0)
                        <div class="row">
                            @foreach($recipes as $recipe)
                                <div class="col-md-4 mb-4">
                                    <div class="card shadow-sm">
                                        <img src="{{ $recipe['image'] ?? 'https://via.placeholder.com/300' }}" class="card-img-top img-fluid" alt="Imagen receta">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $recipe['title'] }}</h5>
                                            <a href="{{ route('recipes.show', $recipe['id']) }}" class="btn btn-sm btn-success">
                                                Ver detalles
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center">No se encontraron recetas para "{{ $query }}".</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
