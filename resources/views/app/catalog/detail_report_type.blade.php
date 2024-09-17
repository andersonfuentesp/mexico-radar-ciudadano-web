@extends('master')
@section('title', 'Detalle del Tipo de Reporte')

@section('content_header')
<div class="card container-fluid mb-0">
    <div class="row mb-1 mt-3">
        <div class="col-sm-12">
            <div class="p-3 mb-2 bg-light rounded">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.catalog.reports.type') }}"><i class="fas fa-file-alt"></i> Gestión de Tipos de Reporte</a></li>
                    <li class="breadcrumb-item active"><a><i class="fas fa-eye"></i> Detalle del Tipo de Reporte</a></li>
                </ol>
                <h1 class="m-0" style="font-size: 23px;"><b>Detalle del Tipo de Reporte</b></h1>
            </div>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-header bg-custom text-white">
        <h1 class="card-title"><i class="fas fa-eye"></i> Detalles del Tipo de Reporte</h1>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                @foreach ([
                    ['Nombre', 'ReportTypeName', 'fas fa-file-alt'],
                    ['Prioridad de Orden', 'ReportTypeOrderPriority', 'fas fa-sort-amount-up'],
                    ['Activo', 'ReportTypeIsActive', 'fas fa-toggle-on', 'Sí', 'No'],
                    ['Fecha de Registro', 'ReportTypeRegistrationDate', 'fas fa-calendar-alt', \Carbon\Carbon::parse($reportType->ReportTypeRegistrationDate)->format('d/m/Y')],
                ] as $field)
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label font-weight-bold"><i class="{{ $field[2] }} mr-2"></i> {{ $field[0] }}:</label>
                    <div class="col-sm-8">
                        <div class="border p-2 rounded">{{ $field[1] === 'ReportTypeIsActive' ? ($reportType->ReportTypeIsActive ? $field[3] : $field[4]) : $field[3] ?? $reportType->{$field[1]} }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-md-6">
                @foreach ([
                    ['Pin Reportado', 'ReportTypePinReported', 'fas fa-map-pin'],
                    ['Pin en Proceso', 'ReportTypePinInProcess', 'fas fa-spinner'],
                    ['Pin Terminado', 'ReportTypePinFinished', 'fas fa-check-circle'],
                    ['Imagen', 'ReportTypeImage', 'fas fa-image'],
                ] as $field)
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label font-weight-bold"><i class="{{ $field[2] }} mr-2"></i> {{ $field[0] }}:</label>
                    <div class="col-sm-8">
                        @if($reportType->{$field[1]})
                        <img src="{{ $reportType->{$field[1]} }}" alt="{{ $field[0] }}" class="img-fluid mt-2 border rounded" style="max-width: 100px; height: auto;">
                        @else
                        <div class="form-control-plaintext">N/A</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card-footer text-right">
        <a href="{{ route('admin.catalog.reports.type') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Regresar</a>
    </div>
</div>
@stop
