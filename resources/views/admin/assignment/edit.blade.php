@extends('master')
@section('title', 'Editar asignación')

@section('content_header')
    <h1>Editar asignación de rol</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Datos del usuario</h1>
        </div>

        <form class="form-horizontal" method="POST" action="{{ route('assignment.update', $data->id) }}">

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
                <a href="{{ route('assignment.all') }}" class="btn btn-default">Regresar</a>
            </div>

        </form>

    </div>

@stop

@section('jscode')
@endsection
