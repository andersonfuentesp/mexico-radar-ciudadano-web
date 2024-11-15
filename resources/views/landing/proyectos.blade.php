@extends('master')

@section('title')
    Proyectos
@endsection

@section('main')
    <section class="page-header padding">
        <div class="container">
            <div class="page-content text-center">
                <h2>Proyectos</h2>
                <p>Desarrollados por Grupo de investigación y tecnología aplicada AI.</p>
            </div>
        </div>
    </section>

    <section class="service-section section-2" style="padding: 30px 0;">
        <div class="dots"></div>
    </section>

    <section class="service-section section-2 bg-grey padding">
        <div class="dots"></div>
        <div class="container">
            <!-- Project 1: Optimización de Rutas de Entrega para Empresa de Logística -->
            <div class="row content-wrap">
                <div class="col-lg-6 sm-padding wow fadeInLeft" data-wow-delay="100ms">
                    <img class="box-shadow" src="{{ asset('website/img/projects/project_1.jpg') }}"
                        alt="Optimización de Rutas de Entrega">
                </div>
                <div class="col-lg-6 sm-padding">
                    <div class="content-info wow fadeInRight" data-wow-delay="300ms">
                        <h2>Optimización de Rutas de Entrega para Empresa de Logística</h2>
                        <p>En colaboración con una empresa líder en logística, desarrollamos una solución integral para
                            optimizar sus operaciones de entrega. Utilizando nuestra aplicación móvil personalizada, la cual
                            integra datos de cartografía, análisis de datos y seguimiento en tiempo real, pudimos diseñar
                            rutas más eficientes, reducir los tiempos de entrega y mejorar la satisfacción del cliente.</p>
                    </div>
                </div>
            </div>
            <br>
            <hr>
            <br>
            <!-- Project 2: Desarrollo de Aplicación Móvil para Gestión de Ventas en Ruta -->
            <div class="row content-wrap">
                <div class="col-lg-6 sm-padding">
                    <div class="content-info wow fadeInRight" data-wow-delay="300ms">
                        <h2>Desarrollo de Aplicación Móvil para Gestión de Ventas en Ruta</h2>
                        <p>En un proyecto innovador para una empresa de ventas directas, creamos una aplicación móvil
                            personalizada que agiliza el proceso de ventas y entregas en ruta. Nuestra aplicación permite a
                            los vendedores realizar pedidos en tiempo real, optimizar sus rutas de entrega y acceder a
                            información de clientes y productos en cualquier momento y lugar.</p>
                    </div>
                </div>
                <div class="col-lg-6 sm-padding wow fadeInLeft" data-wow-delay="100ms">
                    <img class="box-shadow" src="{{ asset('website/img/projects/project_2.jpg') }}"
                        alt="Gestión de Ventas en Ruta">
                </div>
            </div>
            <br>
            <hr>
            <br>
            <!-- Project 3: Plataforma Web GIS para Gobierno Local -->
            <div class="row content-wrap">
                <div class="col-lg-6 sm-padding wow fadeInLeft" data-wow-delay="100ms">
                    <img class="box-shadow" src="{{ asset('website/img/projects/project_3.jpg') }}"
                        alt="Plataforma Web GIS">
                </div>
                <div class="col-lg-6 sm-padding">
                    <div class="content-info wow fadeInRight" data-wow-delay="300ms">
                        <h2>Plataforma Web GIS para Gobierno Local</h2>
                        <p>En colaboración con un gobierno local, desarrollamos una plataforma web SIG (Sistemas de
                            Información Geográfica) que permite a los ciudadanos acceder y explorar datos geoespaciales de
                            su comunidad. Integrando mapas interactivos, aplicaciones y análisis de datos, creamos una
                            herramienta poderosa para la planificación urbana, la gestión de recursos y la participación
                            ciudadana.</p>
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
                <span class="wow fadeInUp">Nuestros Proyectos Destacados</span>
                <h2 class="wow fadeInUp" data-wow-delay="300ms">Innovación y Calidad en Tecnología Geoespacial</h2>
                <p class="wow fadeInUp" data-wow-delay="400ms">
                    En el Grupo de Investigación y Tecnología Aplicada AI, nos enorgullece haber colaborado con una amplia
                    gama de clientes, entregando soluciones innovadoras y de alta calidad que cumplen con sus necesidades
                    únicas. Estos son solo algunos ejemplos de los emocionantes proyectos en los que hemos trabajado.
                </p>
                <p class="wow fadeInUp" data-wow-delay="500ms">
                    ¿Tienes un proyecto en mente? ¡Contáctanos hoy mismo! Estamos listos para ayudarte a convertir tu visión
                    en realidad.
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
