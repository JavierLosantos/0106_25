@extends('layouts.app')
@section('title', 'Dashboard')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
@endsection

@section('content')
<div class="content-wrapper py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white text-center">
                        <h5 class="mb-0"><i class="fas fa-apple-alt"></i> Información del Producto</h5>
                    </div>
                    <div class="card-body text-center">
                        <h3 class="text-primary">{{ $producto['product_name'] ?? 'Producto sin nombre' }}</h3>
                        <img src="{{ $producto['image_url'] ?? 'https://via.placeholder.com/150' }}"
                             class="img-fluid rounded mb-3" style="max-height: 200px;" alt="Imagen del producto">

                        <h5 class="text-muted mb-4">Marca: <strong>{{ $producto['brands'] ?? 'Desconocida' }}</strong></h5>

                        <h4 class="mt-4"><i class="fas fa-apple-alt"></i> Valores Nutricionales</h4>
                        <div class="d-flex flex-wrap justify-content-center mt-3">
                            @php
                                $nutrients = [
                                    'fat' => 'Grasas',
                                    'saturated-fat' => 'Grasas saturadas',
                                    'sugars' => 'Azúcares',
                                    'salt' => 'Sal'
                                ];
                            @endphp
                            @foreach($nutrients as $key => $label)
                                @php
                                    $value = $producto['nutriments'][$key] ?? 0;
                                    $class = $value > 10 ? 'badge-danger' : 'badge-success';
                                @endphp
                                <div class="m-1">
                                    <span class="badge {{ $class }} p-2">
                                        {{ $label }} en cantidad {{ $value > 10 ? 'elevada' : 'baja' }} ({{ $value }}%)
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <h4 class="mt-4"><i class="fas fa-balance-scale"></i> Información Nutricional</h4>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Componente</th>
                                        <th>Por 100 g/ml</th>
                                        <th>Por porción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $datos = [
                                            'energy-kcal' => 'Energía (kcal)',
                                            'fat' => 'Grasas',
                                            'saturated-fat' => 'Grasas Saturadas',
                                            'sugars' => 'Azúcares',
                                            'proteins' => 'Proteínas',
                                            'carbohydrates' => 'Carbohidratos'
                                        ];
                                    @endphp
                                    @foreach($datos as $key => $label)
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td>{{ $producto['nutriments'][$key . '_100g'] ?? 'No disponible' }}</td>
                                            <td>{{ $producto['nutriments'][$key . '_serving'] ?? 'No disponible' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <h4 class="mt-4"><i class="fas fa-chart-pie"></i> Nutrición</h4>
                        <div class="alert alert-success d-flex flex-column flex-sm-row align-items-center text-left">
                            <img src="{{ $producto['nutriscore_image_url'] ?? 'https://static.openfoodfacts.org/images/attributes/dist/nutriscore-a-new-en.svg' }}"
                                 alt="Nutri-Score" class="mb-2 mb-sm-0 mr-sm-3" style="max-width: 100px;">
                            <div>
                                <h5 class="mb-1">Nutri-Score: {{ strtoupper($producto['nutriscore_grade'] ?? 'N/A') }}</h5>
                                <p class="mb-0">Muy buena calidad nutricional</p>
                            </div>
                        </div>

                        <a href="/producto" class="btn btn-primary btn-lg mt-3">
                            <i class="fas fa-arrow-left"></i> Buscar otro producto
                        </a>
                    </div>
                </div>

                <div class="text-center mt-2">
                    <small class="text-muted">Información obtenida de Open Food Facts</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
