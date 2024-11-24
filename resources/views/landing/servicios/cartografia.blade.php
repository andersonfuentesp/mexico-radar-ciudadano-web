@extends('landing.master')

@section('title')
    Servicios - Cartografía
@endsection

@section('main')
    <div class="video-container">
        <video id="videoHeader" class="video-header" autoplay loop muted playsinline>
            <source src="{{ asset('website/videos/slider-cartografia.mp4') }}" type="video/mp4">
            Tu navegador no admite el elemento <code>video</code>.
        </video>
        <div class="video-content text-center">
            <h2>Cartografía</h2>
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
            <span>Servicios de Cartografía y Desarrollo</span>
            <h2>Explora servicios especializados en tecnología de la información y cartografía.</h2>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div id="service-carousel" class="service-carousel box-shadow owl-carousel" style="display: flex;">
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 380px;">
                            <div class="service-icon">
                                <i class="fas fa-plane-departure"></i> <!-- FontAwesome Icon Updated -->
                            </div>
                            <h3>Planificación de Vuelo</h3>
                            <p style="text-align: justify;">Planificación de vuelo personalizada según tus requisitos
                                específicos, asegurando la captura óptima de datos para tu proyecto.</p>
                        </div>
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 380px;">
                            <div class="service-icon">
                                <i class="fas fa-camera-retro"></i> <!-- FontAwesome Icon Updated -->
                            </div>
                            <h3>Captura de Datos Geoespaciales</h3>
                            <p style="text-align: justify;">Captura de datos geoespaciales de alta calidad utilizando drones
                                equipados con cámaras de última generación para una visión detallada y precisa.</p>
                        </div>
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 380px;">
                            <div class="service-icon">
                                <i class="fas fa-chart-area"></i> <!-- FontAwesome Icon Updated -->
                            </div>
                            <h3>Análisis y Procesamiento de Datos</h3>
                            <p style="text-align: justify;">Procesamiento y análisis de datos para generar productos
                                cartográficos precisos, como ortofotos, modelos de elevación digital, y modelos 3D.</p>
                        </div>
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 380px;">
                            <div class="service-icon">
                                <i class="fas fa-globe"></i> <!-- FontAwesome Icon Updated -->
                            </div>
                            <h3>Entrega de Productos Cartográficos</h3>
                            <p style="text-align: justify;">Entrega de productos finales listos para integrarse en tus
                                sistemas de información geográfica (SIG) o aplicaciones de cartografía.</p>
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
                    <h2 class="wow fadeInUp" style="color: white; font-size: 2.5rem; margin-bottom: 20px;">Beneficios</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="100ms">
                        <span class="number">1</span>
                        <div class="number-line"></div>
                        <h3>Precisión sin igual</h3>
                        <p style="text-align: justify;">Nuestros drones están equipados con tecnología de vanguardia que
                            garantiza la captura de datos
                            geoespaciales con una precisión excepcional. Desde la creación de modelos de elevación digital
                            hasta la generación de ortofotos de alta resolución, puedes confiar en la calidad de nuestros
                            productos.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="200ms">
                        <span class="number">2</span>
                        <div class="number-line"></div>
                        <h3>Eficiencia operativa</h3>
                        <p style="text-align: justify;">Con nuestros drones, podemos cubrir grandes áreas en un tiempo
                            récord, lo que resulta en una
                            mayor eficiencia operativa para tus proyectos de cartografía. Esto significa que puedes obtener
                            resultados más rápidos sin comprometer la precisión.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="300ms">
                        <span class="number">3</span>
                        <h3>Flexibilidad y adaptabilidad</h3>
                        <p style="text-align: justify;">Ya sea que necesites mapear terrenos difíciles de alcanzar o áreas
                            extensas de difícil acceso,
                            nuestros drones pueden adaptarse a una variedad de entornos y condiciones. Desde zonas urbanas
                            hasta entornos naturales, estamos preparados para enfrentar cualquier desafío.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="service-section section-2 bg-grey padding">
        <div class="dots"></div>
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-lg-6 sm-padding">
                    <div class="service-content wow fadeInLeft">
                        <h2>Nuestros servicios incluyen</h2>
                        <p style="text-align: justify;">En Grupo de Investigación y Tecnología Aplicada AI, nos
                            especializamos en la provisión de
                            servicios integrales en el ámbito de la cartografía y análisis geoespacial. Nuestro enfoque
                            abarca desde la planificación de vuelos personalizados para la captura de datos, utilizando
                            drones con tecnología de vanguardia, hasta el procesamiento avanzado de estos datos para crear
                            representaciones cartográficas precisas y detalladas. Nos dedicamos a transformar datos
                            geoespaciales en ortofotos, modelos de elevación digital y modelos 3D, listos para ser
                            integrados en sistemas de información geográfica (SIG) o cualquier aplicación de cartografía.
                            Con un compromiso inquebrantable con la calidad y la satisfacción del cliente, entregamos
                            soluciones que no solo cumplen, sino que superan las expectativas de nuestros clientes.</p>
                        <a href="{{ route('website.cotizacion') }}" class="default-btn">Cotizar ahora</a>
                    </div>
                </div>
                <div class="col-lg-6 sm-padding">
                    <div class="row services-list">
                        <div class="col-md-6 padding-15">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="100ms">
                                <i class="fas fa-plane-departure"></i>
                                <!-- Icono actualizado para Planificación de Vuelo -->
                                <h3>Planificación de Vuelo</h3>
                                <p style="text-align: justify;">Planificación de vuelo personalizada según tus requisitos
                                    específicos, asegurando la captura óptima de datos para tu proyecto.</p>
                            </div>
                        </div>
                        <div class="col-md-6 padding-15 offset-top">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="300ms">
                                <i class="fas fa-satellite-dish"></i>
                                <!-- Icono actualizado para Captura de Datos Geoespaciales -->
                                <h3>Captura de Datos Geoespaciales</h3>
                                <p style="text-align: justify;">Captura de datos geoespaciales de alta calidad utilizando
                                    drones equipados con cámaras de última generación para una visión detallada y precisa.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 padding-15">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="400ms">
                                <i class="fas fa-chart-area"></i>
                                <!-- Icono actualizado para Análisis y Procesamiento de Datos -->
                                <h3>Análisis y Procesamiento de Datos</h3>
                                <p style="text-align: justify;">Procesamiento y análisis de datos para generar productos
                                    cartográficos precisos, como ortofotos, modelos de elevación digital, y modelos 3D.</p>
                            </div>
                        </div>
                        <div class="col-md-6 padding-15 offset-top">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="500ms">
                                <i class="fas fa-map-marked-alt"></i>
                                <!-- Icono actualizado para Entrega de Productos Cartográficos -->
                                <h3>Entrega de Productos Cartográficos</h3>
                                <p style="text-align: justify;">Entrega de productos finales listos para integrarse en tus
                                    sistemas de información geográfica (SIG) o aplicaciones de cartografía.</p>
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
                <h2 class="wow fadeInUp" data-wow-delay="300ms">Excelencia en Servicios de Cartografía</h2>
                <p class="wow fadeInUp" data-wow-delay="400ms">
                    En el Grupo de Investigación y Tecnología Aplicada AI, estamos comprometidos con brindar un servicio
                    excepcional y resultados de alta calidad. Nuestro equipo, conformado por expertos en cartografía y
                    pilotos de drones certificados, trabaja de manera conjunta contigo para asegurar que tus necesidades y
                    objetivos sean cumplidos en cada etapa de tu proyecto.
                </p>
                <p class="wow fadeInUp" data-wow-delay="500ms">
                    ¿Listo para elevar tu cartografía al siguiente nivel? Contáctanos hoy mismo para descubrir cómo nuestros
                    servicios de vuelo de dron pueden aportar un valor inigualable a tu proyecto.
                </p>
                <a href="{{ route('website.contacto') }}" class="default-btn wow fadeInUp"
                    data-wow-delay="600ms">Contáctanos</a>
            </div>
        </div>
    </div>

    <section class="service-section section-2">
        <div class="dots"></div>
    </section>
@endsection
