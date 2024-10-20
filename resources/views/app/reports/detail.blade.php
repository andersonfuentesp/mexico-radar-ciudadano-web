@extends('master')
@section('title', 'Detalle del Reporte Ciudadano')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.reports.all') }}">Gestión de Reportes Ciudadanos</a></li>
                        <li class="breadcrumb-item active">Detalle del Reporte Ciudadano</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Detalle del Reporte Ciudadano</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-info-circle"></i> Detalle del Reporte Ciudadano</h3>
            <div>
                <a href="{{ route('admin.reports.all') }}" class="btn btn-secondary mr-2"><i class="fas fa-arrow-left"></i> Regresar</a>
                <button class="btn btn-custom" onclick="window.print()"><i class="fas fa-download"></i> Descargar Reporte</button>
            </div>
        </div>

        <div class="card-body">
            <!-- Nueva Sección de Datos Móviles -->
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

            <!-- Información General del Reporte -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header text-white" style="background-color: #b83e5a;">
                            <h5 class="card-title mb-0">Información General</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>ID del Reporte:</strong></h6>
                                        <p>{{ $report['report_id'] }}</p>
                                        <input type="hidden" id="reportId" value="{{ $report['report_id'] }}">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Folio:</strong></h6>
                                        <p>{{ $report['report_folio'] }}</p>
                                        <input type="hidden" id="reportFolio" value="{{ $report['report_folio'] }}">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Estado:</strong></h6>
                                        <p>{{ $report['EstadoNombre'] }}</p>
                                        <input type="hidden" id="estadoNombre" value="{{ $report['EstadoNombre'] }}">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Municipio:</strong></h6>
                                        <p>{{ $report['MunicipioNombre'] }}</p>
                                        <input type="hidden" id="municipioNombre" value="{{ $report['MunicipioNombre'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Colonia:</strong></h6>
                                        <p>{{ $report['neighborhood_name'] }}</p>
                                        <input type="hidden" id="colonia" value="{{ $report['neighborhood_name'] }}">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Código Postal:</strong></h6>
                                        <p>{{ $report['postal_code'] }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Dependencia:</strong></h6>
                                        <p>{{ $report['DependencyName'] }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Tipo de Reporte:</strong></h6>
                                        <p>{{ $report['ReportTypeName'] }}</p>
                                        <input type="hidden" id="reportTypeName" value="{{ $report['ReportTypeName'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Teléfono:</strong></h6>
                                        <p>{{ $report['phone'] ?? 'No proporcionado' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Correo Electrónico:</strong></h6>
                                        <p>{{ $report['email'] ?? 'No proporcionado' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Fecha de Registro:</strong></h6>
                                        <p>{{ \Carbon\Carbon::parse($report['created_at'])->format('d-m-Y H:i:s') }}</p>
                                        <input type="hidden" id="createdAt" value="{{ \Carbon\Carbon::parse($report['created_at'])->format('d-m-Y H:i:s') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Fecha Reportada:</strong></h6>
                                        <p>{{ \Carbon\Carbon::parse($report['report_reported_date'])->format('d-m-Y H:i:s') }}</p>
                                        <input type="hidden" id="reportedDate" value="{{ \Carbon\Carbon::parse($report['report_reported_date'])->format('d-m-Y H:i:s') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Ubicación GPS:</strong></h6>
                                        <p>{{ $report['gps_location'] }}</p>
                                        <input type="hidden" id="gpsLocation" value="{{ $report['gps_location'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Estado del Reporte:</strong></h6>
                                        <p class="lead">
                                            <span class="badge badge-primary">
                                                {{ $report['ReportStatusName'] }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Dirección:</strong></h6>
                                        <p>{!! $report['report_address'] !!}</p>
                                        <input type="hidden" id="reportAddress" value="{{ $report['report_address'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="p-2 border rounded h-100">
                                        <h6><strong>Comentario:</strong></h6>
                                        <p>{!! $report['report_comment'] !!}</p>
                                        <input type="hidden" id="reportComment" value="{{ $report['report_comment'] }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha para Respuesta -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-custom text-white">
                            <h5 class="card-title mb-0">Respuesta</h5>
                        </div>
                        <div class="card-body">
                            <div class="p-2 border rounded h-100">
                                <p>{!! $report['response_text'] !!}</p>
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
                                @if ($report['reported_photo'])
                                    <img src="{{ $report['reported_photo'] }}" alt="Foto del Reporte" class="img-fluid rounded" style="width: 300px; height: 300px; object-fit: cover;">
                                @else
                                    <p>No se ha subido una foto inicial para este reporte.</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-2 border rounded">
                                <h6><span class="badge badge-warning">Foto de Cierre</span></h6>
                                @if ($report['end_photo'])
                                    <img src="{{ $report['end_photo'] }}" alt="Foto de Cierre" class="img-fluid rounded" style="width: 300px; height: 300px; object-fit: cover;">
                                @else
                                    <p>No se ha subido una foto de cierre para este reporte.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mapa GPS -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">Ubicación en Mapa</h5>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 400px; width: 100%;"></div>
                </div>
            </div>

            <!-- Finalización del Reporte -->
            @if ($report['end_comment'])
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">Comentario Final</h5>
                    </div>
                    <div class="card-body">
                        <p>{!! $report['end_comment'] !!}</p>
                    </div>
                </div>
            @endif
        </div>

        <input type="hidden" id="locationIconPath" value="{{ asset('backend/assets/img/map.png') }}">
        <input type="hidden" id="mapsProxyRoute" value="{{ route('maps.proxy') }}">

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

@section('jscode')
    <script src="{{ asset('backend/assets/js/reportDetail.js') }}"></script>
@stop
