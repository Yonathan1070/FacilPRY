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

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{asset("assets/bsb/css/themes/all-themes.css")}}" rel="stylesheet" />

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
                <a class="navbar-brand" href="{{route("inicio_tester")}}" style="padding: 1px 1px;">
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
                                <li class="body">
                                    <ul class="menu">
                                        @foreach ($notificaciones as $notificacion)
                                            <li>
                                                <a onclick="notificacion({{$notificacion->id}})">
                                                    <div class="icon-circle bg-light-green">
                                                        <i class="material-icons">{{$notificacion->NTF_Icono}}</i>
                                                    </div>
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
                                    <a href="javascript:void(0);">View All Notifications</a>
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
        @extends("theme.bsb.menu")
    </section>

    <section class="content">
        @yield('contenido')
    </section>

    <!-- Jquery Core Js -->
    <script src="{{asset("assets/bsb/plugins/jquery/jquery.min.js")}}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{asset("assets/bsb/plugins/bootstrap/js/bootstrap.js")}}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{asset("assets/bsb/plugins/node-waves/waves.js")}}"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="{{asset("assets/bsb/plugins/jquery-slimscroll/jquery.slimscroll.js")}}"></script>

    <!-- Plugin Js para Validaciones -->
    <script src="{{asset("assets/bsb/plugins/jquery-validation/jquery.validate.js")}}"></script>
    <!-- Mensajes en español -->
    <script src="{{asset("assets/bsb/plugins/jquery-validation/localization/messages_es.js")}}"></script>

    <script src="{{asset("assets/bsb/js/pages/forms/form-validation.js")}}"></script>
    
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
    
    <!-- Custom Js -->
    <script src="{{asset("assets/bsb/js/admin.js")}}"></script>
    <script src="{{asset("assets/bsb/js/pages/tables/jquery-datatable.js")}}"></script>
    
    <!-- Demo Js -->
    <script src="{{asset("assets/bsb/js/demo.js")}}"></script>

    <script src="{{asset("assets/js/funciones.js")}}"></script>
    <script src="{{asset("assets/js/scripts.js")}}"></script>
    
    <script>
        function notificacion(id){
                    $.ajax({
                        dataType: "json",
                        method: "get",
                        url: "/tester/" + id + "/cambio-estado"
                    }).done(function (notif) {
                        if(notif.ruta != null)
                            document.location.replace(notif.ruta);
                    });
                }
    </script>

    @yield('scripts')
</body>

</html>