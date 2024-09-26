@extends('master')
@section('title', 'Detalle del Servicio')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.contractedMunicipality.all') }}">Gestión de Municipios Contratados</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.contractedMunicipality.services', encrypt($municipality->id)) }}">Servicios</a></li>
                        <li class="breadcrumb-item active">Detalle del Servicio</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Detalle del Servicio</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-info-circle"></i> Detalle del Servicio</h3>
            <div>
                <a href="{{ route('admin.contractedMunicipality.services', encrypt($municipality->id)) }}" class="btn btn-secondary mr-2"><i class="fas fa-arrow-left"></i> Regresar</a>
                <button class="btn btn-custom" onclick="window.print()"><i class="fas fa-download"></i> Descargar Detalle</button>
            </div>
        </div>

        <div class="card-body">
            <!-- Información General -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>Nombre del Servicio:</strong></h6>
                        <p>{{ $service->service_name }}</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>API URL:</strong></h6>
                        <!-- Aquí concatenamos la URL del municipio con la URL del servicio -->
                        <p>{{ rtrim($municipality->url, '/') . '/' . ltrim($service->api_url, '/') }}</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>Formato de Respuesta:</strong></h6>
                        <p>{{ $service->response_format }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>Estado del Servicio:</strong></h6>
                        <p>
                            @if ($service->status)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>Token de API:</strong></h6>
                        <p>{{ $service->api_token ?? 'No disponible' }}</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>Descripción:</strong></h6>
                        <p>{{ $service->description ?? 'No disponible' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-start">
            <a href="{{ route('admin.contractedMunicipality.services', encrypt($municipality->id)) }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
            <button class="btn btn-custom" onclick="window.print()">
                <i class="fas fa-download"></i> Descargar Detalle
            </button>
        </div>
    </div>
@stop
