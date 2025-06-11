@extends('layouts.app')
@section('title', 'Dashboard')





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
                <div class="col-lg-8">
                    <h2 class="text-center">Productos en {{ ucfirst($supermercado) }} - {{ ucfirst($categoria) }}</h2>

                    @if(count($productos) > 0)
                        <div class="row">
                            @foreach($productos as $producto)
                                <div class="col-md-4 mb-3">
                                    <div class="card shadow-sm">
                                        <img src="{{ $producto['image_url'] ?? 'https://via.placeholder.com/150' }}" class="card-img-top img-fluid" alt="Imagen">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $producto['product_name'] ?? 'Sin nombre' }}</h5>
                                            <p class="card-text">Marca: <strong>{{ $producto['brands'] ?? 'Desconocida' }}</strong></p>
                                            <p class="card-text">Código de barras: {{ $producto['code'] }}</p>
                                            <a href="https://world.openfoodfacts.org/product/{{ $producto['code'] }}" target="_blank" class="btn btn-primary btn-sm">
                                                Ver en Open Food Facts
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center">No se encontraron productos.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
