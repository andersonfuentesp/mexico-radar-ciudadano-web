@extends('landing.master')

@section('title')
    Nosotros - Empresa
@endsection

@section('main')
    <section class="page-header padding">
        <div class="container">
            <div class="page-content text-center">
                <h2>Sobre la empresa</h2>
                <p>Empoderando comunidades con tecnología y soluciones innovadoras.</p>
            </div>
        </div>
    </section>
    <section class="service-section section-2 bg-grey padding">
        <div class="dots"></div>
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-lg-6 sm-padding">
                    <div class="service-content wow fadeInLeft">
                        <span>Acerca de Radar Ciudadano</span>
                        <h2>Innovamos para mejorar comunidades y facilitar la gestión ciudadana</h2>
                        <p style="text-align: justify;">
                            En Radar Ciudadano, estamos comprometidos con el desarrollo de soluciones tecnológicas que
                            transformen la manera en que las comunidades gestionan y resuelven incidencias. Desde la
                            geolocalización de reportes hasta el análisis avanzado de datos, nuestro enfoque se centra
                            en conectar a los ciudadanos con las herramientas y servicios necesarios para crear un
                            entorno más seguro, eficiente y colaborativo.
                        </p>
                        <a href="{{ route('website.servicios') }}" class="default-btn">Conoce Nuestros Servicios</a>
                    </div>
                </div>
                <div class="col-lg-6 sm-padding">
                    <div class="row services-list">
                        <div class="col-md-6 padding-15">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="100ms">
                                <i class="fas fa-map-marker-alt"></i>
                                <h3>Geolocalización</h3>
                                <p style="text-align: justify;">
                                    Nuestra plataforma permite localizar incidencias con precisión, agilizando la
                                    respuesta de las autoridades y optimizando recursos comunitarios.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 padding-15 offset-top">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="300ms">
                                <i class="fas fa-mobile-alt"></i>
                                <h3>Desarrollo de Aplicaciones</h3>
                                <p style="text-align: justify;">
                                    Creamos aplicaciones móviles y web personalizadas que facilitan la interacción entre
                                    los ciudadanos y las autoridades locales.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 padding-15">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="400ms">
                                <i class="fas fa-globe"></i>
                                <h3>Acceso a Información</h3>
                                <p style="text-align: justify;">
                                    Proporcionamos acceso rápido y confiable a directorios de emergencia, trámites y
                                    servicios gubernamentales esenciales.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 padding-15 offset-top">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="500ms">
                                <i class="fas fa-chart-pie"></i>
                                <h3>Análisis de Datos</h3>
                                <p style="text-align: justify;">
                                    Utilizamos datos en tiempo real para generar estadísticas e insights que apoyan la
                                    toma de decisiones estratégicas y mejoran la gestión comunitaria.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
