@extends('master')
@section('title', 'Registrar usuario')

@section('content_header')
    <h1>Crear usuario</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Registro de nuevo usuario</h1>
        </div>

        <form class="form-horizontal" id="quickForm" method="POST" action="{{ route('user.store') }}"
            enctype="multipart/form-data">

            @csrf

            <div class="card-body">

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Nombres</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="name" value="{{ old('name') }}"
                            placeholder="Ingrese tus nombres" type="text" id="example-text-input">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Apellidos</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="lastname" value="{{ old('lastname') }}"
                            placeholder="Ingrese tus apellidos" type="text" id="example-text-input">
                        @error('lastname')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="username" value="{{ old('username') }}"
                            placeholder="Ingresa el username" type="text" id="example-text-input">
                        @error('username')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="email" value="{{ old('email') }}"
                            placeholder="Ingresa el email" type="email" id="example-text-input">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-2">
                        <label for="example-text-input" class="form-label">Password</label>
                    </div>
                    <div class="col-4">
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Ingrese la contraseña">
                    </div>
                    <div class="col-3">
                        <button class="form-control btn btn-primary" id="generate" type="button">Generar
                            contraseña</button>
                    </div>
                    <div class="col-3">
                        <button class="form-control btn btn-secondary" id="show-password" type="button">
                            <i class="bi bi-eye"></i> Mostrar contraseña</button>
                    </div>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <hr>

                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Rol</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="rol_id" name="rol_id">
                            <option value="">Escoge una opción</option>
                            @foreach ($roles as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Imagen</label>
                    <div class="input-group col-sm-10">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="image" id="image">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
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
                    <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <img id="showImage" class="rounded avatar-lg" src="{{ url('backend/upload/no_image.jpg') }}"
                            alt="Card image cap" style="width: 120px">
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-info btn-rounded waves-effect waves-light">
                    <i class="bi bi-floppy"></i>&nbsp;&nbsp;Registrar usuario</button>
                <a href="{{ route('user.all') }}" class="btn btn-default"><i class="bi bi-arrow-left-circle"></i> Regresar</a>
            </div>

        </form>

    </div>

@stop

@section('jscode')

    <script type="text/javascript">
        function random_password() {
            var pass = '';
            var str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' +
                'abcdefghijklmnopqrstuvwxyz0123456789@#$';

            for (let i = 1; i <= 8; i++) {
                var char = Math.floor(Math.random() *
                    str.length + 1);

                pass += str.charAt(char)
            }
            return pass;
        }

        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });

        $("#rol_id").change(function() {
            if (this.value == "2") {

            }
        });

        $("#generate").click(function() {

            var pass = random_password();
            var x = Math.floor((Math.random() * 100) + 1);
            $("#password").val(pass + "#" + x + "Tf");
        });

        $("#show-password").click(function() {
            if ('password' == $('#password').attr('type')) {
                $('#password').prop('type', 'text');
            } else {
                $('#password').prop('type', 'password');
            }
        });
    </script>

    <script type="text/javascript">
        $(function() {
            $('#quickForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    lastname: {
                        required: true,
                    },
                    username: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    password: {
                        required: true,
                    },
                    rol_id: {
                        required: true,
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.test').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>

@stop
