<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>@yield('titulo', 'InkBrutalPRY') | {{session()->get('Rol_Nombre')}} | InkBrutalPRY</title>
    <!-- Favicon-->
    <link rel="icon" href="{{asset("assets/bsb/images/ink_logo.png")}}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="{{asset("assets/bsb/css/fontfamilycss.css")}}" rel="stylesheet" type="text/css">
    <link href="{{asset("assets/bsb/css/fontfamilyicon.css")}}" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{asset("assets/bsb/plugins/bootstrap/css/bootstrap.css")}}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{asset("assets/bsb/plugins/node-waves/waves.css")}}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{asset("assets/bsb/plugins/animate-css/animate.css")}}" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="{{asset("assets/bsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css")}}" rel="stylesheet">

    <!-- Custom Css -->
    <link href="{{asset("assets/bsb/css/style.css")}}" rel="stylesheet">

    <link href="{{asset("assets/css/custom.css")}}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{asset("assets/bsb/css/themes/all-themes.css")}}" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link href="{{asset("assets/bsb/plugins/bootstrap-select/css/bootstrap-select.css")}}" rel="stylesheet">
    
    @yield('styles')
</head>

<body class="theme-cyan">
    <!-- Cargador de Página -->
    @include('includes/loader')
    <!-- Fin Cargador de Página -->
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="{{route("inicio_director")}}" style="padding: 1px 1px;">
                    <img src="{{asset("assets/images/ink_logo.png")}}" height="48px" width="200px" /> - {{session()->get('Rol_Nombre')}}
                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Notifications -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">notifications</i>
                            @if ($cantidad != 0)
                                <span class="label-count">{{$cantidad}}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">NOTIFICACIONES</li>
                            <li class="footer">
                                <a onclick="notificacionTodas({{session()->get('Usuario_Id')}})">Marcar todo como leido</a>
                            </li>
                            <li class="body">
                                <ul class="menu">
                                    @foreach ($notificaciones as $notificacion)
                                        <li>
                                            <a onclick="notificacion({{$notificacion->id}})">
                                                @if ($notificacion->NTF_Estado == 0)
                                                    <div class="icon-circle bg-indigo">
                                                        <i class="material-icons">{{$notificacion->NTF_Icono}}</i>
                                                    </div>
                                                @else
                                                    <div class="icon-circle bg-blue">
                                                        <i class="material-icons">{{$notificacion->NTF_Icono}}</i>
                                                    </div>
                                                @endif
                                                <div class="menu-info">
                                                    <h4>{{$notificacion->NTF_Titulo}}</h4>
                                                    @if (\Carbon\Carbon::now()->diffInSeconds($notificacion->NTF_Fecha) < 60)
                                                        <p>{{\Carbon\Carbon::now()->diffInSeconds($notificacion->NTF_Fecha)}} Segundos</p>
                                                    @elseif(\Carbon\Carbon::now()->diffInMinutes($notificacion->NTF_Fecha) < 60)
                                                        <p>{{\Carbon\Carbon::now()->diffInMinutes($notificacion->NTF_Fecha)}} Minutos</p>
                                                    @elseif(\Carbon\Carbon::now()->diffInHours($notificacion->NTF_Fecha) < 24)
                                                        <p>{{\Carbon\Carbon::now()->diffInHours($notificacion->NTF_Fecha)}} Horas</p>
                                                    @else
                                                        <p>{{\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $notificacion->NTF_Fecha)->format('d/m/Y')}}</p>
                                                    @endif
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="footer">
                                <a onclick="limpiarNotificacion({{session()->get('Usuario_Id')}})">Limpiar Bandeja</a>
                            </li>
                            <li class="footer">
                                <a onclick="verTodas()">Ver todas las notificaciones</a>
                            </li>
                        </ul>
                    </li>
                    <!-- #END# Notifications -->
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
            @extends("theme.bsb.2.menu")
    </section>

    <section class="content">
        @yield('contenido')
    </section>
    @if (session()->get('roles') && count(session()->get('roles')) > 1)
        @csrf
        <div class="modal fade" id="modal-seleccionar-rol" data-rol-set="{{empty(session()->get('Rol_Id')) ? 'NO' : 'SI'}}" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Roles del Usuario</h4>
                    </div>
                    <div class="modal-body">
                        <p>Cuentas con más de un rol en InkBrutalPRY, acontinuación seleccione con cual desea ingresar.</p>
                        @foreach (session()->get('roles') as $id => $rol)
                            <li style="list-style: none">
                                <a href="#" class="asignar-rol btn btn-info waves-effect" data-rolid="{{$rol['id']}}" data-rolnombre="{{$rol["RLS_Nombre_Rol"]}}" data-subrolid="{{$rol['RLS_Rol_Id']}}">
                                    {{$rol['RLS_Nombre_Rol']}}
                                </a>
                            </li><br />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Jquery Core Js -->
    <script src="{{asset("assets/bsb/plugins/jquery/jquery.min.js")}}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{asset("assets/bsb/plugins/bootstrap/js/bootstrap.js")}}"></script>

    <!-- Select Plugin Js -->
    <script src="{{asset("assets/bsb/plugins/bootstrap-select/js/bootstrap-select.js")}}"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="{{asset("assets/bsb/plugins/jquery-slimscroll/jquery.slimscroll.js")}}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{asset("assets/bsb/plugins/node-waves/waves.js")}}"></script>

    <!-- Custom Js -->
    <script src="{{asset("assets/bsb/js/admin.js")}}"></script>

    <!-- Autosize Plugin Js -->
    <script src="{{asset("assets/bsb/plugins/autosize/autosize.js")}}"></script>

    <!-- Moment Plugin Js -->
    <script src="{{asset("assets/bsb/plugins/momentjs/moment.js")}}"></script>

    <!-- Demo Js -->
    <script src="{{asset("assets/bsb/js/demo.js")}}"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="{{asset("assets/bsb/plugins/jquery-datatable/jquery.dataTables.js")}}"></script>
    <script src="{{asset("assets/bsb/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js")}}"></script>
    <script src="{{asset("assets/bsb/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js")}}"></script>
    <script src="{{asset("assets/bsb/plugins/jquery-datatable/extensions/export/buttons.flash.min.js")}}"></script>
    <script src="{{asset("assets/bsb/plugins/jquery-datatable/extensions/export/jszip.min.js")}}"></script>
    <script src="{{asset("assets/bsb/plugins/jquery-datatable/extensions/export/pdfmake.min.js")}}"></script>
    <script src="{{asset("assets/bsb/plugins/jquery-datatable/extensions/export/vfs_fonts.js")}}"></script>
    <script src="{{asset("assets/bsb/plugins/jquery-datatable/extensions/export/buttons.html5.min.js")}}"></script>
    <script src="{{asset("assets/bsb/plugins/jquery-datatable/extensions/export/buttons.print.min.js")}}"></script>
    
    <script src="{{asset("assets/bsb/js/pages/tables/jquery-datatable.js")}}"></script>

    <script src="{{asset("assets/bsb/js/pages/ui/tooltips-popovers.js")}}"></script>

    <script src="{{asset("assets/js/funciones.js")}}"></script>
    <script src="{{asset("assets/js/scripts.js")}}"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        function notificacion(id){
            $.ajax({
                dataType: "json",
                method: "get",
                url: "/director/" + id + "/cambio-estado"
            }).done(function (notif) {
                if(notif.ruta != null)
                    document.location.replace(notif.ruta);
            });
        }
        function notificacionTodas(id){
            $.ajax({
                dataType: "json",
                method: "get",
                url: "/director/" + id + "/cambio-estado-todo"
            }).done(function (mensaje) {
                location.reload();
                if(mensaje.mensaje == 'ok') {
                    InkBrutalPRY.notificaciones('Notificaciones marcadas como leidas', 'InkBrutalPRY', 'success');
                }
            });
        }

        function limpiarNotificacion(id){
            $.ajax({
                dataType: "json",
                method: "get",
                url: "/director/" + id + "/limpiar-notificaciones"
            }).done(function (mensaje) {
                location.reload();
            });
        }
        function verTodas(id){
            window.location.href = "{{route('notificaciones_director')}}";
        }
    </script>
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/5e3d9b2f298c395d1ce6c749/default';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
    @yield('scripts')
</body>

</html>