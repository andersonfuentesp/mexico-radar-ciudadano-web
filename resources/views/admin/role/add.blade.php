@extends('master')
@section('title', 'Registrar rol')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.role.all') }}">Gestión de Roles</a></li>
                        <li class="breadcrumb-item active">Registrar rol</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Registrar Rol</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Registro de nuevo rol</h1>
        </div>

        <form class="form-horizontal" method="POST" action="{{ route('admin.role.store') }}">

            @csrf

            <div class="card-body">

                <!-- Botón para seleccionar todos los checkboxes -->
                <button type="button" id="select-all" class="btn btn-primary mb-3">Seleccionar Todo</button>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Nombres</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="name" type="text" id="example-text-input" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    @foreach ($permissions as $key => $item)
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" name="permissions[]" id="customCheckbox{{ $key + 1 }}"
                                value="{{ $item->id }}" type="checkbox">
                            <label for="customCheckbox{{ $key + 1 }}"
                                class="custom-control-label">{{ $item->description }}</label>
                        </div>
                    @endforeach
                </div>

            </div>

            <div class="card-footer">
                <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light" value="Registrar rol" />
                <a href="{{ route('admin.role.all') }}" class="btn btn-default">Regresar</a>
            </div>

        </form>

    </div>

@stop

@section('jscode')
    <script>
        document.getElementById('select-all').addEventListener('click', function() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            for (var checkbox of checkboxes) {
                checkbox.checked = true;
            }
        });
    </script>
@stop
