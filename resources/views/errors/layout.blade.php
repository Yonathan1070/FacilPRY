<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
        <title>@yield('titulo', 'InkBrutalPRY') | {{session()->get('Rol_Nombre')}} | InkBrutalPRY</title>
        <!-- Favicon-->
        <link rel="icon" href="{{asset("assets/bsb/images/ink_logo.png")}}" type="image/x-icon">
    
        <!-- Google Fonts -->
        <link type="text/css" rel="stylesheet" href="{{asset("assets/errors/css/font-family.min.css")}}" />

        <!-- Font Awesome Icon -->
	    <link type="text/css" rel="stylesheet" href="{{asset("assets/errors/css/font-awesome.min.css")}}" />
    
        <!-- Custom stlylesheet -->
        <link type="text/css" rel="stylesheet" href="{{asset("assets/errors/css/style.css")}}" />

        @yield('styles')
    </head>
    
    <body>
        <div id="notfound">
            <div class="notfound-bg"></div>
            <div class="notfound">
                <div class="notfound-404">
                    <h1>@yield('codigo')</h1>
                </div>
                @yield('mensaje')
                <a href="{{route('login')}}" class="home-btn">Ir al inicio</a>
                <div class="notfound-social">
                    <a href="#"><i class="fa fa-facebook"></i></a>
                    <a href="#"><i class="fa fa-twitter"></i></a>
                    <a href="#"><i class="fa fa-pinterest"></i></a>
                    <a href="#"><i class="fa fa-google-plus"></i></a>
                </div>
            </div>
        </div>
    
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