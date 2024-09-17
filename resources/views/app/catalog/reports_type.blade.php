@extends('master')
@section('title', 'Gestión de Tipos de Reporte')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active">Gestión de Tipos de Reporte</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Gestión de Tipos de Reporte</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file-alt"></i> Lista de Tipos de Reporte</h3>
            <a href="{{ route('admin.catalog.reports.type.add') }}" class="btn btn-custom float-right"><i class="fas fa-plus"></i> Agregar Tipo de Reporte</a>
        </div>

        <div class="card-body">
            <table id="datatable" class="table table-striped table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre del Tipo de Reporte</th>
                        <th>Prioridad de Orden</th>
                        <th>Activo</th>
                        <th>Fecha de Registro</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reportTypes as $key => $reportType)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $reportType->ReportTypeName }}</td>
                            <td>{{ $reportType->ReportTypeOrderPriority }}</td>
                            <td>
                                @if ($reportType->ReportTypeIsActive)
                                    <span class="badge badge-success">Sí</span>
                                @else
                                    <span class="badge badge-danger">No</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($reportType->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($reportType->ReportTypeImage)
                                    <img src="{{ $reportType->ReportTypeImage }}" alt="Imagen" style="width: 50px; height: auto;">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.catalog.reports.type.detail', encrypt($reportType->ReportTypeId)) }}" class="btn btn-info sm" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.catalog.reports.type.edit', encrypt($reportType->ReportTypeId)) }}" class="btn btn-warning sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.catalog.reports.type.delete', encrypt($reportType->ReportTypeId)) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger sm delete-button" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $reportTypes->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
@stop
