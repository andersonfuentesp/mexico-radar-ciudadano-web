@extends('master')
@section('title', 'Colonias Contratadas')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active"><a><i class="fas fa-city"></i> Colonias Contratadas</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Gestión de Colonias Contratadas</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Estado</th>
                        <th>Municipio</th>
                        <th>Nombre de la Colonia</th>
                        <th>Código Postal</th>
                        <th>Fecha de Creación</th>
                        <th>URL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($neighborhoods as $index => $neighborhood)
                        <tr>
                            <td>{{ ($currentPage - 1) * $perPage + $index + 1 }}</td>
                            <td>{{ $neighborhood['EstadoNombre'] }}</td>
                            <td>{{ $neighborhood['MunicipioNombre'] }}</td>
                            <td>{{ $neighborhood['neighborhood_name'] }}</td>
                            <td>{{ $neighborhood['postal_code'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($neighborhood['created_at'])->format('d/m/Y H:i:s') }}</td>
                            <td>
                                @if($neighborhood['municipality_url'])
                                    <a href="{{ $neighborhood['municipality_url'] }}" target="_blank" class="btn btn-custom"
                                       title="Ir a la Aplicación/Web">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @else
                                    <span class="text-muted">No disponible</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginación -->
            @php
                $totalPages = ceil($total / $perPage);
            @endphp

            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ route('admin.catalog.neighborhoods', ['page' => $currentPage - 1]) }}" tabindex="-1">Anterior</a>
                    </li>
                    @for ($i = 1; $i <= $totalPages; $i++)
                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ route('admin.catalog.neighborhoods', ['page' => $i]) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ route('admin.catalog.neighborhoods', ['page' => $currentPage + 1]) }}">Siguiente</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
@stop
