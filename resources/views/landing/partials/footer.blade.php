<section class="widget-section padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-sm-6 sm-padding">
                <div class="widget-content">
                    <a href="#"><img src="{{ asset('website/img/logos/logo_footer.png') }}" alt="Radar Ciudadano"></a>
                    <p style="text-align: justify;">
                        Empoderamos a la ciudadanía al facilitar reportes de incidencias georreferenciados.
                        Conecta con tu comunidad y ten al gobierno en tus manos. Innovamos con mapas interactivos y
                        tecnología avanzada para transformar la manera en que vivimos.
                    </p>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 sm-padding">
                <div class="widget-content">
                    <h4>Destacado</h4>
                    <ul class="widget-links" style="list-style-type: disc; margin-left: 20px;">
                        <li><a href="{{ route('website.index') }}">Inicio</a></li>
                        <li><a href="{{ route('website.servicios') }}" class="{{ request()->routeIs('website.servicios') ? 'active' : '' }}">Servicios</a></li>
                        <li><a href="{{ route('website.galeria') }}" class="{{ request()->routeIs('website.galeria') ? 'active' : '' }}">Galería</a></li>
                        <li><a href="#" class="{{ request()->routeIs('website.nosotros*') ? 'active' : '' }}">Nosotros</a>
                            <ul class="sub-links" style="list-style-type: circle; margin-left: 5px;">
                                <li><a href="{{ route('website.nosotros') }}" class="{{ request()->routeIs('website.nosotros') ? 'active' : '' }}">Sobre nosotros</a></li>
                                <li><a href="{{ route('website.nosotros-company') }}" class="{{ request()->routeIs('website.nosotros-company') ? 'active' : '' }}">Sobre la compañía</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('website.contacto') }}">Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 sm-padding" id="contacto-info">
                <div class="widget-content">
                    <h4>Contacto</h4>
                    <p><i class="fas fa-map-marker-alt"></i> Calz. Santo Tomás 110, Santo Tomas, Azcapotzalco, 02020 Ciudad de México, CDMX</p>
                    <p><i class="fas fa-envelope"></i> <a href="mailto:contacto@radarciudadano.mx">contacto@radarciudadano.mx</a></p>
                    <p><i class="fas fa-phone"></i> <a href="tel:+525539129260">+52 1 55 3912 9260</a></p>
                    <p id="horario-atencion">
                        <i class="fas fa-clock"></i>
                        Horario de Atención:
                        <br>
                        <span>Lunes a Viernes: 9:00 AM - 6:00 PM</span>
                        <br>
                        <span>Sábado y Domingo: Cerrado</span>
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 sm-padding">
                <div class="widget-content">
                    <h4>Conéctate con nosotros</h4>
                    <p style="text-align: justify;">
                        Sigue nuestras redes sociales y participa en la construcción de una comunidad más segura y conectada.
                        Descubre noticias, actualizaciones y proyectos que marcan la diferencia.
                    </p>
                    <ul class="social-links" style="list-style: none; padding: 0; display: flex; justify-content: start;">
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer-section align-center">
    <div class="container">
        <p>
            <a href="https://www.radarciudadano.mx">Radar Ciudadano</a> © {{ date('Y') }}. Todos los derechos reservados.
        </p>
    </div>
</footer>
