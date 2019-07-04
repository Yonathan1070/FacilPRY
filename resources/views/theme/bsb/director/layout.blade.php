<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>@yield('titulo', 'FacilPRY') | FacilPRY</title>
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

    <link href="{{asset("assets/css/custom.css")}}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{asset("assets/bsb/css/themes/all-themes.css")}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
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
                <a class="navbar-brand" href="{{route("inicio")}}">FacilPRY</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Call Search -->
                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                    <!-- #END# Call Search -->
                    <!-- Notifications -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">notifications</i>
                            <span class="label-count">1</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">NOTIFICACIONES</li>
                            <li class="body">
                                <ul class="menu">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">person_add</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>12 new members joined</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 14 mins ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="javascript:void(0);">Todas las Notificaciones</a>
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
        @include("theme.bsb.director.menu")
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

    <!-- Custom Js -->
    <script src="{{asset("assets/bsb/js/admin.js")}}"></script>

    <script src="{{asset("assets/js/funciones.js")}}"></script>
    <script src="{{asset("assets/js/scripts.js")}}"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @yield('scripts')
</body>

</html>