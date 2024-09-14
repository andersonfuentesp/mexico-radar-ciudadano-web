@extends('master')
@section('title', 'Editar rol y sus permisos')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.role.all') }}">Gestión de Roles</a></li>
                        <li class="breadcrumb-item active">Editar rol</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Editar Rol</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Edición de rol</h1>
        </div>

        <form class="form-horizontal" method="POST" action="{{ route('admin.role.update', $role->id) }}">

            @csrf

            <div class="card-body">

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Nombres</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="name" value="{{ $role->name }}" type="text"
                            id="example-text-input" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    @foreach ($permissions as $key => $item)
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" name="permissions[]" id="customCheckbox{{ $key+1 }}" value="{{ $item->id }}"
                                type="checkbox" @if (in_array($item->name, $permisionsName)) {{ 'checked'}} @endif>
                            <label for="customCheckbox{{ $key+1 }}" class="custom-control-label">{{ $item->description }}</label>
                        </div>
                    @endforeach
                </div>

            </div>

            <div class="card-footer">
                <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light"
                    value="Actualizar rol" />
                <a href="{{ route('admin.role.all') }}" class="btn btn-default">Regresar</a>
            </div>

        </form>

    </div>

@stop

@section('jscode')

@stop