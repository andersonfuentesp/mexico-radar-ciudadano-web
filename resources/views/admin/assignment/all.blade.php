@extends('master')
@section('title', 'Todos los usuarios')

@section('content_header')
    <h1>Gestión de asignaciones</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <a href="{{ route('user.all') }}" class="btn btn-info btn-rounded 
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
                                @can('assignment.edit')
                                <a href="{{ route('assignment.edit', $item->id) }}" class="btn btn-success sm" title="Edit data">
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
