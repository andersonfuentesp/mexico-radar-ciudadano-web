<div class="site-preloader-wrap">
    <div class="spinner"></div>
</div>
<!-- Inicio de la barra de contacto superior -->
<div class="contact-top">
    <div class="container">
        <div class="contact-info">
            <a>¡Descarga la aplicación Gratis y empieza a Reportar Incidencias!</a>
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
                        <img src="{{ asset('website/img/logos/logo_radar.png') }}" width="85"
                            style="margin-top: 5px; margin-bottom: 5px" alt="Indico" style="border-radius: 5px;">
                    </a>
                </div>
                <div class="header-menu-wrap">
                    <ul class="dl-menu">
                        <li><a href="{{ route('website.index') }}"
                                class="{{ request()->routeIs('website.index') ? 'active' : '' }}">Inicio</a></li>
                        <li><a href="{{ route('website.servicios') }}"
                                class="{{ request()->routeIs('website.servicios') ? 'active' : '' }}">Servicios</a></li>
                        <li><a href="{{ route('website.galeria') }}"
                                class="{{ request()->routeIs('website.galeria') ? 'active' : '' }}">Galería</a></li>
                        <li><a href="#"
                                class="{{ request()->routeIs('website.nosotros*') ? 'active' : '' }}">Nosotros</a>
                            <ul>
                                <li><a href="{{ route('website.nosotros') }}"
                                        class="{{ request()->routeIs('website.nosotros') ? 'active' : '' }}">Sobre
                                        nosotros</a></li>
                                <li><a href="{{ route('website.nosotros-company') }}"
                                        class="{{ request()->routeIs('website.nosotros-company') ? 'active' : '' }}">Sobre
                                        la compañía</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('website.contacto') }}"
                                class="{{ request()->routeIs('website.contacto') ? 'active' : '' }}">Contacto</a></li>
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
