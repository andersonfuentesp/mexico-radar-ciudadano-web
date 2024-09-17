@extends('master')
@section('title', 'Editar Tipo de Reporte')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.catalog.reports.type') }}"><i class="fas fa-file-alt"></i> Gestión de Tipos de Reporte</a></li>
                        <li class="breadcrumb-item active"><a><i class="fas fa-edit"></i> Editar Tipo de Reporte</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Editar Tipo de Reporte</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title"><i class="fas fa-edit"></i> Datos del Tipo de Reporte</h1>
        </div>

        <form class="form-horizontal" method="POST" action="{{ route('admin.catalog.reports.type.update', $reportType->ReportTypeId) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="form-group row">
                    <label for="ReportTypeName" class="col-sm-2 col-form-label"><i class="fas fa-file-alt"></i> Nombre del Tipo de Reporte</label>
                    <div class="col-sm-10">
                        <input type="text" name="ReportTypeName" class="form-control" value="{{ $reportType->ReportTypeName }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ReportTypeOrderPriority" class="col-sm-2 col-form-label"><i class="fas fa-sort-amount-up"></i> Prioridad de Orden</label>
                    <div class="col-sm-10">
                        <input type="number" name="ReportTypeOrderPriority" class="form-control" value="{{ $reportType->ReportTypeOrderPriority }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ReportTypePinReported" class="col-sm-2 col-form-label"><i class="fas fa-map-pin"></i> Pin Reportado</label>
                    <div class="col-sm-10">
                        <div class="custom-file">
                            <input type="file" name="ReportTypePinReported" class="custom-file-input" id="ReportTypePinReported">
                            <label class="custom-file-label" for="ReportTypePinReported">Elige archivo</label>
                        </div>
                        @if($reportType->ReportTypePinReported)
                            <img src="{{ $reportType->ReportTypePinReported }}" alt="Pin Reportado" class="img-fluid mt-2" style="width: 50px; height: auto;">
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ReportTypePinInProcess" class="col-sm-2 col-form-label"><i class="fas fa-spinner"></i> Pin en Proceso</label>
                    <div class="col-sm-10">
                        <div class="custom-file">
                            <input type="file" name="ReportTypePinInProcess" class="custom-file-input" id="ReportTypePinInProcess">
                            <label class="custom-file-label" for="ReportTypePinInProcess">Elige archivo</label>
                        </div>
                        @if($reportType->ReportTypePinInProcess)
                            <img src="{{ $reportType->ReportTypePinInProcess }}" alt="Pin en Proceso" class="img-fluid mt-2" style="width: 50px; height: auto;">
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ReportTypePinFinished" class="col-sm-2 col-form-label"><i class="fas fa-check-circle"></i> Pin Terminado</label>
                    <div class="col-sm-10">
                        <div class="custom-file">
                            <input type="file" name="ReportTypePinFinished" class="custom-file-input" id="ReportTypePinFinished">
                            <label class="custom-file-label" for="ReportTypePinFinished">Elige archivo</label>
                        </div>
                        @if($reportType->ReportTypePinFinished)
                            <img src="{{ $reportType->ReportTypePinFinished }}" alt="Pin Terminado" class="img-fluid mt-2" style="width: 50px; height: auto;">
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ReportTypeImage" class="col-sm-2 col-form-label"><i class="fas fa-image"></i> Imagen</label>
                    <div class="col-sm-10">
                        <div class="custom-file">
                            <input type="file" name="ReportTypeImage" class="custom-file-input" id="ReportTypeImage">
                            <label class="custom-file-label" for="ReportTypeImage">Elige archivo</label>
                        </div>
                        @if($reportType->ReportTypeImage)
                            <img src="{{ $reportType->ReportTypeImage }}" alt="Imagen" class="img-fluid mt-2" style="width: 50px; height: auto;">
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ReportTypeIsActive" class="col-sm-2 col-form-label"><i class="fas fa-toggle-on"></i> Activo</label>
                    <div class="col-sm-10">
                        <select name="ReportTypeIsActive" class="form-control" required>
                            <option value="1" {{ $reportType->ReportTypeIsActive ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ !$reportType->ReportTypeIsActive ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ReportTypeRegistrationDate" class="col-sm-2 col-form-label"><i class="fas fa-calendar-alt"></i> Fecha de Registro</label>
                    <div class="col-sm-10">
                        <input type="date" name="ReportTypeRegistrationDate" class="form-control" value="{{ \Carbon\Carbon::parse($reportType->ReportTypeRegistrationDate)->format('Y-m-d') }}" required>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-custom"><i class="fas fa-save"></i> Guardar</button>
                <a href="{{ route('admin.catalog.reports.type') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Regresar</a>
            </div>
        </form>
    </div>
@stop

@section('jscode')
    <script>
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    </script>
@endsection
