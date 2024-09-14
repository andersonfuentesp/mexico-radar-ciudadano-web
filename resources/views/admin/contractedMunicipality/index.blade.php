@extends('master')
@section('title', 'Municipios Contratados')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active"><a><i class="fas fa-city"></i> Municipios Contratados</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Gestión de Municipios Contratados</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.contractedMunicipality.add') }}"
                class="btn btn-info btn-rounded waves-effect waves-light">
                <i class="fas fa-plus"></i> Registrar Nuevo Municipio Contratado
            </a>
        </div>

        <div class="card-body">
            <table id="datatable" class="table table-striped table-bordered"
                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Municipio</th>
                        <th>Fecha de Contrato</th>
                        <th>Estado del Contrato</th>
                        <th>Aplicación/Web</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($municipalities as $key => $municipality)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $municipality->name }}</td>
                            <td>{{ $municipality->EstadoNombre }}</td>
                            <td>{{ $municipality->MunicipioNombre }}</td>
                            <td>{{ \Carbon\Carbon::parse($municipality->contract_date)->format('d/m/Y') }}</td>

                            <!-- Badge para el estado del contrato -->
                            <td>
                                @if ($municipality->status)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>

                            <!-- Columna para el enlace a la aplicación/web -->
                            <td class="text-center">
                                @if ($municipality->url)
                                    <a href="{{ $municipality->url }}" target="_blank" class="btn btn-custom"
                                        title="Ir a la Aplicación/Web">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @else
                                    <span class="text-muted">No disponible</span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td>
                                <!-- Botón de detalle -->
                                @can('admin.contractedMunicipality.detail')
                                    <a href="{{ route('admin.contractedMunicipality.detail', encrypt($municipality->id)) }}"
                                        class="btn btn-info" title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan

                                <!-- Botón de editar -->
                                @can('admin.contractedMunicipality.edit')
                                    <a href="{{ route('admin.contractedMunicipality.edit', encrypt($municipality->id)) }}"
                                        class="btn btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan

                                <!-- Botón de eliminar -->
                                @can('admin.contractedMunicipality.delete')
                                    <form
                                        action="{{ route('admin.contractedMunicipality.delete', encrypt($municipality->id)) }}"
                                        method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger sm delete-button" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
