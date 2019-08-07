<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>@yield('titulo', 'FacilPRY') | {{session()->get('Rol_Nombre')}} | FacilPRY</title>
    <!-- Favicon-->
    <link rel="icon" href="{{asset("assets/bsb/images/favicon.ico")}}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{asset("assets/bsb/plugins/bootstrap/css/bootstrap.css")}}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{asset("assets/bsb/plugins/node-waves/waves.css")}}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{asset("assets/bsb/plugins/animate-css/animate.css")}}" rel="stylesheet" />

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
                <a class="navbar-brand" href="{{route("inicio_finanzas")}}">FacilPRY - {{session()->get('Rol_Nombre')}}</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">                                            
                    <!-- Notifications -->
                    @include('includes.notificaciones')
                    <!-- #END# Notifications -->
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        @extends("theme.bsb.finanzas.menu")
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

    <!-- Custom Js -->
    <script src="{{asset("assets/bsb/js/admin.js")}}"></script>

    <!-- Demo Js -->
    <script src="{{asset("assets/bsb/js/demo.js")}}"></script>

    @yield('scripts')
</body>

</html>