@extends('master')

@section('title')
    Servicios - Mapas Satelitales
@endsection

@section('main')
    <div class="video-container">
        <video id="videoHeader" class="video-header" autoplay loop muted playsinline>
            <source src="{{ asset('website/videos/slider-mapas.mp4') }}" type="video/mp4">
            Tu navegador no admite el elemento <code>video</code>.
        </video>
        <!-- Agregamos un div para la sombra -->
        <div class="video-content text-center">
            <h2>Mapas Satelitales</h2>
            <p>Investigación y tecnología aplicada.</p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var video = document.getElementById('videoHeader');
            video.playbackRate = 1.5;
        });
    </script>

    <section class="service-section section-2" style="padding: 30px 0;">
        <div class="dots"></div>
    </section>

    <section class="service-section bg-grey padding">
        <div class="dark-bg"></div>
        <div class="section-heading dark-background text-center mb-40 wow fadeInUp" data-wow-delay="100ms">
            <span>Mapas Satelitales y Análisis Geoespacial</span>
            <h2>Explora servicios especializados en mapas satelitales y herramientas geoespaciales avanzadas.</h2>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div id="service-carousel" class="service-carousel box-shadow owl-carousel" style="display: flex;">
                        <!-- Acceso a mapas satelitales -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 350px;">
                            <div class="service-icon">
                                <i class="fas fa-satellite-dish"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Acceso a Mapas Satelitales</h3>
                            <p style="text-align: justify;">Proporcionamos acceso a mapas satelitales de alta resolución a
                                través de nuestra plataforma en línea fácil de usar.</p>
                        </div>
                        <!-- Herramientas de visualización avanzadas -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 350px;">
                            <div class="service-icon">
                                <i class="fas fa-chart-bar"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Herramientas de Visualización Avanzadas</h3>
                            <p style="text-align: justify;">Ofrecemos herramientas de visualización avanzadas que te
                                permiten explorar y analizar los mapas satelitales con detalle.</p>
                        </div>
                        <!-- Integración de mapas satelitales -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 350px;">
                            <div class="service-icon">
                                <i class="fas fa-code-branch"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Integración de Mapas Satelitales</h3>
                            <p style="text-align: justify;">Facilitamos la integración de mapas satelitales en tus propias
                                aplicaciones o sistemas de información geográfica (SIG) para una mayor funcionalidad.</p>
                        </div>
                        <!-- Asistencia técnica y soporte -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 350px;">
                            <div class="service-icon">
                                <i class="fas fa-headset"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Soporte Técnico y Asistencia</h3>
                            <p style="text-align: justify;">Brindamos asistencia técnica y soporte para garantizar una
                                experiencia sin problemas al utilizar nuestros mapas satelitales.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="work-pro-section padding" style="background-color: #333; color: white;">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="wow fadeInUp" style="color: white; font-size: 2.5rem; margin-bottom: 20px;">Beneficios de los
                        Mapas Satelitales</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="100ms">
                        <span class="number">1</span>
                        <div class="number-line"></div>
                        <h3>Imágenes Detalladas</h3>
                        <p style="text-align: justify;">Nuestros mapas satelitales proporcionan imágenes detalladas de la
                            superficie terrestre, permitiéndote observar características geográficas, topografía,
                            vegetación, cuerpos de agua y mucho más con una claridad impresionante.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="200ms">
                        <span class="number">2</span>
                        <div class="number-line"></div>
                        <h3>Cobertura Global</h3>
                        <p style="text-align: justify;">Con nuestro servicio de mapas satelitales, puedes acceder a imágenes
                            de alta resolución de cualquier lugar en la Tierra, desde áreas urbanas hasta regiones remotas.
                            Ya sea que necesites explorar ciudades, selvas, desiertos o montañas, tenemos la cobertura que
                            necesitas.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="300ms">
                        <span class="number">3</span>
                        <h3>Actualizaciones Frecuentes</h3>
                        <p style="text-align: justify;">Nuestros mapas satelitales se actualizan regularmente para
                            garantizar que siempre tengas acceso a la información más reciente y precisa disponible. Esto te
                            permite tomar decisiones informadas y mantener tus proyectos actualizados con los últimos datos
                            disponibles.</p>
                    </div>
                </div>
                <!-- Se puede añadir más beneficios si es necesario -->
            </div>
        </div>
    </section>

    <section class="service-section section-2 bg-grey padding">
        <div class="dots"></div>
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-lg-6 sm-padding">
                    <div class="service-content wow fadeInLeft">
                        <h2>Mapas Satelitales de Alta Resolución</h2>
                        <p style="text-align: justify;">En Grupo de Investigación y Tecnología Aplicada AI, ofrecemos un
                            servicio de mapas satelitales de
                            vanguardia que te permite explorar el mundo desde una perspectiva única y detallada. Con acceso
                            a imágenes de alta resolución tomadas por satélites de última generación, nuestros mapas
                            satelitales ofrecen una visión precisa y actualizada de cualquier lugar en el planeta.</p>
                        <a href="{{ route('website.cotizacion') }}" class="default-btn">Cotizar ahora</a>
                    </div>
                </div>
                <div class="col-lg-6 sm-padding">
                    <div class="row services-list">
                        <!-- Acceso a mapas satelitales -->
                        <div class="col-md-6 padding-15">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="100ms">
                                <i class="fas fa-satellite"></i> <!-- Ícono actualizado para Acceso a Mapas Satelitales -->
                                <h3>Acceso a Mapas Satelitales</h3>
                                <p style="text-align: justify;">Acceso a mapas satelitales de alta resolución a través de
                                    nuestra plataforma en línea fácil de usar.</p>
                            </div>
                        </div>
                        <!-- Herramientas de visualización avanzadas -->
                        <div class="col-md-6 padding-15 offset-top">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="300ms">
                                <i class="fas fa-chart-bar"></i>
                                <!-- Ícono actualizado para Herramientas de Visualización -->
                                <h3>Herramientas de Visualización</h3>
                                <p style="text-align: justify;">Herramientas de visualización avanzadas que te permiten
                                    explorar y analizar los mapas satelitales con detalle.</p>
                            </div>
                        </div>
                        <!-- Integración de mapas satelitales -->
                        <div class="col-md-6 padding-15">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="400ms">
                                <i class="fas fa-code-branch"></i>
                                <!-- Ícono actualizado para Integración de Mapas Satelitales -->
                                <h3>Integración de Mapas Satelitales</h3>
                                <p style="text-align: justify;">Integración de mapas satelitales en tus propias aplicaciones
                                    o sistemas de información geográfica (SIG) para una mayor funcionalidad y utilidad.</p>
                            </div>
                        </div>
                        <!-- Asistencia técnica y soporte -->
                        <div class="col-md-6 padding-15 offset-top">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="500ms">
                                <i class="fas fa-headset"></i> <!-- Ícono actualizado para Soporte Técnico -->
                                <h3>Soporte Técnico</h3>
                                <p style="text-align: justify;">Asistencia técnica y soporte para garantizar una experiencia
                                    sin problemas al utilizar nuestros mapas satelitales.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="service-section section-2">
        <div class="dots"></div>
    </section>

    <div class="work-pro-section padding">
        <div class="container">
            <div class="cta-content text-center">
                <span class="wow fadeInUp">¿Por qué elegirnos?</span>
                <h2 class="wow fadeInUp" data-wow-delay="300ms">Líderes en Mapas Satelitales de Alta Precisión</h2>
                <p class="wow fadeInUp" data-wow-delay="400ms">
                    En el Grupo de Investigación y Tecnología Aplicada AI, nos comprometemos a proporcionar a nuestros
                    clientes los mejores servicios y productos disponibles en el mercado. Nuestra oferta de mapas
                    satelitales destaca por una combinación inigualable de precisión, cobertura y facilidad de uso,
                    convirtiéndolos en la solución perfecta para una amplia gama de aplicaciones, desde la planificación
                    urbana hasta la gestión de recursos naturales y mucho más.
                </p>
                <p class="wow fadeInUp" data-wow-delay="500ms">
                    ¿Listo para explorar el mundo desde arriba? Regístrate hoy mismo para obtener acceso a nuestros mapas
                    satelitales y descubre un nuevo nivel de información geoespacial que puede transformar tu forma de ver y
                    gestionar el espacio.
                </p>
                <a href="{{ route('website.cotizacion') }}" class="default-btn wow fadeInUp"
                    data-wow-delay="600ms">Cotizalo Ahora</a>
            </div>
        </div>
    </div>

    <section class="service-section section-2">
        <div class="dots"></div>
    </section>
@endsection
