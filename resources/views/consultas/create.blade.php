@extends('layouts.app')

@section('title', 'Registrar Consulta')

@section('content')
    <!-- Container -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-center">Registrar nuevo seguimiento</h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        <a href="{{ url('/consultas') }}" class="btn btn-info">Volver a Consultas</a>
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
                                <h3 class="card-title">Formulario de Registro de Consulta</h3>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('consultas.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                   
<!--ue no sea posible cambiar el formulario desde consola-->     
                          <!-- Breve Resumen -->
                                    <div class="input-group mb-3">
                                        <textarea name="texto_largo" id="texto_largo" class="form-control" rows="4" placeholder="Breve Resumen" required></textarea>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-file-alt"></span>
                                            </div>
                                        </div>
                                        @error('texto_largo')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Subir Imágenes -->
                                    <div class="input-group mb-3">
                                        <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*" max="5" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-image"></span>
                                            </div>
                                        </div>
                                        @error('imagenes')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Botón de Guardar -->
                                    <button type="submit" class="btn btn-block btn-flat btn-primary">
                                        <span class="fas fa-save"></span>
                                        Guardar Consulta
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@stop
