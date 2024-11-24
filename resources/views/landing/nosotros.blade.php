@extends('landing.master')

@section('title')
    Nosotros
@endsection

@section('main')
    <section class="page-header padding">
        <div class="container">
            <div class="page-content text-center">
                <h2>Sobre nosotros</h2>
                <p>Investigación y tecnología aplicada.</p>
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
                        Experiencia</h2>
                    <p style="font-size: 1.25rem; text-align: justify;">En Grupo de Investigación y Tecnología Aplicada AI, contamos con un
                        equipo de profesionales altamente cualificados y con amplia experiencia en el campo de la
                        cartografía y el análisis de datos. Hemos trabajado con una variedad de clientes, proporcionando
                        soluciones efectivas y resultados tangibles.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section padding">
        <div class="container">
            <div class="row about-wrap">
                <div class="col-lg-6 sm-padding">
                    <div class="about-content wow fadeInLeft">
                        <h2>En Grupo de Investigación y Tecnología Aplicada AI, nos dedicamos a innovar en cartografía y
                            análisis de datos.</h2>
                        <p style="text-align: justify;">En Grupo de Investigación y Tecnología Aplicada AI, nos enorgullecemos de ofrecer soluciones
                            innovadoras y personalizadas para satisfacer las necesidades de nuestros clientes. Nuestra
                            misión es proporcionar servicios de alta calidad que impulsen el éxito de nuestros clientes en
                            un mundo cada vez más centrado en los datos. Nos esforzamos por ofrecer soluciones adaptadas a
                            las necesidades específicas de cada proyecto, impulsando así la innovación y la eficiencia.</p>
                        <a href="{{ route('website.contacto') }}" class="default-btn">Contactarnos</a>
                    </div>
                </div>
                <div class="col-lg-6 sm-padding">
                    <ul class="about-promo">
                        <li class="about-promo-item wow fadeInUp">
                            <i class="flaticon-tanks"></i>
                            <div>
                                <h3>Innovación Continua</h3>
                                <p style="text-align: justify;">Nos centramos en la investigación y el desarrollo continuo para ofrecer las soluciones
                                    más avanzadas.</p>
                            </div>
                        </li>
                        <li class="about-promo-item wow fadeInUp" data-wow-delay="300ms">
                            <i class="flaticon-worker"></i>
                            <div>
                                <h3>Compromiso con el Cliente</h3>
                                <p style="text-align: justify;">Nuestro equipo está dedicado a entender y satisfacer las necesidades específicas de cada
                                    cliente.</p>
                            </div>
                        </li>
                        <li class="about-promo-item wow fadeInUp" data-wow-delay="500ms">
                            <i class="flaticon-gear"></i>
                            <div>
                                <h3>Soluciones Personalizadas</h3>
                                <p style="text-align: justify;">Desarrollamos soluciones a medida que se adaptan perfectamente a los objetivos y
                                    requisitos de nuestros clientes.</p>
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
                    <p style="font-size: 1.25rem;">¿Estás interesado en aprender más sobre nuestros servicios? ¡No dudes en
                        ponerte en contacto con nosotros para discutir cómo podemos ayudarte a alcanzar tus objetivos!</p>
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
                                <i class="flaticon-worker"></i>
                                <!-- Cambiado a un ícono de tecnología representativa -->
                                <h3>Cartografía</h3>
                                <p style="text-align: justify;">Nuestra experiencia en cartografía nos permite ofrecer una amplia gama de servicios,
                                    desde la digitalización de mapas hasta la creación de modelos geoespaciales avanzados.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 padding-15 offset-top">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="300ms">
                                <i class="flaticon-control-system"></i> <!-- Mantenido por ser adecuado para el servicio -->
                                <h3>Servicios de Aplicaciones</h3>
                                <p style="text-align: justify;">Desarrollamos aplicaciones personalizadas para satisfacer las
                                    necesidades específicas de nuestros clientes.</p>
                            </div>
                        </div>
                        <div class="col-md-6 padding-15">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="400ms">
                                <i class="flaticon-generator"></i> <!-- Asumiendo que es parte de tu colección existente -->
                                <h3>Mapas Satelitales</h3>
                                <p style="text-align: justify;">Ofrecemos acceso a mapas satelitales de alta resolución que proporcionan una
                                    visión detallada y actualizada del mundo.</p>
                            </div>
                        </div>
                        <div class="col-md-6 padding-15 offset-top">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="500ms">
                                <i class="flaticon-tanks"></i> <!-- Asumiendo que es adecuado para análisis de datos -->
                                <h3>Análisis de Datos</h3>
                                <p style="text-align: justify;">Nuestro equipo de expertos en análisis de datos utiliza técnicas avanzadas para
                                    extraer información valiosa de grandes conjuntos de datos.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 sm-padding">
                    <div class="service-content wow fadeInLeft">
                        <h2>Ofrecemos soluciones innovadoras en cartografía y análisis de datos</h2>
                        <p style="text-align: justify;">En Grupo de Investigación y Tecnología Aplicada AI, nos dedicamos a ofrecer soluciones
                            innovadoras
                            en los campos de la cartografía, servicios de aplicaciones, mapas satelitales y análisis de
                            datos. Con
                            un enfoque centrado en la excelencia y la satisfacción del cliente, trabajamos incansablemente
                            para
                            proporcionar servicios de alta calidad que impulsen el éxito de nuestros clientes.</p>
                        <a href="{{ route('website.servicios') }}" class="default-btn">Conoce Nuestros Servicios</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
