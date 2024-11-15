<section class="widget-section padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-sm-6 sm-padding">
                <div class="widget-content">
                    <a href="#"><img src="{{ asset('website/img/logos/logo-3.png') }}" alt="brand"></a>
                    <p style="text-align: justify;">Transformando el mundo a través de la cartografía avanzada y el
                        desarrollo de sistemas de
                        información geográfica. Nos especializamos en servicios de cartografía, aplicaciones,
                        mapas satelitales y análisis de datos.</p>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 sm-padding">
                <div class="widget-content">
                    <h4>Destacado</h4>
                    <ul class="widget-links" style="list-style-type: disc; margin-left: 20px;">
                        <li><a href="{{ route('website.index') }}">Inicio</a></li>
                        <li>
                            <a href="{{ route('website.servicios') }}">Servicios</a>
                            <ul class="sub-links" style="list-style-type: circle; margin-left: 5px;">
                                <li><a href="{{ route('website.servicios.cartografia') }}">Cartografía</a></li>
                                <li><a href="{{ route('website.servicios.aplicaciones') }}">Aplicaciones</a></li>
                                <li><a href="{{ route('website.servicios.mapas') }}">Mapas Satelitales</a></li>
                                <li><a href="{{ route('website.servicios.analisis') }}">Análisis de Datos</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('website.proyectos') }}">Proyectos</a></li>
                        <li><a href="{{ route('website.contacto') }}">Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 sm-padding" id="contacto-info">
                <div class="widget-content">
                    <h4>Contacto</h4>
                    <p><i class="fas fa-map-marker-alt"></i> Calz. Santo Tomás 110, Santo Tomas, Azcapotzalco, 02020
                        Ciudad de México, CDMX</p>
                    <p><i class="fas fa-envelope"></i> <a
                            href="mailto:contacto@grupoitaai.com">contacto@grupoitaai.com</a></p>
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
                    <p style="text-align: justify;">Sigue nuestras redes sociales para estar al tanto de las últimas
                        noticias, actualizaciones y
                        proyectos destacados.</p>
                    <ul class="social-links"
                        style="list-style: none; padding: 0; display: flex; justify-content: start;">
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a>
                        </li>
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
        <p><a href="">Grupo de investigación y tecnología aplicada AI</a></p>
    </div>
</footer>
