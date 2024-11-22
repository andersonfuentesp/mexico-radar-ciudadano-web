@extends('master')
@section('title', 'Registrar Usuario')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user.all') }}">Gestión de Usuarios</a></li>
                        <li class="breadcrumb-item active"><a>Registrar Usuario</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Gestión de Usuarios</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('csscode')
    <style>
        /* Indicar campos requeridos */
        .required-field:required:invalid {
            border: 2px solid red !important;
            border-radius: 0.375rem;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Registro de Nuevo Usuario</h1>
        </div>

        <form class="form-horizontal" id="quickForm" method="POST" action="{{ route('admin.user.store') }}"
            enctype="multipart/form-data">
            @csrf

            <div class="card-body">

                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Nombres <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input class="form-control required-field" name="name" value="{{ old('name') }}"
                            placeholder="Ingrese sus nombres" type="text" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="lastname" class="col-sm-2 col-form-label">Apellidos <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input class="form-control required-field" name="lastname" value="{{ old('lastname') }}"
                            placeholder="Ingrese sus apellidos" type="text" required>
                        @error('lastname')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="username" class="col-sm-2 col-form-label">Nombre de Usuario <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input class="form-control required-field" name="username" value=""
                            placeholder="Ingrese el nombre de usuario" type="text" autocomplete="off" required>
                        @error('username')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input class="form-control required-field" name="email" value="{{ old('email') }}"
                            placeholder="Ingrese el email" type="email" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-2">
                        <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-4">
                        <input type="password" class="form-control required-field" id="password" name="password"
                            placeholder="Ingrese la contraseña" autocomplete="new-password" required>
                    </div>
                    <div class="col-3">
                        <button class="form-control btn btn-primary" id="generate" type="button">
                            <i class="bi bi-shield-lock"></i>&nbsp;Generar contraseña</button>
                    </div>
                    <div class="col-3">
                        <button class="form-control btn btn-secondary" id="show-password" type="button">
                            <i class="bi bi-eye"></i>&nbsp;Mostrar contraseña</button>
                    </div>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <hr>

                <div class="form-group row">
                    <label for="rol_id" class="col-sm-2 col-form-label">Rol <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control required-field" id="rol_id" name="rol_id" required>
                            <option value="">Escoge una opción</option>
                            @foreach ($roles as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="image" class="col-sm-2 col-form-label">Imagen</label>
                    <div class="input-group col-sm-10">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="image" id="image">
                            <label class="custom-file-label" for="image">Choose file</label>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text">Upload</span>
                        </div>
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="preview" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <img id="showImage" class="rounded avatar-lg" src="{{ url('backend/upload/no_image.jpg') }}"
                            alt="Vista previa" style="width: 120px">
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-info btn-rounded waves-effect waves-light">
                    <i class="bi bi-floppy"></i>&nbsp;&nbsp;Registrar Usuario</button>
                <a href="{{ route('admin.user.all') }}" class="btn btn-default"><i class="bi bi-arrow-left-circle"></i>
                    Regresar</a>
            </div>

        </form>
    </div>
@stop

@section('jscode')
    <script type="text/javascript">
        function random_password() {
            var pass = '';
            var str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$';

            for (let i = 1; i <= 8; i++) {
                var char = Math.floor(Math.random() * str.length + 1);
                pass += str.charAt(char);
            }
            return pass;
        }

        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                };
                reader.readAsDataURL(e.target.files[0]);
            });

            $('#generate').click(function() {
                var pass = random_password();
                var x = Math.floor((Math.random() * 100) + 1);
                $('#password').val(pass + '#' + x + 'Tf');
            });

            $('#show-password').click(function() {
                if ('password' === $('#password').attr('type')) {
                    $('#password').prop('type', 'text');
                } else {
                    $('#password').prop('type', 'password');
                }
            });
        });
    </script>
@endsection
