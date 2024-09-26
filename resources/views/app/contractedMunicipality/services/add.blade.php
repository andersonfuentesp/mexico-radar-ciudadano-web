@extends('master')
@section('title', 'Agregar Servicio al Municipio Contratado')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.contractedMunicipality.all') }}"><i
                                    class="fas fa-city"></i> Municipios Contratados</a></li>
                        <li class="breadcrumb-item active"><a><i class="fas fa-plus"></i> Agregar Servicio</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Agregar Servicio al Municipio:
                            {{ $municipality->name }}</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title"><i class="fas fa-plus"></i> Datos del Servicio</h1>
        </div>

        <form class="form-horizontal" method="POST"
            action="{{ route('admin.contractedMunicipality.services.store', encrypt($municipality->id)) }}">
            @csrf

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="service_name"><i class="fas fa-cogs mr-2"></i> Nombre del Servicio</label>
                            <input type="text" name="service_name" class="form-control" placeholder="Nombre del servicio"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="api_url"><i class="fas fa-link mr-2"></i> API URL</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $municipality->url }}" readonly>
                                <input type="text" name="api_url" class="form-control"
                                    placeholder="Segmento adicional de la URL" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="method"><i class="fas fa-code-branch mr-2"></i> Método HTTP</label>
                            <select name="method" class="form-control select2" required>
                                <option value="GET">GET</option>
                                <option value="POST">POST</option>
                                <option value="PUT">PUT</option>
                                <option value="DELETE">DELETE</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="api_token"><i class="fas fa-key mr-2"></i> Token de API (solo lectura)</label>
                            <input type="text" name="api_token" class="form-control" value="{{ $municipality->token }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label for="response_format"><i class="fas fa-file-code mr-2"></i> Formato de Respuesta</label>
                            <select name="response_format" class="form-control select2" required>
                                <option value="JSON">JSON</option>
                                <option value="XML">XML</option>
                                <option value="CSV">CSV</option>
                                <option value="Other">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description"><i class="fas fa-file-alt mr-2"></i> Descripción del Servicio</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Descripción del servicio..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="status"><i class="fas fa-toggle-on mr-2"></i> Estado del Servicio</label>
                            <select name="status" class="form-control select2" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-custom"><i class="fas fa-save"></i> Guardar Servicio</button>
                <a href="{{ route('admin.contractedMunicipality.services', encrypt($municipality->id)) }}"
                    class="btn btn-default"><i class="fas fa-arrow-left"></i> Regresar</a>
            </div>
        </form>
    </div>
@stop

@section('jscode')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@stop
