@extends('master')
@section('title', 'Gestión de Reportes Ciudadanos')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active">Gestión de Reportes Ciudadanos</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Gestión de Reportes Ciudadanos</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-exclamation-circle"></i> Lista de Reportes Ciudadanos</h3>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <form action="{{ route('admin.reports.all') }}" method="GET" class="form-inline">
                        <div class="form-row align-items-center custom-form-row">
                            <div class="col-auto">
                                <label for="search_estado" class="sr-only">Estado</label>
                                <input type="text" name="search_estado" id="search_estado"
                                    class="form-control mb-2 custom-input" placeholder="Buscar por estado"
                                    value="{{ request()->get('search_estado') }}">
                            </div>
                            <div class="col-auto">
                                <label for="search_municipio" class="sr-only">Municipio</label>
                                <input type="text" name="search_municipio" id="search_municipio"
                                    class="form-control mb-2 custom-input" placeholder="Buscar por municipio"
                                    value="{{ request()->get('search_municipio') }}">
                            </div>
                            <div class="col-auto">
                                <label for="search_dependencia" class="sr-only">Dependencia</label>
                                <input type="text" name="search_dependencia" id="search_dependencia"
                                    class="form-control mb-2 custom-input" placeholder="Buscar por dependencia"
                                    value="{{ request()->get('search_dependencia') }}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-custom mb-2 custom-button"><i
                                        class="fas fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    @can('admin.reports.add')
                        <a href="{{ route('admin.reports.add') }}" class="btn btn-custom mb-2 custom-button"><i
                                class="fas fa-plus"></i> Agregar Reporte</a>
                    @endcan
                </div>
            </div>
            <table id="datatable" class="table table-striped table-bordered"
                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Estado</th>
                        <th>Municipio</th>
                        <th>Dependencia</th>
                        <th>Colonia</th>
                        <th>Dirección</th>
                        <th>Comentario</th>
                        <th>Estatus</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $key => $report)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $report->report_id }}</td>
                            <td>{{ $report->EstadoNombre }}</td>
                            <td>{{ $report->MunicipioNombre }}</td>
                            <td>{{ $report->DependencyName }}</td>
                            <td>{{ $report->NeighborhoodName }}</td>
                            <td>{!! $report->report_address !!}</td>
                            <td>{!! $report->report_comment !!}</td>
                            <td><span class="badge badge-dark">{{ $report->ReportStatusName }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                @can('admin.reports.detail')
                                    <a href="{{ route('admin.reports.detail', encrypt($report->report_id)) }}"
                                        class="btn btn-info sm" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan

                                @can('admin.reports.edit')
                                    <a href="{{ route('admin.reports.edit', encrypt($report->report_id)) }}"
                                        class="btn btn-warning sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan

                                @can('admin.reports.delete')
                                    <form action="{{ route('admin.reports.delete', encrypt($report->report_id)) }}"
                                        method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger sm delete-button" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $reports->appends([
                    'search_estado' => request()->get('search_estado'),
                    'search_municipio' => request()->get('search_municipio'),
                    'search_dependencia' => request()->get('search_dependencia'),
                ])->links() }}
            </div>
        </div>
    </div>
@stop

@section('jscode')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@endsection
