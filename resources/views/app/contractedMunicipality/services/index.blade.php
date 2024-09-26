@extends('master')
@section('title', 'Servicios del Municipio Contratado')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.contractedMunicipality.all') }}"><i
                                    class="fas fa-city"></i> Municipios Contratados</a></li>
                        <li class="breadcrumb-item active"><a><i class="fas fa-tools"></i> Servicios</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;">
                        <b>Servicios del Municipio: {{ $municipality->MunicipioNombre }}, Estado:
                            {{ $municipality->EstadoNombre }}</b>
                    </h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('admin.contractedMunicipality.services.add')
                <a href="{{ route('admin.contractedMunicipality.services.add', encrypt($municipality->id)) }}"
                    class="btn btn-custom btn-rounded waves-effect waves-light">
                    <i class="fas fa-plus"></i> Agregar Nuevo Servicio
                </a>
            @endcan
        </div>

        <div class="card-body">
            <table id="datatable" class="table table-striped table-bordered"
                style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre del Servicio</th>
                        <th>API URL</th>
                        <th>Método HTTP</th>
                        <th>Formato de Respuesta</th>
                        <th>Estado</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $key => $service)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $service->service_name }}</td>
                            <td>{{ rtrim($municipality->url, '/') . '/' . ltrim($service->api_url, '/') }}</td>
                            <td>{{ $service->method }}</td>
                            <td>{{ $service->response_format }}</td>
                            <td>
                                @if ($service->status)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($service->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                @can('admin.contractedMunicipality.services.detail')
                                    <a href="{{ route('admin.contractedMunicipality.services.detail', encrypt($service->id)) }}"
                                        class="btn btn-info" title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan

                                @can('admin.contractedMunicipality.services.edit')
                                    <a href="{{ route('admin.contractedMunicipality.services.edit', ['municipalityId' => encrypt($municipality->id), 'serviceId' => encrypt($service->id)]) }}"
                                        class="btn btn-primary" title="Editar Servicio">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan

                                @can('admin.contractedMunicipality.services.delete')
                                    <form
                                        action="{{ route('admin.contractedMunicipality.services.delete', ['municipalityId' => encrypt($municipality->id), 'serviceId' => encrypt($service->id)]) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger delete-button" title="Eliminar Servicio">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan

                                <!-- Botón para probar la conectividad del servicio -->
                                <button class="btn btn-warning test-connection-button"
                                    data-url="{{ rtrim($municipality->url, '/') . '/' . ltrim($service->api_url, '/') }}"
                                    data-method="{{ $service->method }}" data-token="{{ $municipality->token }}"
                                    title="Probar Conectividad">
                                    <i class="fas fa-plug"></i>
                                </button>

                                <!-- Botón para descargar el log de la conexión -->
                                <a class="btn btn-secondary download-log-button" style="display:none;"
                                    id="download-log-{{ $key }}" title="Descargar Log de Conexión">
                                    <i class="fas fa-download"></i> Log
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Incluir el modal desde otro Blade -->
    @include('partials.service-modal')
@stop

@section('jscode')
    <script src="{{ asset('backend/assets/js/services.js') }}"></script>
@stop
