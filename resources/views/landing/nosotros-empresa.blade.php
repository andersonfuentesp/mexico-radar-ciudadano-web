@extends('landing.master')

@section('title')
    Nosotros - Empresa
@endsection

@section('main')
    <section class="page-header padding">
        <div class="container">
            <div class="page-content text-center">
                <h2>Sobre la empresa</h2>
                <p>Investigación y tecnología aplicada.</p>
            </div>
        </div>
    </section>
    <section class="service-section section-2 bg-grey padding">
        <div class="dots"></div>
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-lg-6 sm-padding">
                    <div class="service-content wow fadeInLeft">
                        <span>Acerca de Grupo de Investigación y Tecnología Aplicada AI</span>
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
            </div>
        </div>
    </section>

@endsection
