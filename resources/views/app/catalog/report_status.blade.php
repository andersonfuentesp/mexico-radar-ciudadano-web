@extends('master')
@section('title', 'Gestión de Estatus de Reporte')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active">Gestión de Estatus de Reporte</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Gestión de Estatus de Reporte</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file-alt"></i> Lista de Estatus de Reporte</h3>
            <a href="{{ route('admin.catalog.report.status.add') }}" class="btn btn-custom float-right">
                <i class="fas fa-plus"></i> Agregar Estatus
            </a>
        </div>

        <div class="card-body">
            <table id="datatable" class="table table-striped table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre del Estatus</th>
                        <th>Fecha de Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($statuses as $key => $status)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $status->report_status_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($status->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if (in_array($status->report_status_id, [1, 2, 3, 4]))
                                    <button class="btn btn-danger sm delete-button" title="Eliminar" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    <form action="{{ route('admin.catalog.report.status.delete', $status->report_status_id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger sm delete-button" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $statuses->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
@stop
