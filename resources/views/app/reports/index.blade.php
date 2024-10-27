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
            <input type="hidden" id="municipiosRoute" value="{{ route('admin.utilitie.getMunicipiosByEstado', '') }}">

            <!-- Grid de búsqueda -->
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <form action="{{ route('admin.reports.all') }}" method="GET" class="form-inline">
                        <div class="form-row align-items-center custom-form-row">
                            <!-- Estado -->
                            <div class="col-auto" style="width: 230px;">
                                <select name="estado_id" id="StateId" class="form-control select2" required>
                                    <option value="">Seleccione un estado</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->EstadoId }}"
                                            {{ request()->get('estado_id') == $state->EstadoId ? 'selected' : '' }}>
                                            {{ $state->EstadoNombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Municipio -->
                            <div class="col-auto" style="width: 250px;">
                                <select name="municipio_id" id="MunicipalityId" class="form-control select2" required>
                                    <option value="">Seleccione un municipio</option>
                                    @if (!empty($municipios))
                                        @foreach ($municipios as $municipio)
                                            <option value="{{ $municipio->MunicipioId }}"
                                                {{ request()->get('municipio_id') == $municipio->MunicipioId ? 'selected' : '' }}>
                                                {{ $municipio->MunicipioNombre }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <!-- Tipo de reporte -->
                            <div class="col-auto">
                                <select name="report_type" id="report_type" class="form-control">
                                    <option value="">Selecciona Tipo de Reporte</option>
                                    @foreach ($reportTypes as $type)
                                        <option value="{{ $type }}"
                                            {{ request()->get('report_type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Fechas -->
                            <div class="col-auto">
                                <input type="date" name="search_start" id="search_start" class="form-control"
                                    value="{{ request()->get('search_start') }}">
                            </div>
                            <div class="col-auto">
                                <input type="date" name="search_end" id="search_end" class="form-control"
                                    value="{{ request()->get('search_end') }}">
                            </div>
                            <!-- Estatus -->
                            <div class="col-auto">
                                <select name="search_status_name" id="search_status_name" class="form-control">
                                    <option value="">Selecciona Estatus</option>
                                    @foreach ($reportStatuses as $status)
                                        <option value="{{ $status }}"
                                            {{ request()->get('search_status_name') == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Contratado -->
                            <div class="col-auto">
                                <select name="contratado" id="contratado" class="form-control">
                                    <option value="">¿Contratado?</option>
                                    <option value="1" {{ request()->get('contratado') == '1' ? 'selected' : '' }}>Sí
                                    </option>
                                    <option value="0" {{ request()->get('contratado') == '0' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                            </div>
                            <!-- Botón de búsqueda -->
                            <div class="col-auto">
                                <button type="submit" class="btn btn-custom mb-2"><i class="fas fa-search"></i>
                                    Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de reportes ciudadanos -->
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Estado</th>
                        <th>Municipio</th>
                        <th>Tipo de Reporte</th>
                        <th>Dirección</th>
                        <th>Comentario</th>
                        <th>Estatus</th>
                        <th>Fecha de Registro</th>
                        <th>URL Municipio</th>
                        <th>¿Contratado?</th>
                        <th>Origen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $key => $report)
                        <tr>
                            <td>{{ ($reports->currentPage() - 1) * $reports->perPage() + $key + 1 }}</td>
                            <td>{{ $report->report_id }}</td>
                            <td>{{ $report->EstadoNombre }}</td>
                            <td>{{ $report->MunicipioNombre }}</td>
                            <td>{{ $report->report_type_id }}</td>
                            <td>{!! $report->report_address !!}</td>
                            <td>{!! $report->report_comment !!}</td>
                            <!-- Estatus con badge -->
                            <td>
                                @if ($report->report_status_id == 'Completado')
                                    <span class="badge badge-success">{{ $report->report_status_id }}</span>
                                @elseif ($report->report_status_id == 'Pendiente')
                                    <span class="badge badge-warning">{{ $report->report_status_id }}</span>
                                @elseif ($report->report_status_id == 'En Proceso')
                                    <span class="badge badge-info">{{ $report->report_status_id }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ $report->report_status_id }}</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($report->report_registration_time)->format('d/m/Y H:i') }}</td>
                            <!-- Columna con URL del municipio -->
                            <td>
                                @if ($report->municipality_url)
                                    <a href="{{ $report->municipality_url }}" class="btn btn-warning" target="_blank"
                                        title="Ir al sitio">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @else
                                    <span class="text-muted">No disponible</span>
                                @endif
                            </td>
                            <td>
                                @if ($report->is_contracted_municipality)
                                    <span class="badge badge-success">Sí</span>
                                @else
                                    <span class="badge badge-danger">No</span>
                                @endif
                            </td>
                            <td>{{ $report->generated_from ?? 'No especificado' }}</td>
                            <td>
                                <!-- Enlace a la ruta de detalle con encriptación -->
                                <a href="{{ route('admin.reports.detail', [
                                    'report_id' => encrypt($report->report_id),
                                    'state_id' => encrypt($report->state_id),
                                    'municipality_id' => encrypt($report->municipality_id),
                                ]) }}"
                                    class="btn btn-info sm" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
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

            $('#StateId').change(function() {
                var estadoId = $(this).val();
                var municipioSelect = $('#MunicipalityId');
                var municipiosRoute = $('#municipiosRoute').val();

                if (estadoId) {
                    fetch(`${municipiosRoute}/${estadoId}`)
                        .then(response => response.json())
                        .then(municipios => {
                            municipioSelect.html('<option value="">Seleccione un municipio</option>');
                            municipios.forEach(municipio => {
                                municipioSelect.append(new Option(municipio.MunicipioNombre,
                                    municipio.MunicipioId));
                            });
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    municipioSelect.html('<option value="">Seleccione un municipio</option>');
                }
            });
        });
    </script>
@stop
