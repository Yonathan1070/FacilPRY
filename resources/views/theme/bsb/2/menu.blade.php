<!-- Barra Lateral Izquierda -->
<aside id="leftsidebar" class="sidebar">
    <!-- Información de Usuario -->
    <div class="user-info">
        <div class="image">
            @if ($datos->USR_Foto_Perfil_Usuario==null)
                <img src="{{asset("assets/bsb/images/user-lg.ico")}}" width="48" height="48" alt="User" />
            @else
            <img src="{{route('foto_perfil', ['id' => session()->get('Usuario_Id')])}}" width="48" height="48" alt="User" />
            @endif
        </div>
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{$datos->USR_Nombres_Usuario.' '.$datos->USR_Apellidos_Usuario}}</div>
            <div class="email">{{$datos->USR_Correo_Usuario}}</div>
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <li><a href="{{route('perfil')}}"><i class="material-icons">person</i>Perfil</a></li>
                    @if (session()->get('Rol_Nombre')=='Administrador')
                        <li><a href="{{route('empresa_administrador')}}"><i class="material-icons">business</i>Empresa</a></li>
                    @endif
                    <li role="separator" class="divider"></li>
                    @if (session()->get('roles') && count(session()->get('roles')) > 1)
                        <li><a href="#" class="cambiar-rol"><i class="material-icons">autorenew</i>Cambiar de Rol</a></li>
                    @endif
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
                    <i class="material-icons">pie_chart</i>
                    <span>Métricas</span>
                </a>
            </li>
            @include("theme.bsb.menu")
        </ul>
    </div>
    <!-- Fin Menú -->
    <!-- Footer -->
    @include('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.footer')
    <!-- Fin Footer -->
</aside>
<!-- Fin Barra Lateral Izquierda -->