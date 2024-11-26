@extends('landing.master')

@section('title')
    Galería
@endsection

@section('main')
    <section class="page-header padding">
        <div class="container">
            <div class="page-content text-center">
                <h2>Galería</h2>
                <p>Empoderando comunidades mediante tecnología y reportes ciudadanos.</p>
            </div>
        </div>
    </section>

    <section class="gallery-section padding">
        <div class="container">
            <div class="section-title text-center">
                <h2 class="wow fadeInUp" style="color: #333; font-size: 2.5rem; margin-bottom: 20px;">Sé testigo de lo que hacemos</h2>
            </div>

            <!-- Carrusel -->
            <div id="galleryCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <!-- Imagen 1 -->
                    <div class="carousel-item active">
                        <img src="{{ asset('website/img/slides/slide_1.jpg') }}" class="d-block w-100" alt="Reporta Incidencias">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Reporta Incidencias Fácilmente</h5>
                            <p>Utiliza nuestra plataforma para enviar reportes ciudadanos con geolocalización. Conecta con las autoridades y promueve una comunidad más segura.</p>
                        </div>
                    </div>
                    <!-- Imagen 2 -->
                    <div class="carousel-item">
                        <img src="{{ asset('website/img/slides/slide_2.jpg') }}" class="d-block w-100" alt="Georreferenciación de Reportes">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Georreferenciación de Reportes</h5>
                            <p>Facilita la identificación y gestión de problemas en tu comunidad con nuestra plataforma de mapeo interactivo y análisis en tiempo real.</p>
                        </div>
                    </div>
                    <!-- Imagen 3 -->
                    <div class="carousel-item">
                        <img src="{{ asset('website/img/slides/slide_3.jpg') }}" class="d-block w-100" alt="Transforma tu Comunidad">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Transforma tu Comunidad</h5>
                            <p>Tu reporte puede marcar la diferencia. Radar Ciudadano empodera a la ciudadanía para crear un entorno más seguro y organizado.</p>
                        </div>
                    </div>
                </div>

                <!-- Controles del carrusel -->
                <a class="carousel-control-prev" href="#galleryCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Anterior</span>
                </a>
                <a class="carousel-control-next" href="#galleryCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Siguiente</span>
                </a>
            </div>

            <!-- Miniaturas -->
            <div class="gallery-thumbnails mt-4 text-center">
                <img src="{{ asset('website/img/slides/slide_1.jpg') }}" class="img-thumbnail" width="100" alt="Thumbnail 1">
                <img src="{{ asset('website/img/slides/slide_2.jpg') }}" class="img-thumbnail" width="100" alt="Thumbnail 2">
                <img src="{{ asset('website/img/slides/slide_3.jpg') }}" class="img-thumbnail" width="100" alt="Thumbnail 3">
            </div>
        </div>
    </section>
@endsection
