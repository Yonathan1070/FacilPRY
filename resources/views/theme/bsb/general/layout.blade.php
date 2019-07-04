<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>@yield('titulo', 'FacilPRY') | FacilPRY</title>
    <!-- Favicon-->
    <link rel="icon" href="{{asset("assets/images/favicon.ico")}}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{asset("assets/plugins/bootstrap/css/bootstrap.css")}}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{asset("assets/plugins/node-waves/waves.css")}}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{asset("assets/plugins/animate-css/animate.css")}}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{asset("assets/css/style.css")}}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{asset("assets/css/themes/all-themes.css")}}" rel="stylesheet" />

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
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        @include("theme.bsb.general.menu")
    </section>

    <section class="content">
        @yield('contenido')
    </section>

    <!-- Jquery Core Js -->
    <script src="{{asset("assets/plugins/jquery/jquery.min.js")}}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{asset("assets/plugins/bootstrap/js/bootstrap.js")}}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{asset("assets/plugins/node-waves/waves.js")}}"></script>

    <!-- Custom Js -->
    <script src="{{asset("assets/js/admin.js")}}"></script>

    @yield('scripts')
</body>

</html>