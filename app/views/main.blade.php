<!DOCTYPE HTML>
<!--[if lt IE 7 ]><html class="ie ie6" lang="es"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="es"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="es"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="es"> <!--<![endif]-->
    <head>
        <title>Left Sidebar - Strongly Typed by HTML5 UP</title>
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
        
        
        <link href='fullcalendar/fullcalendar.css' rel='stylesheet' />
        <link href='fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
        <script src='fullcalendar/lib/moment.min.js'></script>
<!--        <script src='fullcalendar/lib/jquery.min.js'></script>-->
        <script src='fullcalendar/fullcalendar.min.js'></script>
        
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
                <h1 id="logo"><a href="{{ URL::to('/') }}">Partes FPP</a></h1>
                <p>App para el registro de partes</p>

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

    </body>
</html>