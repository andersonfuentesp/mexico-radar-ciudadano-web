@extends('master')
@section('title', 'Agregar Estatus de Reporte')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.catalog.report.status') }}"><i
                                    class="fas fa-file-alt"></i> Gestión de Estatus de Reporte</a></li>
                        <li class="breadcrumb-item active"><a><i class="fas fa-plus"></i> Agregar Estatus</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Agregar Estatus de Reporte</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title"><i class="fas fa-plus"></i> Datos del Estatus de Reporte</h1>
        </div>

        <form class="form-horizontal" method="POST" action="{{ route('admin.catalog.report.status.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="report_status_name"><i class="fas fa-file-alt mr-2"></i> Nombre del Estatus</label>
                    <input type="text" name="report_status_name" class="form-control"
                        placeholder="Ingrese el nombre del estatus" required>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-custom"><i class="fas fa-save"></i> Guardar</button>
                <a href="{{ route('admin.catalog.report.status') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i>
                    Regresar</a>
            </div>
        </form>
    </div>
@stop
