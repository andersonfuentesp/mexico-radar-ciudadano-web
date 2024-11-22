@extends('master')
@section('title', 'Agregar Municipio Contratado')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.contractedMunicipality.all') }}"><i
                                    class="fas fa-building"></i> Gestión de Municipios Contratados</a></li>
                        <li class="breadcrumb-item active"><a><i class="fas fa-plus"></i> Agregar Municipio</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Agregar Municipio Contratado</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('csscode')
    <style>
        .required-field:required:invalid {
            border: 2px solid red !important;
            border-radius: 0.375rem;
        }

        .select2-container.border-danger .select2-selection {
            border: 2px solid red !important;
            border-radius: 0.375rem;
        }

        .select2-container--bootstrap4 .select2-selection {
            border-radius: 0.375rem;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title"><i class="fas fa-plus"></i> Datos del Municipio Contratado</h1>
        </div>

        <form class="form-horizontal" id="municipalityForm" method="POST"
            action="{{ route('admin.contractedMunicipality.store') }}">
            @csrf
            <input type="hidden" id="municipiosRoute" value="{{ route('admin.utilitie.getMunicipiosByEstado', '') }}">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="StateId"><i class="fas fa-map-marker-alt mr-2"></i> Estado</label>
                            <select name="state_id" id="StateId" class="form-control select2 required-field" required>
                                <option value="">Seleccione un estado</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->EstadoId }}">{{ $state->EstadoNombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="MunicipalityId"><i class="fas fa-city mr-2"></i> Municipio</label>
                            <select name="municipality_id" id="MunicipalityId" class="form-control select2 required-field"
                                required>
                                <option value="">Seleccione un municipio</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name"><i class="fas fa-building mr-2"></i> Nombre del Municipio
                                Contratado</label>
                            <input type="text" name="name" class="form-control required-field"
                                placeholder="Nombre del municipio" required>
                        </div>

                        <div class="form-group">
                            <label for="contract_date"><i class="fas fa-calendar-alt mr-2"></i> Fecha de Contrato</label>
                            <input type="date" name="contract_date" class="form-control required-field" required>
                        </div>

                        <div class="form-group">
                            <label for="contact_responsible"><i class="fas fa-user mr-2"></i> Responsable de
                                Contacto</label>
                            <input type="text" name="contact_responsible" class="form-control required-field"
                                placeholder="Nombre del responsable" required>
                        </div>

                        <div class="form-group">
                            <label for="contact_phone1"><i class="fas fa-phone-alt mr-2"></i> Teléfono de Contacto</label>
                            <input type="text" name="contact_phone1" class="form-control required-field"
                                placeholder="Teléfono principal" required>
                        </div>

                        <div class="form-group">
                            <label for="contact_email"><i class="fas fa-envelope mr-2"></i> Email de Contacto</label>
                            <input type="email" name="contact_email" class="form-control"
                                placeholder="Email del responsable">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contract_number"><i class="fas fa-file-contract mr-2"></i> Número de
                                Contrato</label>
                            <input type="text" name="contract_number" class="form-control"
                                placeholder="Número de contrato">
                        </div>

                        <div class="form-group">
                            <label for="url"><i class="fas fa-link mr-2"></i> URL del Municipio</label>
                            <input type="url" name="url" class="form-control" placeholder="https://example.com">
                        </div>

                        <div class="form-group">
                            <label for="token"><i class="fas fa-key mr-2"></i> Token del Municipio</label>
                            <input type="text" name="token" class="form-control"
                                placeholder="Token de acceso del municipio">
                        </div>

                        <div class="form-group">
                            <label for="description"><i class="fas fa-file-alt mr-2"></i> Descripción</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Descripción del municipio..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="contact_position"><i class="fas fa-briefcase mr-2"></i> Cargo del
                                Responsable</label>
                            <input type="text" name="contact_position" class="form-control"
                                placeholder="Cargo del responsable">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-custom"><i class="fas fa-save"></i> Guardar</button>
                <a href="{{ route('admin.contractedMunicipality.all') }}" class="btn btn-default"><i
                        class="fas fa-arrow-left"></i> Regresar</a>
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

            // Validación dinámica para Select2
            function validateSelect2Fields() {
                $('.select2.required-field').each(function() {
                    let $select = $(this);
                    let $container = $select.siblings('.select2-container');
                    if ($select.val() === "" || $select.val() === null) {
                        $container.addClass('border-danger');
                    } else {
                        $container.removeClass('border-danger');
                    }
                });
            }

            // Validación inicial al cargar la página
            validateSelect2Fields();

            // Validación en tiempo real
            $('.select2.required-field').on('change', function() {
                validateSelect2Fields();
            });

            // Validación del formulario al enviar
            $('#municipalityForm').on('submit', function(e) {
                validateSelect2Fields(); // Validar Select2 antes de enviar
                if ($('.border-danger').length > 0) {
                    e.preventDefault(); // Evitar envío si hay errores
                }
            });
        });
    </script>
@stop
