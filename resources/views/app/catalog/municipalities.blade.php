@extends('master')
@section('title', 'Gestión de Municipios')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded shadow-sm">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.catalog.states') }}"><i class="fas fa-map-marker-alt"></i> Gestión de Estados</a></li>
                        <li class="breadcrumb-item active"><i class="fas fa-city"></i> Gestión de Municipios</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Gestión de Municipios</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-city"></i> Lista de Municipios</h3>
            <div class="d-flex justify-content-end">
                <form action="{{ route('admin.catalog.municipalities') }}" method="GET" class="form-inline">
                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            <label for="search_estado" class="sr-only">Estado</label>
                            <input type="text" name="search_estado" id="search_estado" class="form-control mb-2" placeholder="Buscar por estado" value="{{ request()->get('search_estado') }}">
                        </div>
                        <div class="col-auto">
                            <label for="search_municipio" class="sr-only">Municipio</label>
                            <input type="text" name="search_municipio" id="search_municipio" class="form-control mb-2" placeholder="Buscar por municipio" value="{{ request()->get('search_municipio') }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-custom mb-2"><i class="fas fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-striped table-bordered mt-2">
                <thead>
                    <tr>
                        <th>EstadoPolId</th>
                        <th>Nombre del Estado</th>
                        <th>MunicipioPolId</th>
                        <th>Nombre del Municipio</th>
                        <th>Número de Polígono</th>
                        <th>Polígono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($municipalities as $municipiopol)
                        <tr>
                            <td>{{ $municipiopol->EstadoPolId }}</td>
                            <td>{{ $municipiopol->EstadoNombre }}</td>
                            <td>{{ $municipiopol->MunicipioPolId }}</td>
                            <td>{{ $municipiopol->MunicipioNombre }}</td>
                            <td>{{ $municipiopol->MunicipioPolNumero }}</td>
                            <td>{{ $municipiopol->MunicipioPolPoligono }}</td>
                            <td>
                                <a href="{{ route('admin.catalog.municipality.detail', [
                                    'estadoPolId' => encrypt($municipiopol->EstadoPolId),
                                    'municipioPolId' => encrypt($municipiopol->MunicipioPolId),
                                    'municipioPolNumero' => encrypt($municipiopol->MunicipioPolNumero),
                                ]) }}" class="btn btn-warning sm">
                                    <i class="fas fa-map"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Sin datos</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $municipalities->appends([
                    'search_estado' => request()->get('search_estado'),
                    'search_municipio' => request()->get('search_municipio')
                ])->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
@stop

@section('jscode')
@stop
