@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('content_header')
    <h1 class="text-center">Perfil de Usuario</h1>
@stop

@section('content')
<div class="content-wrapper">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3>{{ $usuario->name }}</h3>
                    <p class="mb-0"><i class="fas fa-envelope"></i> {{ $usuario->email }}</p>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($usuario->name) }}&size=150" 
                             class="rounded-circle shadow" alt="Avatar">
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Teléfono:</strong> {{ $usuario->telefono ?? 'No especificado' }}</li>
                        <li class="list-group-item"><strong>Fecha de Nacimiento:</strong> {{ $usuario->fecha_nacimiento ?? 'No especificado' }}</li>
                        <li class="list-group-item"><strong>Género:</strong> {{ $usuario->genero ?? 'No especificado' }}</li>
                        <li class="list-group-item"><strong>Peso:</strong> {{ $usuario->peso ?? 'No especificado' }} kg</li>
                        <li class="list-group-item"><strong>Altura:</strong> {{ $usuario->altura ?? 'No especificado' }} m</li>
                        <li class="list-group-item"><strong>Tipo de Alimentación:</strong> {{ $usuario->tipo_alimentacion ?? 'No especificado' }}</li>
                        <li class="list-group-item"><strong>Deportista:</strong> {{ $usuario->deportista ? 'Sí' : 'No' }}</li>
                        <li class="list-group-item"><strong>Rol:</strong> {{ $usuario->role }}</li>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('users.edit', $usuario->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
