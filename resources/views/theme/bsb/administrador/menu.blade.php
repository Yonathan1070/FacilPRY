<!-- Barra Lateral Izquierda -->
<aside id="leftsidebar" class="sidebar">
    <!-- Información de Usuario -->
    <div class="user-info">
        <div class="image">
            @if ($datos->USR_Foto_Perfil==null)
                <img src="{{asset("assets/bsb/images/user-lg.ico")}}" width="48" height="48" alt="User" />
            @else
            
                <img src="{{asset('/assets/bsb/images/'.$datos->USR_Foto_Perfil)}}" width="48" height="48" alt="User" />
            @endif
        </div>
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{$datos->USR_Nombre.' '.$datos->USR_Apellido}}</div>
            <div class="email">{{$datos->USR_Correo}}</div>
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <li><a href="{{route('perfil_administrador')}}"><i class="material-icons">person</i>Perfil</a></li>
                    <li><a href="javascript:void(0);"><i class="material-icons">business</i>Empresa</a></li>
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
                <a href="{{route("decisiones_administrador")}}">
                    <i class="material-icons">group_work</i>
                    <span>Decisiones</span>
                </a>
            </li>
            <li>
                <a href="{{route("directores_administrador")}}">
                    <i class="material-icons">assessment</i>
                    <span>Director de Proyectos</span>
                </a>
            </li>
            <li>
                <a href="{{route("roles_administrador")}}">
                    <i class="material-icons">accessibility</i>
                    <span>Roles</span>
                </a>
            </li>
            <li>
                <a href="{{route("permisos_administrador")}}">
                    <i class="material-icons">assignment_ind</i>
                    <span>Permisos</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- Fin Menú -->
    <!-- Footer -->
    @include("theme.bsb.administrador.footer")
    <!-- Fin Footer -->
</aside>
<!-- Fin Barra Lateral Izquierda -->