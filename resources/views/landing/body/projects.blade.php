<section class="projects-section padding">
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-lg-8 col-md-6">
                <div class="section-heading mb-40">
                    <span>Proyectos Destacados</span>
                    <h2>Explora nuestros proyectos más innovadores en Grupo de Investigación y Tecnología Aplicada AI.
                    </h2>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 text-right">
                <a href="{{ route('website.proyectos') }}" class="default-btn">Ver Todos los Proyectos</a>
            </div>
        </div>
        <div id="projects-carousel" class="projects-carousel owl-carousel">
            <!-- Proyecto 1 -->
            <div class="project-item">
                <img src="{{ asset('website/img/projects/project-1.jpg') }}" alt="Optimización de Rutas de Entrega">
                <div class="overlay"></div>
                <a href="{{ asset('website/img/projects/project-1.jpg') }}" class="view-icon img-popup"
                    data-gall="project">
                    <i class="fas fa-expand"></i>
                </a>
                <div class="projects-content">
                    <a href="{{ route('website.proyectos') }}" class="category">Logística</a>
                    <h3><a href="{{ route('website.proyectos') }}" class="tittle">Optimización de Rutas de Entrega para Empresa de Logística</a>
                    </h3>
                    <p style="text-align: justify;">Colaboración para optimizar operaciones de entrega con una solución que integra análisis de datos
                        y seguimiento en tiempo real.</p>
                </div>
            </div>
            <!-- Proyecto 2 -->
            <div class="project-item">
                <img src="{{ asset('website/img/projects/project-2.jpg') }}" alt="Gestión de Ventas en Ruta">
                <div class="overlay"></div>
                <a href="{{ asset('website/img/projects/project-2.jpg') }}" class="view-icon img-popup"
                    data-gall="project">
                    <i class="fas fa-expand"></i>
                </a>
                <div class="projects-content">
                    <a href="{{ route('website.proyectos') }}" class="category">Ventas Directas</a>
                    <h3><a href="{{ route('website.proyectos') }}" class="tittle">Desarrollo de Aplicación Móvil para Gestión de Ventas en
                            Ruta</a></h3>
                    <p style="text-align: justify;">Aplicación móvil personalizada para agilizar las ventas directas y optimizar la entrega, con
                        acceso a datos en tiempo real.</p>
                </div>
            </div>
            <!-- Proyecto 3 -->
            <div class="project-item">
                <img src="{{ asset('website/img/projects/project-3.jpg') }}" alt="Plataforma Web GIS">
                <div class="overlay"></div>
                <a href="{{ asset('website/img/projects/project-3.jpg') }}" class="view-icon img-popup"
                    data-gall="project">
                    <i class="fas fa-expand"></i>
                </a>
                <div class="projects-content">
                    <a href="{{ route('website.proyectos') }}" class="category">Gobierno Local</a>
                    <h3><a href="{{ route('website.proyectos') }}" class="tittle">Plataforma Web GIS para Gobierno Local</a></h3>
                    <p style="text-align: justify;">Plataforma SIG para mejorar la planificación urbana y gestión de recursos, facilitando la
                        participación ciudadana con datos geoespaciales.</p>
                </div>
            </div>
            <div class="project-item">
                <img src="{{ asset('website/img/projects/project-4.jpg') }}">
                <div class="overlay"></div>
                <div class="projects-content">
                    <p style="text-align: justify;">¿Tienes un proyecto en mente? Nos enorgullece haber colaborado en proyectos innovadores y ofrecer soluciones de alta calidad. ¡Contáctanos hoy para discutir cómo podemos ayudarte!</p>
                    <a href="{{ route('website.contacto') }}" class="default-btn mt-3">Contáctanos</a>
                </div>
            </div>
        </div>
    </div>
</section>
