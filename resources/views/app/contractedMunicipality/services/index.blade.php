@extends('master')
@section('title', 'Servicios del Municipio Contratado')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.contractedMunicipality.all') }}"><i class="fas fa-city"></i> Municipios Contratados</a></li>
                        <li class="breadcrumb-item active"><a><i class="fas fa-tools"></i> Servicios</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;">
                        <b>Servicios del Municipio: {{ $municipality->MunicipioNombre }}, Estado: {{ $municipality->EstadoNombre }}</b>
                    </h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('admin.contractedMunicipality.services.add')
                <a href="{{ route('admin.contractedMunicipality.services.add', encrypt($municipality->id)) }}"
                    class="btn btn-custom btn-rounded waves-effect waves-light">
                    <i class="fas fa-plus"></i> Agregar Nuevo Servicio
                </a>
            @endcan
        </div>

        <div class="card-body">
            <table id="datatable" class="table table-striped table-bordered" style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre del Servicio</th>
                        <th>API URL</th>
                        <th>Formato de Respuesta</th>
                        <th>Estado</th>
                        <th>Token de API</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $key => $service)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $service->service_name }}</td>
                            <td>{{ $service->api_url }}</td>
                            <td>{{ $service->response_format }}</td>
                            <td>
                                @if ($service->status)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>{{ $service->api_token ?? 'No disponible' }}</td>
                            <td>{{ \Carbon\Carbon::parse($service->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                @can('admin.contractedMunicipality.services.detail')
                                    <a href="{{ route('admin.contractedMunicipality.services.detail', encrypt($service->id)) }}"
                                        class="btn btn-info" title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan

                                @can('admin.contractedMunicipality.services.edit')
                                    <a href="{{ route('admin.contractedMunicipality.services.edit', ['municipalityId' => encrypt($municipality->id), 'serviceId' => encrypt($service->id)]) }}"
                                        class="btn btn-primary" title="Editar Servicio">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan

                                @can('admin.contractedMunicipality.services.delete')
                                    <form action="{{ route('admin.contractedMunicipality.services.delete', ['municipalityId' => encrypt($municipality->id), 'serviceId' => encrypt($service->id)]) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger delete-button" title="Eliminar Servicio">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan

                                <!-- Botón para probar la conectividad del servicio -->
                                <button class="btn btn-warning test-connection-button" data-url="{{ $service->api_url }}" title="Probar Conectividad">
                                    <i class="fas fa-plug"></i>
                                </button>

                                <!-- Botón para descargar el log de la conexión -->
                                <a class="btn btn-secondary download-log-button" style="display:none;" id="download-log-{{ $key }}" title="Descargar Log de Conexión">
                                    <i class="fas fa-download"></i> Log
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('jscode')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Agregar evento para probar la conectividad usando fetch
            document.querySelectorAll('.test-connection-button').forEach((button, index) => {
                button.addEventListener('click', function() {
                    const apiUrl = this.dataset.url;
                    const downloadLogButton = document.getElementById(`download-log-${index}`);

                    // Mostrar el loader mientras se realiza la prueba de conectividad
                    Swal.fire({
                        title: 'Probando Conectividad...',
                        text: 'Por favor espera mientras se prueba la conexión.',
                        icon: 'info',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Usar fetch para probar la conectividad
                    fetch(apiUrl, {
                        method: 'GET',
                    })
                    .then(response => {
                        Swal.close(); // Cerrar el loader cuando obtenemos respuesta

                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error('Error en la conexión');
                    })
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Conexión Exitosa',
                            text: 'La conexión con el servicio fue exitosa.',
                        });

                        // Crear un archivo log con los detalles de la conexión exitosa
                        const logData = `Conexión Exitosa con el servicio en: ${apiUrl}\nRespuesta: ${JSON.stringify(data)}`;
                        const blob = new Blob([logData], { type: 'text/plain' });
                        const url = window.URL.createObjectURL(blob);

                        // Configurar el botón para descargar el log
                        downloadLogButton.href = url;
                        downloadLogButton.download = 'log_conexion.txt';
                        downloadLogButton.style.display = 'inline-block'; // Mostrar el botón de log
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de Conexión',
                            text: 'No se pudo establecer la conexión con el servicio.',
                        });

                        // Crear un archivo log con los detalles del error
                        const logData = `Error al intentar conectar con el servicio en: ${apiUrl}\nMensaje de error: ${error.message}`;
                        const blob = new Blob([logData], { type: 'text/plain' });
                        const url = window.URL.createObjectURL(blob);

                        // Configurar el botón para descargar el log
                        downloadLogButton.href = url;
                        downloadLogButton.download = 'log_conexion.txt';
                        downloadLogButton.style.display = 'inline-block'; // Mostrar el botón de log
                    });
                });
            });
        });
    </script>
@stop
