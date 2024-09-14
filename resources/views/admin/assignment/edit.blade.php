@extends('master')
@section('title', 'Editar asignación')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.assignment.all') }}">Gestión de Asignaciones</a></li>
                        <li class="breadcrumb-item active">Editar asignación</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Editar Asignación de Rol</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Datos del usuario</h1>
        </div>

        <form class="form-horizontal" method="POST" action="{{ route('admin.assignment.update', $data->id) }}">

            @csrf

            <div class="card-body">
                <h4>Listado de roles</h4>

                <div class="form-group">
                    @foreach ($roles as $rol)
                        <div class="form-check">
                            <input class="form-check-input mr-1" name="roles[]" value="{{ $rol->id }}"
                                type="checkbox" @if (in_array($rol->name, $rolesName)) {{ 'checked'}} @endif>
                            <label class="form-check-label mr-1">{{ $rol->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card-footer">
                <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light"
                    value="Asignar rol" />
                <a href="{{ route('admin.assignment.all') }}" class="btn btn-default">Regresar</a>
            </div>

        </form>

    </div>

@stop

@section('jscode')
@endsection
