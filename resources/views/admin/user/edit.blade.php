@extends('master')
@section('title', 'Editar usuario')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user.all') }}">Gestión de Usuarios</a></li>
                        <li class="breadcrumb-item active">Editar usuario</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Editar Usuario</b></h1>
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

        <form class="form-horizontal" method="POST" action="{{ route('admin.user.update', $data->id ) }}"
            enctype="multipart/form-data">

            @csrf

            <div class="card-body">

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Nombres</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="name" value="{{ $data->name }}" type="text"
                            id="example-text-input">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Apellidos</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="lastname" value="{{ $data->lastname }}" type="text"
                            id="example-text-input">
                        @error('lastname')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="username" value="{{ $data->username }}" type="text"
                            id="example-text-input" readonly>
                        @error('username')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="email" value="{{ $data->email }}" type="email"
                            id="example-text-input">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Nueva contraseña</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="password" placeholder="Ingresa una nueva contraseña" type="password"
                            id="example-text-input">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
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
                        <img id="showImage" class="rounded avatar-lg"
                            src="{{ !empty($data->image) ? url($data->image) : url('backend/upload/no_image.jpg') }}"
                            alt="Card image cap" style="width: 120px">
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-info btn-rounded waves-effect waves-light">
                    <i class="bi bi-pencil-square"></i>&nbsp;&nbsp;Actualizar datos de perfil</button>
                <a href="{{ route('admin.user.all') }}" class="btn btn-default"><i class="bi bi-arrow-left-circle"></i> Regresar</a>
            </div>

        </form>

    </div>

@stop

@section('jscode')
    <script>
        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files[0]);
            });
        });
    </script>
@endsection
