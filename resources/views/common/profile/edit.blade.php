@extends('master')
@section('title', 'Editar perfil')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active"><i class="fas fa-user"></i> Perfil del usuario</li>
                        <li class="breadcrumb-item active"><i class="fas fa-user-edit"></i> Editar el perfil</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Editar el perfil</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title"><i class="fas fa-user-edit"></i> Datos generales</h1>
        </div>

        <form class="form-horizontal" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">

                <!-- Nombres -->
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label"><i class="fas fa-user"></i> Nombres <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input class="form-control" name="name" value="{{ $userData->name }}" type="text" id="name" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Apellidos -->
                <div class="form-group row">
                    <label for="lastname" class="col-sm-2 col-form-label"><i class="fas fa-user"></i> Apellidos <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input class="form-control" name="lastname" value="{{ $userData->lastname }}" type="text" id="lastname" required>
                        @error('lastname')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Username (solo lectura) -->
                <div class="form-group row">
                    <label for="username" class="col-sm-2 col-form-label"><i class="fas fa-user-tag"></i> Username <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input class="form-control" name="username" value="{{ $userData->username }}" type="text" id="username" readonly>
                        @error('username')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label"><i class="fas fa-envelope"></i> Email <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input class="form-control" name="email" value="{{ $userData->email }}" type="email" id="email" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Teléfono -->
                <div class="form-group row">
                    <label for="phone" class="col-sm-2 col-form-label"><i class="fas fa-phone"></i> Teléfono</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="phone" value="{{ $userData->phone ?? '' }}" type="tel" id="phone">
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Imagen -->
                <div class="form-group row">
                    <label for="image" class="col-sm-2 col-form-label"><i class="fas fa-image"></i> Imagen</label>
                    <div class="input-group col-sm-10">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="image" id="image">
                            <label class="custom-file-label" for="image">Elegir archivo</label>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text">Subir</span>
                        </div>
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Previsualización de imagen -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <img id="showImage" class="rounded avatar-lg"
                             src="{{ !empty($userData->image) ? url($userData->image) : url('backend/upload/no_image.jpg') }}"
                             alt="Imagen de perfil" style="width: 120px">
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light" value="Actualizar datos de perfil">
                <a href="{{ route('admin.profile') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Regresar</a>
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
