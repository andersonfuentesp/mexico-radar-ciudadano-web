@extends('master')
@section('title', 'Cambiar contraseña')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active"><a><i class="fas fa-key"></i> Cambiar Contraseña</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Cambiar contraseña</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title"><i class="fas fa-key"></i> Actualizar clave de seguridad</h1>
        </div>

        <form class="form-horizontal" id="myForm" method="POST" action="{{ route('admin.password.update') }}">
            @csrf
            <div class="card-body">
                @if (count($errors))
                    @foreach ($errors->all() as $error)
                        <p class="alert alert-danger alert-dismissible fade show">{{ $error }}</p>
                    @endforeach
                @endif

                <div class="form-group row">
                    <label for="old-password-input" class="col-sm-2 col-form-label">
                        <i class="fas fa-lock"></i> Contraseña antigua <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-10">
                        <input class="form-control" name="oldpassword" type="password" id="old-password-input"
                            placeholder="Introduce tu contraseña actual" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new-password-input" class="col-sm-2 col-form-label">
                        <i class="fas fa-lock"></i> Nueva Contraseña <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-10">
                        <input class="form-control" name="newpassword" type="password" id="new-password-input"
                            placeholder="Introduce la nueva contraseña" required>
                        <small class="form-text text-muted">
                            La contraseña debe tener al menos 6 caracteres, incluyendo una mayúscula, una minúscula, un número y un carácter especial.
                        </small>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="confirm-password-input" class="col-sm-2 col-form-label">
                        <i class="fas fa-lock"></i> Confirmar Contraseña <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-10">
                        <input class="form-control" name="confirm_password" type="password" id="confirm-password-input"
                            placeholder="Confirma la nueva contraseña" required>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-info btn-rounded waves-effect waves-light">
                    <i class="fas fa-sync-alt"></i> Actualizar datos de seguridad
                </button>
                <a href="{{ route('admin.index') }}" class="btn btn-default">
                    <i class="fas fa-arrow-left"></i> Regresar
                </a>
            </div>
        </form>
    </div>
@stop
