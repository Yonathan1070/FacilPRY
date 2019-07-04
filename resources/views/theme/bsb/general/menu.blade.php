<!-- Barra Lateral Izquierda -->
<aside id="leftsidebar" class="sidebar">
    <!-- Menú -->
    <div class="menu">
        <ul class="list">
            <li class="header">MENÚ DE NAVEGACIÓN</li>
            <li>
                <a href="{{route("inicio")}}">
                    <i class="material-icons">home</i>
                    <span>Inicio</span>
                </a>
            </li>
            <li>
                <a href="{{route("iniciar_sesion")}}">
                    <i class="material-icons">input</i>
                    <span>Iniciar Sesión</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- Fin Menú -->
    <!-- Footer -->
    @include("theme.bsb.general.footer")
    <!-- Fin Footer -->
</aside>
<!-- Fin Barra Lateral Izquierda -->