@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
    <!-- Container -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-center">Editar Usuario</h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        <a href="{{ url('/usuarios') }}" class="btn btn-info">Volver a Usuarios</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header text-center">
                                <h3 class="card-title">Formulario de Edición de Usuario</h3>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('users.update', $resultados->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <!-- Nombre -->
                                    <div class="input-group mb-3">
                                        <input type="text" name="name" class="form-control" placeholder="Nombre completo" value="{{ $resultados->name }}" autofocus>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Email (solo de lectura) -->
                                    <div class="input-group mb-3">
                                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ $resultados->email }}" readonly>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-envelope"></span>
                                            </div>
                                        </div>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Teléfono -->
                                    <div class="input-group mb-3">
                                        <input type="text" name="telefono" class="form-control" placeholder="Teléfono" value="{{ $resultados->telefono }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-phone"></span>
                                            </div>
                                        </div>
                                        @error('telefono')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Género -->
                                    <div class="input-group mb-3">
                                        <select name="genero" class="form-control">
                                            <option value="" disabled {{ $resultados->genero ? '' : 'selected' }}>Selecciona el género</option>
                                            <option value="Masculino" {{ $resultados->genero == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                            <option value="Femenino" {{ $resultados->genero == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                            <option value="Otro" {{ $resultados->genero == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-venus-mars"></span>
                                            </div>
                                        </div>
                                        @error('genero')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Peso -->
                                    <div class="input-group mb-3">
                                        <input type="number" step="0.1" name="peso" class="form-control" placeholder="Peso (kg)" value="{{ $resultados->peso }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-weight"></span>
                                            </div>
                                        </div>
                                        @error('peso')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Altura -->
                                    <div class="input-group mb-3">
                                        <input type="number" step="0.1" name="altura" class="form-control" placeholder="Altura (m)" value="{{ $resultados->altura }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-ruler-vertical"></span>
                                            </div>
                                        </div>
                                        @error('altura')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Tipo de Alimentación -->
                                    <div class="input-group mb-3">
                                        <select name="tipo_alimentacion" class="form-control">
                                            <option value="" disabled {{ $resultados->tipo_alimentacion ? '' : 'selected' }}>Selecciona el tipo de alimentación</option>
                                            <option value="Omnivoro" {{ $resultados->tipo_alimentacion == 'Omnivoro' ? 'selected' : '' }}>Omnivoro</option>
                                            <option value="Vegetariano" {{ $resultados->tipo_alimentacion == 'Vegetariano' ? 'selected' : '' }}>Vegetariano</option>
                                            <option value="Vegano" {{ $resultados->tipo_alimentacion == 'Vegano' ? 'selected' : '' }}>Vegano</option>
                                        </select>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-utensils"></span>
                                            </div>
                                        </div>
                                        @error('tipo_alimentacion')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Deportista -->
                                    <div class="input-group mb-3">
                                        <select name="deportista" class="form-control">
                                            <option value="" disabled {{ is_null($resultados->deportista) ? 'selected' : '' }}>Selecciona si es deportista</option>
                                            <option value="1" {{ $resultados->deportista == 1 ? 'selected' : '' }}>Si</option>
                                            <option value="0" {{ $resultados->deportista == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-running"></span>
                                            </div>
                                        </div>
                                        @error('deportista')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Contraseña (nueva) -->
                                    <div class="input-group mb-3">
                                        <input type="password" name="password" class="form-control" placeholder="Nueva Contraseña">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-lock"></span>
                                            </div>
                                        </div>
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Confirmar Contraseña -->
                                    <div class="input-group mb-3">
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar Contraseña">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-lock"></span>
                                            </div>
                                        </div>
                                        @error('password_confirmation')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Rol -->
                                    <div class="input-group mb-3">
                                        <select name="role" class="form-control" {{ $resultados->role == 'Paciente' ? 'disabled' : '' }}>
                                            <option value="Paciente" {{ $resultados->role == 'Paciente' ? 'selected' : '' }}>Paciente</option>
                                            <option value="Admin" {{ $resultados->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user-tag"></span>
                                            </div>
                                        </div>
                                        @error('role')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Botón de Guardar Cambios -->
                                    <button type="submit" class="btn btn-block btn-flat btn-primary">
                                        <span class="fas fa-save"></span>
                                        Guardar Cambios
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@stop

@section('js')
    <!-- Tus scripts JS aquí -->
@stop
