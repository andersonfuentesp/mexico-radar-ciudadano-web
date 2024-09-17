@extends('master')
@section('title', 'Agregar Tipo de Reporte')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.catalog.reports.type') }}"><i class="fas fa-file-alt"></i> Gestión de Tipos de Reporte</a></li>
                        <li class="breadcrumb-item active"><a><i class="fas fa-plus"></i> Agregar Tipo de Reporte</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Agregar Tipo de Reporte</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title"><i class="fas fa-plus"></i> Datos del Tipo de Reporte</h1>
        </div>

        <form class="form-horizontal" method="POST" action="{{ route('admin.catalog.reports.type.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="card-body">
                <div class="form-group row">
                    <label for="ReportTypeName" class="col-sm-2 col-form-label"><i class="fas fa-file-alt"></i> Nombre del Tipo de Reporte</label>
                    <div class="col-sm-10">
                        <input type="text" name="ReportTypeName" class="form-control" placeholder="Ingrese el nombre del tipo de reporte" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ReportTypeOrderPriority" class="col-sm-2 col-form-label"><i class="fas fa-sort-amount-up"></i> Prioridad de Orden</label>
                    <div class="col-sm-10">
                        <input type="number" name="ReportTypeOrderPriority" class="form-control" placeholder="Ingrese la prioridad de orden" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ReportTypePinReported" class="col-sm-2 col-form-label"><i class="fas fa-map-pin"></i> Pin Reportado</label>
                    <div class="col-sm-10">
                        <div class="custom-file">
                            <input type="file" name="ReportTypePinReported" class="custom-file-input" id="ReportTypePinReported">
                            <label class="custom-file-label" for="ReportTypePinReported">Elige archivo</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ReportTypePinInProcess" class="col-sm-2 col-form-label"><i class="fas fa-spinner"></i> Pin en Proceso</label>
                    <div class="col-sm-10">
                        <div class="custom-file">
                            <input type="file" name="ReportTypePinInProcess" class="custom-file-input" id="ReportTypePinInProcess">
                            <label class="custom-file-label" for="ReportTypePinInProcess">Elige archivo</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ReportTypePinFinished" class="col-sm-2 col-form-label"><i class="fas fa-check-circle"></i> Pin Terminado</label>
                    <div class="col-sm-10">
                        <div class="custom-file">
                            <input type="file" name="ReportTypePinFinished" class="custom-file-input" id="ReportTypePinFinished">
                            <label class="custom-file-label" for="ReportTypePinFinished">Elige archivo</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ReportTypeImage" class="col-sm-2 col-form-label"><i class="fas fa-image"></i> Imagen</label>
                    <div class="col-sm-10">
                        <div class="custom-file">
                            <input type="file" name="ReportTypeImage" class="custom-file-input" id="ReportTypeImage">
                            <label class="custom-file-label" for="ReportTypeImage">Elige archivo</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ReportTypeIsActive" class="col-sm-2 col-form-label"><i class="fas fa-toggle-on"></i> Activo</label>
                    <div class="col-sm-10">
                        <select name="ReportTypeIsActive" class="form-control select2" required>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ReportTypeRegistrationDate" class="col-sm-2 col-form-label"><i class="fas fa-calendar-alt"></i> Fecha de Registro</label>
                    <div class="col-sm-10">
                        <input type="date" name="ReportTypeRegistrationDate" class="form-control" value="{{ date('Y-m-d') }}" required>
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
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@endsection
