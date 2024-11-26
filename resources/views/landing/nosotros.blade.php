@extends('landing.master')

@section('title')
    Nosotros
@endsection

@section('main')
    <section class="page-header padding">
        <div class="container">
            <div class="page-content text-center">
                <h2>Sobre nosotros</h2>
                <p>Empoderando comunidades mediante tecnología y reportes ciudadanos.</p>
            </div>
        </div>
    </section>

    <section class="service-section section-2" style="padding: 30px 0;">
        <div class="dots"></div>
    </section>

    <section class="work-pro-section padding" style="background-color: #333; color: white;">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="wow fadeInUp" style="color: white; font-size: 2.5rem; margin-bottom: 20px;">Nuestra
                        Misión</h2>
                    <p style="font-size: 1.25rem; text-align: justify;">
                        En Radar Ciudadano, nos comprometemos a transformar comunidades a través de soluciones
                        tecnológicas innovadoras que facilitan el reporte de incidencias, la comunicación con las
                        autoridades y el acceso a información clave. Nuestro enfoque está en conectar ciudadanos con
                        su entorno, mejorando la calidad de vida y promoviendo la eficiencia en la gestión pública.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section padding">
        <div class="container">
            <div class="row about-wrap">
                <div class="col-lg-6 sm-padding">
                    <div class="about-content wow fadeInLeft">
                        <h2>Transformamos comunidades con tecnología ciudadana</h2>
                        <p style="text-align: justify;">
                            En Radar Ciudadano, diseñamos y desarrollamos herramientas tecnológicas que facilitan el
                            reporte de incidencias geolocalizadas, permitiendo una comunicación eficiente entre los
                            ciudadanos y las autoridades. Nuestro objetivo es crear un impacto positivo, promoviendo la
                            participación activa y mejorando el entorno comunitario.
                        </p>
                        <a href="{{ route('website.contacto') }}" class="default-btn">Contáctanos</a>
                    </div>
                </div>
                <div class="col-lg-6 sm-padding">
                    <ul class="about-promo">
                        <li class="about-promo-item wow fadeInUp">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <h3>Geolocalización Precisa</h3>
                                <p style="text-align: justify;">
                                    Facilitamos la ubicación precisa de incidencias para garantizar una respuesta rápida
                                    y eficiente por parte de las autoridades.
                                </p>
                            </div>
                        </li>
                        <li class="about-promo-item wow fadeInUp" data-wow-delay="300ms">
                            <i class="fas fa-share-alt"></i>
                            <div>
                                <h3>Conexión Ciudadana</h3>
                                <p style="text-align: justify;">
                                    Creamos puentes entre ciudadanos y gobierno, promoviendo la transparencia y la
                                    participación activa en la mejora del entorno.
                                </p>
                            </div>
                        </li>
                        <li class="about-promo-item wow fadeInUp" data-wow-delay="500ms">
                            <i class="fas fa-chart-line"></i>
                            <div>
                                <h3>Soluciones Innovadoras</h3>
                                <p style="text-align: justify;">
                                    Implementamos tecnologías avanzadas para optimizar procesos y ofrecer resultados
                                    tangibles y sostenibles.
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="work-pro-section padding" style="background-color: #333; color: white;">
        <div class="container">
            <div class="row" style="margin-top: 40px;">
                <div class="col-12 text-center">
                    <h3 class="wow fadeInUp" style="color: #FFF; font-size: 2rem; margin-bottom: 20px;">Contáctanos</h3>
                    <p style="font-size: 1.25rem; text-align: justify;">
                        ¿Tienes preguntas o necesitas más información sobre nuestros servicios? ¡Estamos aquí para
                        ayudarte! Ponte en contacto con nosotros y descubre cómo podemos trabajar juntos para mejorar
                        tu comunidad.
                    </p>
                    <a href="{{ route('website.contacto') }}" class="default-btn" style="margin-top: 20px;">Ponte en
                        contacto</a>
                </div>
            </div>
        </div>
    </section>

    <section class="service-section section-2 bg-grey padding">
        <div class="dots"></div>
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-lg-6 sm-padding">
                    <div class="row services-list">
                        <div class="col-md-6 padding-15">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="100ms">
                                <i class="fas fa-exclamation-triangle"></i>
                                <h3>Reportar Incidencias</h3>
                                <p style="text-align: justify;">
                                    Ofrecemos una plataforma sencilla y eficiente para reportar problemas en tu comunidad
                                    y darles seguimiento oportuno.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 padding-15 offset-top">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="300ms">
                                <i class="fas fa-file-alt"></i>
                                <h3>Trámites y Servicios</h3>
                                <p style="text-align: justify;">
                                    Consulta información relevante sobre trámites gubernamentales y servicios locales.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 padding-15">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="400ms">
                                <i class="fas fa-phone-alt"></i>
                                <h3>Directorios de Emergencia</h3>
                                <p style="text-align: justify;">
                                    Accede rápidamente a números de emergencia y contactos clave para atender cualquier
                                    eventualidad.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 padding-15 offset-top">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="500ms">
                                <i class="fas fa-chart-bar"></i>
                                <h3>Análisis de Datos</h3>
                                <p style="text-align: justify;">
                                    Procesamos datos en tiempo real para optimizar la toma de decisiones y mejorar la
                                    gestión comunitaria.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 sm-padding">
                    <div class="service-content wow fadeInLeft">
                        <h2>Impulsamos comunidades inteligentes</h2>
                        <p style="text-align: justify;">
                            Radar Ciudadano combina tecnología avanzada y una visión comunitaria para resolver problemas
                            de manera eficiente. Desde el reporte de incidencias hasta el análisis de datos, nuestra
                            plataforma está diseñada para conectar a los ciudadanos con las soluciones que necesitan.
                        </p>
                        <a href="{{ route('website.servicios') }}" class="default-btn">Conoce Nuestros Servicios</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
