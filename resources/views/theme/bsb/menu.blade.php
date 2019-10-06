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
                    {{$datos->USR_Nombres_Usuario.' '.$datos->USR_Apellidos_Usuario}}</div>
                <div class="email">{{$datos->USR_Correo_Usuario}}</div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="{{route('perfil_administrador')}}"><i class="material-icons">person</i>Perfil</a></li>
                        <li><a href="{{route('empresa_administrador')}}"><i class="material-icons">business</i>Empresa</a></li>
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
                @foreach ($menusComposer as $key => $item)
                    @include("theme.bsb.menu-item", ["item" => $item])
                @endforeach
            </ul>
        </div>
        <!-- Fin Menú -->
        <!-- Footer -->
        @include("theme.bsb.administrador.footer")
        <!-- Fin Footer -->
    </aside>
    <!-- Fin Barra Lateral Izquierda -->