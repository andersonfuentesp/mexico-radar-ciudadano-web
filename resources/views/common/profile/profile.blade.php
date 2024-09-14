@extends('master')
@section('title', 'Perfil del usuario')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Perfil del usuario</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Perfil del usuario</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                            src="{{ !empty($userData->image) ? url($userData->image) : asset('backend/upload/no_image.jpg') }}"
                            alt="User profile picture"
                            style="width: 150px; height: 150px; object-fit: cover; margin-bottom: 10px">
                    </div>

                    <h3 class="profile-username text-center">{{ $userData->name }} {{ $userData->lastname }}</h3>

                    <p class="text-muted text-center">{{ $rolesName }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $userData->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Usuario</b> <a class="float-right">{{ $userData->username }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Teléfono</b> <a class="float-right">{{ $userData->phone }}</a> <!-- Línea añadida -->
                        </li>
                    </ul>

                    <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary btn-block"><b>Editar Perfil</b></a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-8">
        </div>
    </div>
@stop
