@extends('master')
@section('title', 'Gestión de Usuarios')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active"><a>Gestión de Usuarios</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Gestión de Usuarios</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-people-fill"></i> Lista de Usuarios</h3>
            <a href="{{ route('admin.user.add') }}" class="btn btn-info btn-rounded waves-effect waves-light float-right">
                <i class="bi bi-person-add"></i> Registrar Usuario
            </a>
        </div>

        <div class="card-body">
            <table id="datatable" class="table table-striped table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Imagen</th>
                        <th>Nombres</th>
                        <th>Username</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Fecha de registro</th>
                        <th>Última conexión</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <img src="{{ !empty($item->image)? asset($item->image): 'https://picsum.photos/300/300' }}" style="width: 60px;">
                            </td>
                            <td>{{ $item->name." ". $item->lastname }}</td>
                            <td>{{ $item->username }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item['roles']->pluck('name')->implode(',') }}</td>
                            <td>{{ date('d-m-Y', strtotime($item->date_register)) }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->last_connection)->diffForHumans() }}</td>
                            <td>
                                @can('admin.user.edit')
                                    <a href="{{ route('admin.user.edit', $item->id) }}" class="btn btn-info sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan

                                @can('admin.user.delete')
                                    <a href="{{ route('admin.user.delete', $item->id) }}" id="delete" class="btn btn-danger sm" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('jscode')
@stop
