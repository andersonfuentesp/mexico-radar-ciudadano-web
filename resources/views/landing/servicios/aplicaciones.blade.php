@extends('master')

@section('title')
    Servicios - Aplicaciones
@endsection

@section('main')
    <div class="video-container">
        <video id="videoHeader" class="video-header" autoplay loop muted playsinline>
            <source src="{{ asset('website/videos/slider-aplicaciones.mp4') }}" type="video/mp4">
            Tu navegador no admite el elemento <code>video</code>.
        </video>
        <!-- Agregamos un div para la sombra -->
        <div class="video-overlay"></div>
        <div class="video-content text-center">
            <h2>Servicios de aplicaciones</h2>
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
            <span>Desarrollo de aplicaciones</span>
            <h2>Explora servicios especializados en desarrollo de aplicaciones móviles y web.</h2>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div id="service-carousel" class="service-carousel box-shadow owl-carousel" style="display: flex;">
                        <!-- Desarrollo de aplicaciones móviles -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 390px;">
                            <div class="service-icon">
                                <i class="fas fa-mobile-alt"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Desarrollo de Aplicaciones Móviles</h3>
                            <p style="text-align: justify;">Ofrecemos servicios de desarrollo de aplicaciones móviles para
                                plataformas como iOS y Android, incluyendo aplicaciones nativas, híbridas y web.</p>
                        </div>
                        <!-- Desarrollo de aplicaciones web -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 390px;">
                            <div class="service-icon">
                                <i class="fas fa-code"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Desarrollo de Aplicaciones Web</h3>
                            <p style="text-align: justify;">Desarrollamos aplicaciones web personalizadas como sitios web
                                interactivos, portales de clientes y sistemas de gestión de contenido (CMS).</p>
                        </div>
                        <!-- Desarrollo de aplicaciones empresariales -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 390px;">
                            <div class="service-icon">
                                <i class="fas fa-business-time"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Desarrollo de Aplicaciones Empresariales</h3>
                            <p style="text-align: justify;">Creamos soluciones de software personalizadas para empresas,
                                incluyendo ERP, MRP y sistemas CRM.</p>
                        </div>
                        <!-- Desarrollo de aplicaciones para ecommerce -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 390px;">
                            <div class="service-icon">
                                <i class="fas fa-shopping-cart"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Desarrollo de Aplicaciones para Ecommerce</h3>
                            <p style="text-align: justify;">Brindamos desarrollo de aplicaciones para comercio electrónico,
                                gestión de inventario y pasarelas de pago.</p>
                        </div>
                        <!-- Mantenimiento y soporte de aplicaciones -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 390px;">
                            <div class="service-icon">
                                <i class="fas fa-tools"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Mantenimiento y Soporte de Aplicaciones</h3>
                            <p style="text-align: justify;">Proporcionamos mantenimiento continuo y soporte técnico para
                                aplicaciones, incluyendo actualizaciones y optimización de seguridad.</p>
                        </div>
                        <!-- Consultoría en desarrollo de aplicaciones -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 390px;">
                            <div class="service-icon">
                                <i class="fas fa-lightbulb"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Consultoría en Desarrollo de Aplicaciones</h3>
                            <p style="text-align: justify;">Ofrecemos servicios de consultoría para definir requisitos de
                                software, seleccionar tecnologías y planificar implementaciones.</p>
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
                    <h2 class="wow fadeInUp" style="color: white; font-size: 2.5rem; margin-bottom: 20px;">Beneficios del
                        Desarrollo de Aplicaciones</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="100ms">
                        <span class="number">1</span>
                        <div class="number-line"></div>
                        <h3>Personalización Completa</h3>
                        <p style="text-align: justify;">Ofrecemos desarrollos a medida para que tu aplicación se ajuste
                            perfectamente a las necesidades específicas de tu negocio, mejorando la experiencia del usuario
                            y optimizando los procesos internos.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="200ms">
                        <span class="number">2</span>
                        <div class="number-line"></div>
                        <h3>Integración Fluida</h3>
                        <p style="text-align: justify;">Nuestras aplicaciones están diseñadas para integrarse sin problemas
                            con tu infraestructura existente, asegurando una transición suave y manteniendo la continuidad
                            del negocio.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="300ms">
                        <span class="number">3</span>
                        <h3>Escalabilidad Futura</h3>
                        <p style="text-align: justify;">Preparamos tus aplicaciones para el futuro, construyéndolas con la
                            escalabilidad en mente para facilitar actualizaciones y adaptaciones según las tendencias y
                            requisitos cambiantes del mercado.</p>
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
                        <h2>Nuestros servicios de desarrollo de aplicaciones</h2>
                        <p style="text-align: justify;">En Grupo de Investigación y Tecnología Aplicada AI, proporcionamos
                            una amplia gama de servicios
                            de desarrollo de aplicaciones móviles y web adaptados a las necesidades de tu negocio. Desde la
                            creación de aplicaciones móviles intuitivas hasta soluciones web personalizadas y sistemas
                            empresariales, nuestro equipo de expertos está listo para llevar tu proyecto desde la concepción
                            hasta la realidad. Además, ofrecemos servicios de consultoría, mantenimiento y soporte técnico
                            para garantizar el máximo rendimiento y la mejor seguridad de tus aplicaciones.</p>
                        <a href="{{ route('website.cotizacion') }}" class="default-btn">Cotizar ahora</a>
                    </div>
                </div>
                <div class="col-lg-6 sm-padding">
                    <div class="row services-list">
                        <!-- Desarrollo de aplicaciones móviles -->
                        <div class="col-md-6 padding-15">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="100ms">
                                <i class="fas fa-mobile-alt"></i> <!-- FontAwesome icon for mobile app development -->
                                <h3>Desarrollo de Aplicaciones Móviles</h3>
                                <p style="text-align: justify;">Desarrollo integral de aplicaciones para iOS y Android,
                                    asegurando una experiencia de usuario óptima y funcionalidades avanzadas.</p>
                            </div>
                        </div>
                        <!-- Desarrollo de aplicaciones web -->
                        <div class="col-md-6 padding-15 offset-top">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="300ms">
                                <i class="fas fa-code"></i> <!-- FontAwesome icon for web development -->
                                <h3>Desarrollo de Aplicaciones Web</h3>
                                <p style="text-align: justify;">Creación de aplicaciones web personalizadas que impulsan el
                                    engagement y la eficiencia operativa de tu negocio en línea.</p>
                            </div>
                        </div>
                        <!-- Desarrollo de aplicaciones empresariales -->
                        <div class="col-md-6 padding-15">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="400ms">
                                <i class="fas fa-business-time"></i>
                                <!-- FontAwesome icon for enterprise app development -->
                                <h3>Desarrollo de Aplicaciones Empresariales</h3>
                                <p style="text-align: justify;">Implementación de sistemas ERP, CRM y soluciones
                                    empresariales para automatizar y optimizar procesos de negocio.</p>
                            </div>
                        </div>
                        <!-- Desarrollo de aplicaciones para ecommerce -->
                        <div class="col-md-6 padding-15 offset-top">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="500ms">
                                <i class="fas fa-shopping-cart"></i> <!-- FontAwesome icon for ecommerce development -->
                                <h3>Desarrollo de Aplicaciones para Ecommerce</h3>
                                <p style="text-align: justify;">Construcción de plataformas de comercio electrónico que
                                    ofrecen experiencias de compra seguras y memorables.</p>
                            </div>
                        </div>
                        <!-- Mantenimiento y soporte de aplicaciones -->
                        <!-- Consultoría en desarrollo de aplicaciones -->
                        <!-- Puedes agregar más servicios aquí si es necesario -->
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
                <h2 class="wow fadeInUp" data-wow-delay="300ms">Innovación en Desarrollo de Aplicaciones</h2>
                <p class="wow fadeInUp" data-wow-delay="400ms">
                    En el Grupo de Investigación y Tecnología Aplicada AI, lideramos la vanguardia en el desarrollo de
                    aplicaciones personalizadas. Nos comprometemos a superar las expectativas, ofreciendo soluciones que no
                    solo cumplen sino que transforman las necesidades empresariales de nuestros clientes en éxitos
                    tangibles. Nuestro equipo experto utiliza las últimas tecnologías y metodologías ágiles para asegurar
                    proyectos eficientes y de alta calidad, desde aplicaciones móviles hasta soluciones web complejas.
                </p>
                <p class="wow fadeInUp" data-wow-delay="500ms">
                    ¿Tienes una idea que puede cambiar el juego? ¡Conviértela en realidad con nosotros! Contáctanos hoy para
                    discutir cómo podemos traer al mundo tu visión única con nuestra experiencia en desarrollo de
                    aplicaciones.
                </p>
                <a href="{{ route('website.cotizacion') }}" class="default-btn wow fadeInUp"
                    data-wow-delay="600ms">Inicia
                    tu Proyecto</a>
            </div>
        </div>
    </div>

    <section class="service-section section-2">
        <div class="dots"></div>
    </section>
@endsection
