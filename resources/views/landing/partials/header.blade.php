<div class="site-preloader-wrap">
    <div class="spinner"></div>
</div>
<!-- Inicio de la barra de contacto superior -->
<div class="contact-top">
    <div class="container">
        <div class="contact-info">
            <a>Contáctanos +52 1 55 3912 9260</a>
        </div>
    </div>
</div>
<!-- Fin de la barra de contacto superior -->
<header class="header">
    <div class="primary-header">
        <div class="container">
            <div class="primary-header-inner">
                <div class="header-logo">
                    <a href="{{ route('website.index') }}">
                        <img src="{{ asset('website/img/logos/logo_oficial.png') }}" alt="Indico" style="border-radius: 5px;">
                    </a>
                </div>                
                <div class="header-menu-wrap">
                    <ul class="dl-menu">
                        <li><a href="{{ route('website.index') }}">Inicio</a></li>
                        <li><a href="{{ route('website.servicios') }}">Servicios</a></li>
                        <li><a href="{{ route('website.galeria') }}">Galería</a></li>
                        <li><a href="#">Nosotros</a>
                            <ul>
                                <li><a href="{{ route('website.nosotros') }}">Sobre nosotros</a></li>
                                <li><a href="{{ route('website.nosotros-company') }}">Sobre la compañía</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('website.contacto') }}">Contacto</a></li>
                    </ul>
                </div>
                <div class="header-right">
                    <a class="menu-btn" style="font-size: 14px;" href="{{ route('login') }}">LOGIN</a>

                    <div class="mobile-menu-icon">
                        <div class="burger-menu">
                            <div class="line-menu line-half first-line"></div>
                            <div class="line-menu"></div>
                            <div class="line-menu line-half last-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
