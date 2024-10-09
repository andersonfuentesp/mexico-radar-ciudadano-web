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
            <!-- Grid de búsqueda actualizado -->
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <form action="{{ route('admin.reports.all') }}" method="GET" class="form-inline">
                        <div class="form-row align-items-center custom-form-row">
                            <div class="col-auto">
                                <input type="text" name="search_estado" id="search_estado" class="form-control mb-2" placeholder="Buscar por estado" value="{{ request()->get('search_estado') }}">
                            </div>
                            <div class="col-auto">
                                <input type="text" name="search_municipio" id="search_municipio" class="form-control mb-2" placeholder="Buscar por municipio" value="{{ request()->get('search_municipio') }}">
                            </div>
                            <div class="col-auto">
                                <select name="report_type" id="report_type" class="form-control mb-2">
                                    <option value="">Selecciona Tipo de Reporte</option>
                                    @foreach ($reportTypes as $id => $name)
                                        <option value="{{ $id }}" {{ request()->get('report_type') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <input type="date" name="search_vigencia_inicial" id="search_vigencia_inicial" class="form-control mb-2" value="{{ request()->get('search_vigencia_inicial') }}">
                            </div>
                            <div class="col-auto">
                                <input type="date" name="search_vigencia_final" id="search_vigencia_final" class="form-control mb-2" value="{{ request()->get('search_vigencia_final') }}">
                            </div>
                            <div class="col-auto">
                                <select name="report_status" id="report_status" class="form-control mb-2">
                                    <option value="">Selecciona Estatus</option>
                                    @foreach ($reportStatuses as $id => $status)
                                        <option value="{{ $id }}" {{ request()->get('report_status') == $id ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-custom mb-2"><i class="fas fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de reportes -->
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Estado</th>
                        <th>Municipio</th>
                        <th>Tipo de Reporte</th>
                        <th>Colonia</th>
                        <th>Dirección</th>
                        <th>Comentario</th>
                        <th>Estatus</th>
                        <th>Fecha de Registro</th>
                        <th>URL</th> <!-- Nueva columna para el botón de URL -->
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $key => $report)
                        <tr>
                            <td>{{ ($reports->currentPage() - 1) * $reports->perPage() + $key + 1 }}</td>
                            <td>{{ $report['report_id'] }}</td>
                            <td>{{ $report['EstadoNombre'] }}</td>
                            <td>{{ $report['MunicipioNombre'] }}</td>
                            <td>{{ $report['report_type'] }}</td>
                            <td>{{ $report['neighborhood_name'] }}</td>
                            <td>{!! $report['report_address'] !!}</td>
                            <td>{!! $report['report_comment'] !!}</td>
                            <td><span class="badge badge-dark">{{ $report['report_status'] }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($report['created_at'])->format('d/m/Y H:i') }}</td>

                            <!-- Botón para la URL del municipio -->
                            <td>
                                @if ($report['municipality_url'])
                                    <a href="{{ $report['municipality_url'] }}" class="btn btn-warning" target="_blank" title="Ir al sitio">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @else
                                    <span class="text-muted">No disponible</span>
                                @endif
                            </td>

                            <td>
                                @can('admin.reports.detail')
                                    <a href="{{ route('admin.reports.detail', encrypt($report['report_id'])) }}" class="btn btn-info sm" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $reports->appends(request()->query())->links() }}
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
@stop
