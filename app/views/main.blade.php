<!DOCTYPE HTML>
<html>
    <head>
        <title>Partes</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.dropotron.min.js"></script>
        <script src="js/skel.min.js"></script>
        <script src="js/skel-layers.min.js"></script>
        <script src="js/init.js"></script>
        <noscript>
            <link rel="stylesheet" href="css/skel.css" />
            <link rel="stylesheet" href="css/style.css" />
            <link rel="stylesheet" href="css/style-desktop.css" />
        </noscript>
        <!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
        
        @yield('head')
        
    </head>
    <body class="no-sidebar">

        <div id="header-wrapper">
            <div id="header" class="container">

                <!-- Logo -->
                @if(Session::has('nombre') && Session::has('apellidos') && Session::has('rol'))
                <a class="icon fa-close salir" href="logout"></a>
                @else
                <h1 id="logo"><a href="{{ URL::to('/') }}">Partes</a></h1>
                @endif
                <p>Registro de partes</p>
                <p><b>{{ Session::get('nombre') }} {{ Session::get('apellidos') }}</b></p>
                <p><b>{{ Session::get('rol') }}</b></p>
                
                
                <!-- Nav -->
                @yield('menu')

            </div>
        </div>

        <!-- Main -->
        <div id="main-wrapper">
            <div id="main" class="container">
                <div class="row">

                    @yield('content')

                </div>
            </div>
        </div>

        <div id="footer-wrapper">
            <div id="copyright" clas="container">
                <ul class="links">
                    <li>Francisco Parralejo V2.0 Enero-2015</li>
                </ul>
            </div>
        </div>
        
    </body>
</html>