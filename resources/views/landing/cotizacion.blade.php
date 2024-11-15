@extends('master')

@section('title')
    Cotización
@endsection

@section('main')
    <section class="page-header padding">
        <div class="container">
            <div class="page-content text-center">
                <h2>Solicita una cotización</h2>
                <p>Es rápido y sencillo.</p>
            </div>
        </div>
    </section>
    <section class="contact-section bg-grey padding">
        <div class="dots"></div>
        <div class="container">
            <div class="contact-wrap d-flex align-items-center row">
                <div class="col-md-6 padding-15">
                    <div class="contact-info">
                        <h2>Solicitar cotización</h2>
                        <p style="text-align: justify;">En Grupo de Investigación y Tecnología Aplicada AI, estamos aquí para
                            ayudarte a encontrar
                            soluciones personalizadas que se adapten a tus necesidades específicas en cartografía, servicios
                            de aplicaciones, mapas satelitales y análisis de datos. Completa el formulario a continuación y
                            nos pondremos en contacto contigo lo antes posible para discutir tus requerimientos y
                            proporcionarte
                            una cotización detallada para tu proyecto.</p>
                        <h4>Nos pondremos en contacto contigo en la brevedad posible</h4>
                        <br><br>
                        <div style="text-align: center; position: relative;">
                            <!-- Ícono de satélite con FontAwesome -->
                            <i class="fas fa-satellite"
                                style="font-size: 110px; color: #130a66; position: absolute; left: 0; top: 0;"></i>
                            <!-- Ícono personalizado de drone en SVG, ajustado para Blade -->
                            <img src="{{ asset('website/img/cotizacion/drone.svg') }}" alt="Drone"
                                style="width: 360px; position: relative; bottom: 0; margin-left: 120px;">
                        </div>

                    </div>
                </div>
                <div class="col-md-6 padding-15">
                    <div class="contact-form">
                        <form action="{{ route('website.cotizacion.send') }}" method="POST" class="form-horizontal">
                            @csrf

                            <!-- Aquí vamos a mostrar los errores de validación -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Nombre" value="{{ old('name') }}" required>
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        placeholder="Teléfono" value="{{ old('phone') }}">
                                    @if ($errors->has('phone'))
                                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <input type="email" id="email" name="email" class="form-control"
                                        placeholder="Correo Electrónico" value="{{ old('email') }}" required>
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <select id="services" name="services" class="form-control" required
                                        style="appearance: auto; -webkit-appearance: menulist-button; height: auto;">
                                        <option value="" {{ old('services') == '' ? 'selected' : '' }}>Servicios de
                                            Interés</option>
                                        <option value="Cartografía"
                                            {{ old('services') == 'Cartografía' ? 'selected' : '' }}>Cartografía</option>
                                        <option value="Servicios de Vuelo de Dron para Cartografía"
                                            {{ old('services') == 'Servicios de Vuelo de Dron para Cartografía' ? 'selected' : '' }}>
                                            Servicios de Vuelo de Dron para Cartografía</option>
                                        <option value="Mapas Satelitales"
                                            {{ old('services') == 'Mapas Satelitales' ? 'selected' : '' }}>Mapas
                                            Satelitales</option>
                                        <option value="Servicios de Aplicaciones"
                                            {{ old('services') == 'Servicios de Aplicaciones' ? 'selected' : '' }}>
                                            Servicios de Aplicaciones</option>
                                        <option value="Análisis de Datos"
                                            {{ old('services') == 'Análisis de Datos' ? 'selected' : '' }}>Análisis de
                                            Datos</option>
                                        <option value="other" {{ old('services') == 'other' ? 'selected' : '' }}>Otro
                                            (Especificar)</option>
                                    </select>
                                    @if ($errors->has('services'))
                                        <span class="text-danger">{{ $errors->first('services') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row" id="other_services_div"
                                style="{{ old('services') == 'other' ? '' : 'display: none;' }}">
                                <div class="col-md-12">
                                    <input type="text" id="other_services" name="other_services" class="form-control"
                                        placeholder="Si seleccionó 'Otro', especifique"
                                        value="{{ old('other_services') }}">
                                    @if ($errors->has('other_services'))
                                        <span class="text-danger">{{ $errors->first('other_services') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <textarea id="project_description" name="project_description" rows="4" class="form-control"
                                        placeholder="Descripción del Proyecto" required>{{ old('project_description') }}</textarea>
                                    @if ($errors->has('project_description'))
                                        <span class="text-danger">{{ $errors->first('project_description') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <input type="text" id="start_date" name="start_date" class="form-control"
                                        placeholder="Fecha inicio del Proyecto (si aplica)"
                                        value="{{ old('start_date') }}">
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" id="estimated_budget" name="estimated_budget" class="form-control"
                                        placeholder="Presupuesto Estimado (si aplica)"
                                        value="{{ old('estimated_budget') }}">
                                    @if ($errors->has('estimated_budget'))
                                        <span class="text-danger">{{ $errors->first('estimated_budget') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <select id="discovery_method" name="discovery_method" class="form-control" required
                                        style="appearance: auto; -webkit-appearance: menulist-button; height: auto;">
                                        <option value="">¿Cómo nos encontraste?</option>
                                        <option value="Búsqueda en Internet"
                                            {{ old('discovery_method') == 'Búsqueda en Internet' ? 'selected' : '' }}>
                                            Búsqueda en Internet</option>
                                        <option value="Recomendación de un conocido"
                                            {{ old('discovery_method') == 'Recomendación de un conocido' ? 'selected' : '' }}>
                                            Recomendación de un conocido</option>
                                        <option value="Redes Sociales"
                                            {{ old('discovery_method') == 'Redes Sociales' ? 'selected' : '' }}>Redes
                                            Sociales</option>
                                        <option value="other" {{ old('discovery_method') == 'other' ? 'selected' : '' }}>
                                            Otro (Especificar)</option>
                                    </select>
                                    @if ($errors->has('discovery_method'))
                                        <span class="text-danger">{{ $errors->first('discovery_method') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row" id="other_discovery_method_div"
                                style="{{ old('discovery_method') == 'other' ? '' : 'display: none;' }}">
                                <div class="col-md-12">
                                    <input type="text" id="other_discovery_method" name="other_discovery_method"
                                        class="form-control" placeholder="Si seleccionó 'Otro', especifique"
                                        value="{{ old('other_discovery_method') }}">
                                    @if ($errors->has('other_discovery_method'))
                                        <span class="text-danger">{{ $errors->first('other_discovery_method') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <textarea id="additional_comments" name="additional_comments" rows="3" class="form-control"
                                        placeholder="Comentarios Adicionales">{{ old('additional_comments') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button id="submit" class="default-btn" type="submit">Enviar mensaje</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Escuchar cambios en el select de servicios
            document.getElementById('services').addEventListener('change', function() {
                // Mostrar u ocultar el campo de otro servicio
                var display = this.value === 'other' ? 'block' : 'none';
                document.getElementById('other_services_div').style.display = display;
            });

            // Escuchar cambios en el select de método de descubrimiento
            document.getElementById('discovery_method').addEventListener('change', function() {
                // Mostrar u ocultar el campo de otro método de descubrimiento
                var display = this.value === 'other' ? 'block' : 'none';
                document.getElementById('other_discovery_method_div').style.display = display;
            });
        });
    </script>
@endsection
