@extends('master')
@section('title', 'Todos los usuarios')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user.all') }}">Gestión de Usuarios</a></li>
                        <li class="breadcrumb-item active">Gestión de asignaciones</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Gestión de Asignaciones</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.user.all') }}" class="btn btn-info btn-rounded 
            waves-effect waves-light">Visualizar usuarios</a>
        </div>

        <div class="card-body">
            <table id="datatable-2" class="table table-striped table-bordered"
                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>Cantidad de roles</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->lastname }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ count($item->roles) }}</td>
                            <td>
                                @can('admin.assignment.edit')
                                <a href="{{ route('admin.assignment.edit', $item->id) }}" class="btn btn-success sm" title="Edit data">
                                    <i class="fas fa-plus"></i>
                                    Asignar rol
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
