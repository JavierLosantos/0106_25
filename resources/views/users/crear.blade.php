@extends('layouts.app')

@section('title', 'Registrar Usuario')

@section('content')
    <!-- Container -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-center">Registrar un nuevo usuario</h1>
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
                                <h3 class="card-title">Formulario de Registro de Usuario</h3>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('user.store') }}">
                                    @csrf

                                    <!-- Nombre -->
                                    <div class="input-group mb-3">
                                        <input type="text" name="name" class="form-control" placeholder="Nombre completo" value="{{ old('name') }}" autofocus>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="input-group mb-3">
                                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
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
                                        <input type="text" name="telefono" class="form-control" placeholder="Teléfono" value="{{ old('telefono') }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-phone"></span>
                                            </div>
                                        </div>
                                        @error('telefono')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Fecha de Nacimiento -->
                                    <div class="input-group mb-3">
                                        <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-calendar-alt"></span>
                                            </div>
                                        </div>
                                        @error('fecha_nacimiento')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Género -->
                                    <div class="input-group mb-3">
                                        <select name="genero" class="form-control">
                                            <option value="" disabled selected>Selecciona el género</option>
                                            <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                            <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                            <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
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
                                        <input type="number" step="0.1" name="peso" class="form-control" placeholder="Peso (kg)" value="{{ old('peso') }}">
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
                                        <input type="number" step="0.1" name="altura" class="form-control" placeholder="Altura (m)" value="{{ old('altura') }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-ruler-vertical"></span>
                                            </div>
                                        </div>
                                        @error('altura')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Alimentación -->
                                    <div class="input-group mb-3">
                                        <select name="tipo_alimentacion" class="form-control">
                                            <option value="" disabled selected>Selecciona el tipo de alimentación</option>
                                            <option value="Omnivoro" {{ old('tipo_alimentacion') == 'Omnivoro' ? 'selected' : '' }}>Omnívoro</option>
                                            <option value="Vegetariano" {{ old('tipo_alimentacion') == 'Vegetariano' ? 'selected' : '' }}>Vegetariano</option>
                                            <option value="Vegano" {{ old('tipo_alimentacion') == 'Vegano' ? 'selected' : '' }}>Vegano</option>
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
                                            <option value="" disabled selected>Selecciona si es deportista</option>
                                            <option value="1" {{ old('deportista') == 1 ? 'selected' : '' }}>Sí</option>
                                            <option value="0" {{ old('deportista') == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-dumbbell"></span>
                                            </div>
                                        </div>
                                        @error('deportista')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Contraseña -->
                                    <div class="input-group mb-3">
                                        <input type="password" name="password" class="form-control" placeholder="Contraseña">
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
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar contraseña">
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
                                        <select name="role" class="form-control">
                                            <option value="Paciente" {{ old('role') == 'Paciente' ? 'selected' : '' }}>Paciente</option>
                                            <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
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

                                    <!-- Botón de Registro -->
                                    <button type="submit" class="btn btn-block btn-flat btn-primary">
                                        <span class="fas fa-user-plus"></span>
                                        Registrar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
