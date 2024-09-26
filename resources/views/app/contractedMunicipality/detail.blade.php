@extends('master')
@section('title', 'Detalle del Municipio Contratado')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.contractedMunicipality.all') }}">Gestión de Municipios Contratados</a></li>
                        <li class="breadcrumb-item active">Detalle del Municipio Contratado</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Detalle del Municipio Contratado</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-info-circle"></i> Detalle del Municipio Contratado</h3>
            <div>
                <a href="{{ route('admin.contractedMunicipality.all') }}" class="btn btn-secondary mr-2"><i class="fas fa-arrow-left"></i> Regresar</a>
                <button class="btn btn-custom" onclick="window.print()"><i class="fas fa-download"></i> Descargar Detalle</button>
            </div>
        </div>

        <div class="card-body">
            <!-- Información General -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>Nombre del Municipio:</strong></h6>
                        <p>{{ $municipality->name }}</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>Estado:</strong></h6>
                        <p>{{ $municipality->EstadoNombre }}</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>Municipio:</strong></h6>
                        <p>{{ $municipality->MunicipioNombre }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>Fecha de Contrato:</strong></h6>
                        <p>{{ \Carbon\Carbon::parse($municipality->contract_date)->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>Responsable de Contacto:</strong></h6>
                        <p>{{ $municipality->contact_responsible }}</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>Email de Contacto:</strong></h6>
                        <p>{{ $municipality->contact_email ?? 'No disponible' }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>Teléfono de Contacto:</strong></h6>
                        <p>{{ $municipality->contact_phone1 }}</p>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="p-2 border rounded h-100 text-center">
                        <h6><strong>URL del Municipio:</strong></h6>
                        @if ($municipality->url)
                            <a href="{{ $municipality->url }}" class="btn btn-primary" target="_blank">
                                <i class="fas fa-external-link-alt"></i> Visitar Web
                            </a>
                        @else
                            <p>No disponible</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sección para el token del municipio contratado -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>Token de Autenticación:</strong></h6>
                        <p>{{ $municipality->token ?? 'No disponible' }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="p-2 border rounded h-100">
                        <h6><strong>Descripción:</strong></h6>
                        <p>{{ $municipality->description ?? 'No disponible' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-start">
            <a href="{{ route('admin.contractedMunicipality.all') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
            <button class="btn btn-custom" onclick="window.print()">
                <i class="fas fa-download"></i> Descargar Detalle
            </button>
        </div>
    </div>
@stop

@section('jscode')
    <script>
        // Aquí puedes agregar cualquier script adicional necesario para el detalle
    </script>
@stop
