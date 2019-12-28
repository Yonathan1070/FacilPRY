<!-- Barra Lateral Izquierda -->
<aside id="leftsidebar" class="sidebar">
    <!-- Información de Usuario -->
    <div class="user-info">
        <div class="image">
            @if ($datos->USR_Foto_Perfil_Usuario==null)
                <img src="{{asset("assets/bsb/images/user-lg.ico")}}" width="48" height="48" alt="User" />
            @else
                <img src="{{asset('/assets/bsb/images/'.$datos->USR_Foto_Perfil_Usuario)}}" width="48" height="48" alt="User" />
            @endif
        </div>
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{$datos->USR_Nombre_Usuario.' '.$datos->USR_Apellido_Usuario}}
            </div>
            <div class="email">{{$datos->USR_Correo_Usuario}}</div>
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <li><a href="{{route('perfil_director')}}"><i class="material-icons">person</i>Perfil</a></li>
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
                <a href="{{route("inicio_director")}}">
                    <i class="material-icons">home</i>
                    <span>Inicio</span>
                </a>
            </li>
            <li>
                <a href="{{route("roles_director")}}">
                    <i class="material-icons">accessibility</i>
                    <span>Roles del Sistema</span>
                </a>
            </li>
            <li>
                <a href="{{route("perfil_operacion_director")}}">
                    <i class="material-icons">account_circle</i>
                    <span>Perfil de Operación</span>
                </a>
            </li>
            <li>
                <a href="{{route("clientes_director")}}">
                    <i class="material-icons">account_circle</i>
                    <span>Clientes</span>
                </a>
            </li>
            <li>
                <a href="{{route("proyectos_director")}}">
                    <i class="material-icons">note_add</i>
                    <span>Proyectos</span>
                </a>
            </li>
            <li>
                <a href="{{route("decisiones_director")}}">
                    <i class="material-icons">record_voice_over</i>
                    <span>Decisiones</span>
                </a>
            </li>
            <li>
                <a href="{{route("cobros_director")}}">
                    <i class="material-icons">attach_money</i>
                    <span>Cobros</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- Fin Menú -->
    <!-- Footer -->
    @include("theme.bsb.director.footer")
    <!-- Fin Footer -->
</aside>
<!-- Fin Barra Lateral Izquierda -->