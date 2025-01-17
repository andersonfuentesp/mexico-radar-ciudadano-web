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

            <form action="{{ route('admin.reports.all') }}" method="GET" class="form-horizontal">
                <div class="row align-items-end">
                    <!-- Estado -->
                    <div class="col-lg-1-5 col-md-2 col-12 mb-3">
                        <select name="estado_id" id="StateId" class="form-control select2" required>
                            <option value="">-- Estado --</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->EstadoId }}"
                                    {{ request()->get('estado_id') == $state->EstadoId ? 'selected' : '' }}>
                                    {{ $state->EstadoNombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Municipio -->
                    <div class="col-lg-1-5 col-md-2 col-12 mb-3">
                        <select name="municipio_id" id="MunicipalityId" class="form-control select2" required>
                            <option value="">-- Municipio --</option>
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
                    <div class="col-lg-1-5 col-md-2 col-12 mb-3">
                        <select name="report_type" id="report_type" class="form-control select2">
                            <option value="">-- Tipo de Reporte --</option>
                            @foreach ($reportTypes as $type)
                                <option value="{{ $type }}"
                                    {{ request()->get('report_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Folio -->
                    <div class="col-lg-1 col-md-2 col-12 mb-3">
                        <input type="text" name="report_folio" id="report_folio" class="form-control" placeholder="Folio"
                            value="{{ request()->get('report_folio') }}">
                    </div>

                    <!-- Fecha Inicio -->
                    <div class="col-lg-1-5 col-md-2 col-12 mb-3">
                        <input type="date" name="search_start" id="search_start" class="form-control"
                            value="{{ request()->get('search_start') }}">
                    </div>

                    <!-- Fecha Fin -->
                    <div class="col-lg-1-5 col-md-2 col-12 mb-3">
                        <input type="date" name="search_end" id="search_end" class="form-control"
                            value="{{ request()->get('search_end') }}">
                    </div>

                    <!-- Estatus -->
                    <div class="col-lg-1 col-md-2 col-12 mb-3">
                        <select name="search_status_name" id="search_status_name" class="form-control select2">
                            <option value="">-- Estatus --</option>
                            @foreach ($reportStatuses as $status)
                                <option value="{{ $status }}"
                                    {{ request()->get('search_status_name') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Contratado -->
                    <div class="col-lg-1-5 col-md-2 col-12 mb-3">
                        <select name="contratado" id="contratado" class="form-control select2">
                            <option value="">¿Contratado?</option>
                            <option value="1" {{ request()->get('contratado') == '1' ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ request()->get('contratado') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <!-- Botón de búsqueda -->
                    <div class="col-md-auto col-12 mb-3">
                        <button type="submit" class="btn btn-custom w-100">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tabla de reportes ciudadanos -->
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Folio</th>
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
                            <td>{{ $report->report_folio }}</td>
                            <td>{{ $report->EstadoNombre }}</td>
                            <td>{{ $report->MunicipioNombre }}</td>
                            <td>{{ $report->report_type_id }}</td>
                            <td>
                                <button type="button" class="btn btn-success" style="width: 45px; height: 40px;"
                                    data-toggle="modal" data-target="#addressModal"
                                    data-address="{{ strip_tags($report->report_address) }}"
                                    data-map-url="https://www.google.com/maps?q={{ urlencode(strip_tags($report->report_address)) }}&output=embed">
                                    <i class="fas fa-map-marker-alt" style="font-size: 1.5rem;"></i>
                                </button>
                            </td>
                            <td>{!! $report->report_comment !!}</td>
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

            <div class="d-flex justify-content-center">
                {{ $reports->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- Modal para mostrar dirección y mapa -->
    <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addressModalLabel">Dirección Completa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modalAddressContent" style="margin-bottom: 15px;"></div>
                    <iframe id="mapIframe" src="" style="border:0; width: 100%; height: 600px;"
                        allowfullscreen></iframe>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary btn-sm" id="copyAddressButton"
                        title="Copiar al Portapapeles">
                        <i class="fas fa-copy"></i> Copiar
                    </button>
                    <button class="btn btn-outline-primary btn-sm" id="shareLocationButton" title="Compartir Ubicación">
                        <i class="fas fa-share-alt"></i> Compartir
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
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

            $('#addressModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var address = button.data('address');
                var mapUrl = button.data('map-url');
                var modal = $(this);

                modal.find('#modalAddressContent').html(address || 'No disponible');
                modal.find('#mapIframe').attr('src', mapUrl);

                $('#copyAddressButton').off('click').on('click', function() {
                    navigator.clipboard.writeText(address).then(function() {
                        alert('Dirección copiada al portapapeles.');
                    }, function(err) {
                        alert('Error al copiar la dirección.');
                    });
                });

                $('#shareLocationButton').off('click').on('click', function() {
                    var shareData = {
                        title: 'Ubicación',
                        text: 'Mira esta dirección: ' + address,
                        url: mapUrl
                    };
                    if (navigator.share) {
                        navigator.share(shareData).catch(err => console.log('Error al compartir:',
                            err));
                    } else {
                        alert('La función de compartir no está soportada en este navegador.');
                    }
                });
            });

            $('#addressModal').on('hidden.bs.modal', function() {
                $(this).find('#mapIframe').attr('src', '');
            });
        });
    </script>
@stop
