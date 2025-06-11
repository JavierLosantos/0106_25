@extends('layouts.app')

@section('title', 'Asignar Bono a Usuario')

@section('content')
    <!-- Container -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-center">Asignar Bono a Usuario</h1>
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
                                <h3 class="card-title">Formulario para Asignar Bono</h3>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('users.assignBono', $user->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="user_id">Usuario:</label>
                                        <select name="user_id" id="user_id" class="form-control" disabled>
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        </select>
                                    </div>
                                           <input type="hidden" name= "user"  id="user" value="{{$user->id}}">
                                    <div class="form-group">
                                        <label for="bono_id">Seleccionar Bono:</label>
                                        <select name="bono_id" id="bono_id" class="form-control">
                                            @foreach ($bonos as $bono)
                                                <option value="{{ $bono->id }}">{{ $bono->nombre }} - {{ $bono->sesiones }} sesiones</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="pagado">Pagado:</label>
                                        <select name="pagado" id="pagado" class="form-control">
                                            <option value="SI">SI</option>
                                            <option value="NO">NO</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-success">Asignar Bono</button>
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
