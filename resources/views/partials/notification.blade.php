@section('content_top_nav_right')
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-inbox"></i> <!-- Cambiado a un ícono más moderno -->
            <span class="badge badge-primary navbar-badge">1</span> <!-- Recuento ajustado a 1 -->
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-header">1 Mensaje Nuevo</span>
            <div class="dropdown-divider"></div>
            <!-- Inicio del Mensaje -->
            <a href="#" class="dropdown-item">
                <div class="media">
                    <i class="fas fa-robot fa-2x mr-3"></i> <!-- Ícono de robot en lugar de imagen -->
                    <div class="media-body">
                        <h3 class="dropdown-item-title">
                            Sistema Admin
                            <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                        </h3>
                        <p class="text-sm">¡Bienvenido!</p>
                        <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> Justo ahora</p>
                    </div>
                </div>
            </a>
            <!-- Fin del Mensaje -->
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">Ver Todos los Mensajes</a>
        </div>
    </li>

    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">1</span> <!-- Recuento ajustado a 1 -->
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
            <span class="dropdown-item dropdown-header">1 Notificación</span> <!-- Cambiado a singular -->
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
                <i class="fas fa-robot mr-2"></i> Bienvenido al Sistema <!-- Icono y mensaje de bienvenida -->
                <span class="float-right text-muted text-sm">Justo ahora</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">Ver Todas las Notificaciones</a>
        </div>
    </li>
@endsection
