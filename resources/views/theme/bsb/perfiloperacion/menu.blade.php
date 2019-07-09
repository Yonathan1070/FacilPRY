<!-- Barra Lateral Izquierda -->
<aside id="leftsidebar" class="sidebar">
    <!-- Información de Usuario -->
    <div class="user-info">
        <div class="image">
            <img src="{{asset("assets/bsb/images/user.png")}}" width="48" height="48" alt="User" />
        </div>
        <div class="info-container">
        <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{session()->get('Usuario_Nombre_Completo')}}</div>
            <div class="email">{{session()->get('Usuario_Correo')}}</div>
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <li><a href="javascript:void(0);"><i class="material-icons">person</i>Perfil</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{route('logout')}}"><i class="material-icons">input</i>Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Fin Información de Usuario -->
    <!-- Menú -->
    <div class="menu">
        <ul class="list">
            <li class="header">MENÚ DE NAVEGACIÓN</li>
            <li>
                <a href="{{route("inicio_administrador")}}">
                    <i class="material-icons">home</i>
                    <span>Inicio</span>
                </a>
            </li>
            <li>
                <a href="{{route("director_administrador")}}">
                    <i class="material-icons">text_fields</i>
                    <span>Director de Proyectos</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- Fin Menú -->
    <!-- Footer -->
    @include("theme.bsb.perfiloperacion.footer")
    <!-- Fin Footer -->
</aside>
<!-- Fin Barra Lateral Izquierda -->