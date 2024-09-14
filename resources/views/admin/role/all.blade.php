@extends('master')
@section('title', 'Todos los roles')

@section('content_header')
    <h1>Lista de roles</h1>
@stop

@section('csscode')
    <link href="{{ asset('backend/assets/css/datatable.css') }}" type="text/css" rel="stylesheet" />
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            @can('role.add')
                <a href="{{ route('role.add') }}" class="btn btn-info btn-rounded 
                waves-effect waves-light">Registrar
                    nuevo rol</a>
            @endcan
        </div>

        <div class="card-body">
            <table id="datatable-2" class="table table-striped table-bordered"
                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Rol</th>
                        <th>Cantidad de permisos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->permissions_count }}</td>
                            <td>
                                @can('role.edit')
                                    <a href="{{ route('role.edit', $item->id) }}" class="btn btn-info sm" title="Edit data">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('role.delete')
                                    <a href="{{ route('role.delete', $item->id) }}" id="delete" class="btn btn-danger sm"
                                        title="Delete data">
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
