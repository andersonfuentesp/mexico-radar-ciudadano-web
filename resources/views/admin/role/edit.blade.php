@extends('master')
@section('title', 'Editar rol y sus permisos')

@section('content_header')
    <h1>Editar rol</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Edición de rol</h1>
        </div>

        <form class="form-horizontal" method="POST" action="{{ route('role.update', $role->id) }}">

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
                <a href="{{ route('role.all') }}" class="btn btn-default">Regresar</a>
            </div>

        </form>

    </div>

@stop

@section('jscode')

@stop