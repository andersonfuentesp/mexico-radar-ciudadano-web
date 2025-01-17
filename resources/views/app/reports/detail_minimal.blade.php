@extends('master')
@section('title', 'Detalle del Reporte - Información Limitada')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.reports.all') }}">Gestión de Reportes
                                Ciudadanos</a></li>
                        <li class="breadcrumb-item active">Detalle del Reporte</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Detalle del Reporte - Información Limitada</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-info-circle"></i> Detalle del Reporte</h3>
            <div>
                <a href="{{ route('admin.reports.all') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-arrow-left"></i> Regresar
                </a>
                <button class="btn btn-custom" onclick="window.print()">
                    <i class="fas fa-download"></i> Descargar Reporte
                </button>
            </div>
        </div>

        <div class="card-body">
            @if ($mobileReport)
                <!-- Información del Reporte Móvil -->
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">Información del Reporte Móvil</h5>
                    </div>
                    <div class="card-body">
                        @if ($mobileReport)
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>ID de Usuario de Atención:</strong></h6>
                                        <p>{{ $mobileReport->attention_user_id ?? 'No disponible' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>Nick de Usuario de Atención:</strong></h6>
                                        <p>{{ $mobileReport->attention_user_nick ?? 'No disponible' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>ID GAM de Usuario de Atención:</strong></h6>
                                        <p>{{ $mobileReport->attention_user_id_gam ?? 'No disponible' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>Reporte Bloqueado:</strong></h6>
                                        <p>{{ $mobileReport->blocked_report ?? 'No disponible' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>Fecha de Bloqueo:</strong></h6>
                                        <p>{{ $mobileReport->block_date ? \Carbon\Carbon::parse($mobileReport->block_date)->format('d-m-Y H:i:s') : 'No disponible' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>Usuario que Bloqueó:</strong></h6>
                                        <p>{{ $mobileReport->block_user ?? 'No disponible' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>Correo Electrónico:</strong></h6>
                                        <p>{{ $mobileReport->email ?? 'No disponible' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>Teléfono:</strong></h6>
                                        <p>{{ $mobileReport->phone ?? 'No disponible' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>Estado SD:</strong></h6>
                                        <p>{{ $mobileReport->status_sd ?? 'No disponible' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>Estado Lista SD:</strong></h6>
                                        <p>{{ $mobileReport->status_list_sd ?? 'No disponible' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>Modelo del Dispositivo:</strong></h6>
                                        <p>{{ $mobileReport->mobile_model ?? 'No disponible' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>Versión del SO:</strong></h6>
                                        <p>{{ $mobileReport->os_version ?? 'No disponible' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>Versión de la App:</strong></h6>
                                        <p>{{ $mobileReport->app_version ?? 'No disponible' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>Red al Enviar:</strong></h6>
                                        <p>{{ $mobileReport->network_type ?? 'No disponible' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded">
                                        <h6><strong>IMEI:</strong></h6>
                                        <p>{{ $mobileReport->imei ?? 'No disponible' }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                Sin datos disponibles para móvil.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Información General -->
                <div class="card mb-4">
                    <div class="card-header bg-custom text-white">
                        <h5 class="card-title mb-0">Información General</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="p-2 border rounded">
                                    <h6><strong>ID del Reporte:</strong></h6>
                                    <p>{{ $mobileReport->report_id ?? 'No disponible' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-2 border rounded">
                                    <h6><strong>Folio:</strong></h6>
                                    <p>{{ $mobileReport->report_folio ?? 'No disponible' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-2 border rounded">
                                    <h6><strong>Fecha de Registro:</strong></h6>
                                    <p>{{ $mobileReport->report_registration_time ? \Carbon\Carbon::parse($mobileReport->report_registration_time)->format('d-m-Y H:i:s') : 'No disponible' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="p-2 border rounded">
                                    <h6><strong>Dirección:</strong></h6>
                                    <p>{!! $mobileReport->report_address ?? 'No disponible' !!}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-2 border rounded">
                                    <h6><strong>Comentario:</strong></h6>
                                    <p>{{ $mobileReport->report_comment ?? 'No disponible' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-2 border rounded">
                                    <h6><strong>Estado del Reporte:</strong></h6>
                                    <p>{{ $mobileReport->report_status_id ?? 'No disponible' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="card mb-4">
                    <div class="card-header bg-custom text-white">
                        <h5 class="card-title mb-0">Información Adicional</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="p-2 border rounded">
                                    <h6><strong>Dispositivo ID:</strong></h6>
                                    <p>{{ $mobileReport->device_id ?? 'No disponible' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-2 border rounded">
                                    <h6><strong>Nick de Atención:</strong></h6>
                                    <p>{{ $mobileReport->attention_user_nick ?? 'No disponible' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-2 border rounded">
                                    <h6><strong>Fecha de Bloqueo:</strong></h6>
                                    <p>{{ $mobileReport->block_date ? \Carbon\Carbon::parse($mobileReport->block_date)->format('d-m-Y H:i:s') : 'No disponible' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="p-2 border rounded">
                                    <h6><strong>Teléfono:</strong></h6>
                                    <p>{{ $mobileReport->phone ?? 'No disponible' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-2 border rounded">
                                    <h6><strong>Correo Electrónico:</strong></h6>
                                    <p>{{ $mobileReport->email ?? 'No disponible' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-2 border rounded">
                                    <h6><strong>Estado SD:</strong></h6>
                                    <p>{{ $mobileReport->status_sd ?? 'No disponible' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fotos del Reporte -->
                <div class="card mb-4">
                    <div class="card-header bg-custom text-white">
                        <h5 class="card-title mb-0">Fotos del Reporte</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="p-2 border rounded">
                                    <h6><span class="badge badge-info">Foto Inicial</span></h6>
                                    @if ($mobileReport->reported_photo)
                                        <img src="{{ $mobileReport->reported_photo }}" alt="Foto Inicial"
                                            class="img-fluid rounded"
                                            style="width: 200px; height: 200px; object-fit: cover;">
                                    @else
                                        <p>No hay foto inicial disponible.</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-2 border rounded">
                                    <h6><span class="badge badge-warning">Foto de Cierre</span></h6>
                                    @if ($mobileReport->end_photo)
                                        <img src="{{ $mobileReport->end_photo }}" alt="Foto de Cierre"
                                            class="img-fluid rounded"
                                            style="width: 200px; height: 200px; object-fit: cover;">
                                    @else
                                        <p>No hay foto de cierre disponible.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mapa de Ubicación -->
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">Mapa de Ubicación</h5>
                    </div>
                    <div class="card-body">
                        @if ($mobileReport->gps_location)
                            @php
                                [$latitude, $longitude] = explode(',', $mobileReport->gps_location);
                            @endphp
                            <iframe width="100%" height="400" style="border:0;" loading="lazy" allowfullscreen
                                src="https://maps.google.com/maps?q={{ trim($latitude) }},{{ trim($longitude) }}&hl=es;z=14&output=embed">
                            </iframe>
                        @else
                            <div class="alert alert-warning">No hay coordenadas GPS disponibles.</div>
                        @endif
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    No hay datos disponibles para este reporte.
                </div>
            @endif
        </div>

        <div class="card-footer d-flex justify-content-start">
            <a href="{{ route('admin.reports.all') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
            <button class="btn btn-custom" onclick="window.print()">
                <i class="fas fa-download"></i> Descargar Reporte
            </button>
        </div>
    </div>
@stop
