@extends('master')
@section('title', 'Gestión de Estados')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active"><a>Gestión de Estados</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Gestión de Estados</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-map"></i> Lista de Estados</h3>
            <form action="{{ route('admin.catalog.states') }}" method="GET" class="form-inline float-right">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Buscar estados" value="{{ request()->get('search') }}">
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-custom btn-flat"><i class="fas fa-search"></i></button>
                    </span>
                </div>
            </form>
        </div>

        <div class="card-body">
            <table class="table table-striped table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Estado</th>
                        <th>Número de Polígono</th>
                        <th>Polígono</th>
                        <th>Cantidad de Municipios</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($states as $estadopol)
                        <tr>
                            <td>{{ $estadopol->EstadoPolId }}</td>
                            <td>{{ $estadopol->estado->EstadoNombre }}</td>
                            <td>{{ $estadopol->EstadoPolNumero }}</td>
                            <td>{{ $estadopol->EstadoPolPoligono }}</td>
                            <td>{{ $estadopol->estado->municipios->count() }}</td>
                            <td>
                                <a href="{{ route('admin.catalog.state.detail', ['estadoPolId' => encrypt($estadopol->EstadoPolId), 'estadoPolNumero' => encrypt($estadopol->EstadoPolNumero)]) }}" class="btn btn-warning sm" title="Detalles">
                                    <i class="fas fa-map"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Sin datos</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $states->appends(['search' => request()->get('search')])->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
@stop

@section('jscode')
@stop
