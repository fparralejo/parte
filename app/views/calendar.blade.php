@extends('main')



@section('head')
<meta http-equiv="PRAGMA" content="NO-CACHE">
<meta http-equiv="EXPIRES" content="-1">

<link type="text/css" rel="stylesheet" media="all" href="{{ URL::asset('css/estilos.css') }}">
<script>

</script>
<style>
</style>


@stop

@section('menu')

<nav id="nav">
    <ul>
        <li><a class="icon fa-home" href="logout"><span>Salir</span></a></li>
    </ul>



    <!--    <ul>
            <li><a class="icon fa-home" href="index.html"><span>Introduction</span></a></li>
            <li>
                <a href="" class="icon fa-bar-chart-o"><span>Dropdown</span></a>
                <ul>
                    <li><a href="#">Lorem ipsum dolor</a></li>
                    <li><a href="#">Magna phasellus</a></li>
                    <li><a href="#">Etiam dolore nisl</a></li>
                    <li>
                        <a href="">Phasellus consequat</a>
                        <ul>
                            <li><a href="#">Magna phasellus</a></li>
                            <li><a href="#">Etiam dolore nisl</a></li>
                            <li><a href="#">Phasellus consequat</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Veroeros feugiat</a></li>
                </ul>
            </li>
            <li><a class="icon fa-cog" href="left-sidebar.html"><span>Left Sidebar</span></a></li>
            <li><a class="icon fa-retweet" href="right-sidebar.html"><span>Right Sidebar</span></a></li>
            <li><a class="icon fa-sitemap" href="no-sidebar.html"><span>No Sidebar</span></a></li>-->
</ul>
</nav>
@stop




@section('content')

<div class="calendario_ajax">
    <div class="cal"></div><div id="mask"></div>
</div>

<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/localization/messages_es.js "></script>

<script>
    function generar_calendario(mes, anio)
    {
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");
        $.ajax({
            type: "GET",
            url: "generar_calendario",
            cache: false,
            data: {mes: mes, anio: anio, accion: "generar_calendario"}
        }).done(function (respuesta)
        {
            agenda.html(respuesta);
        });
    }

    function formatDate(input) {
        var datePart = input.match(/\d+/g),
                year = datePart[0].substring(2),
                month = datePart[1], day = datePart[2];
        return day + '-' + month + '-' + year;
    }
    
    function evento(dia,mes,anio){
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");
        
        $.ajax({
            type: "GET",
            url: "evento_nuevo",
            data: {dia: dia,mes: mes, anio: anio, accion: "evento_nuevo"}
        }).done(function (respuesta)
        {
            agenda.html(respuesta);
        });
    }
    
    function main(fecha){
        window.location.href='main?fecha='+fecha;
    }
    
    //doy de alta el evento
    function darAltaEvento(){
        var eventoTxt = $("#evento_titulo").val();
        var fecha = $("#evento_fecha").val();
        var horas = $("#evento_horas").val();
        var tipo = $("#tipo").val();

        //comprobamos que haya texto en '#evento_titulo'
        if(eventoTxt!=='' && horas!=='' && tipo!==''){
            $('#formNuevo').hide();
            $('#dandoAlta').show();

            $.ajax({
                type: "GET",
                url: "guardar_evento",
                cache: false,
                data: {evento: eventoTxt, fecha: fecha, horas: horas, tipo: tipo, accion: "guardar_evento"}
            }).done(function (respuesta2)
            {
                document.getElementById('evento_titulo').value='';
                document.getElementById('evento_fecha').value='';
                $("#respuesta_accion").html(respuesta2);
                $('#dandoAlta').hide();
                //redibujar toda la pantalla de nuevo con el nuevo evento guardado
                setTimeout(function ()
                {
                    var fechaA=fecha.split('-');
                    var dia=fechaA[0];
                    var mes=fechaA[1];
                    var anio=fechaA[2];
                    evento(dia,mes,anio);
                }, 3000);
            });
        }else{
            $("#respuesta_accion").html("<p class='rojo'>Se debe introducir un texto.</p>");
            //borrar el texto a los 3 segundos
            setTimeout(function ()
            {
                $("#respuesta_accion").html("");
            }, 3000);
        }
        
    }
    
    function borrarEvento(id,anio,mes,dia){
        if (confirm("¿Desea borrar el parte?"))
        {
            //cargo gif accion
            $("#evIcono"+id).html("<img src='{{ URL::asset('img/loading.gif') }}' height='10' width='10'>");
            $('#formNuevo').hide();
            $('#dandoAlta').show();

            $.ajax({
                type: "GET",
                url: "borrar_evento",
                cache: false,
                data: {id: id, accion: "borrar_evento"}
            }).done(function (respuesta2)
            {
                $("#respuesta_accion").html(respuesta2);
                $('#dandoAlta').hide();
                //redibujar toda la pantalla de nuevo con el nuevo evento guardado
                setTimeout(function ()
                {
                    evento(dia,mes,anio);
                }, 3000);
            });
        
        }
    }
    
    function cambiaMes(objeto){
        var datos = $(objeto).attr("rel");
        var nueva_fecha = datos.split("-");
        generar_calendario(nueva_fecha[1], nueva_fecha[0]);
    }
    
    function cambiaMesAnio(){
        var anio = $("#anioc").val();
        var mes = $("#mesc").val();
        generar_calendario(mes, anio);
    }
    
    function listarMes(mes,anio){
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");
        $.ajax({
            type: "GET",
            url: "listarMes",
            cache: false,
            data: {mes: mes, anio: anio, accion: "listarMes"}
        }).done(function (respuesta)
        {
            agenda.html(respuesta);
        });
    }
    
    function editarParte(id){
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");
        $.ajax({
            type: "GET",
            url: "editarParte",
            cache: false,
            data: {IdParte: id, accion: "editarParte"}
        }).done(function (respuesta)
        {
            agenda.html(respuesta);
        });
    }

    function editarParteOK(){
        var eventoTxt = $("#evento_titulo").val();
        var fecha = $("#evento_fecha").val();
        var horas = $("#evento_horas").val();
        var tipo = $("#tipo").val();
        var IdParte = $("#IdParte").val();

        //comprobamos que haya texto en '#evento_titulo'
        if(eventoTxt!=='' && horas!=='' && tipo!==''){
            $('#formNuevo').hide();
            $('#dandoAlta').show();

            $.ajax({
                type: "GET",
                url: "editarParteOK",
                cache: false,
                data: {IdParte: IdParte, evento: eventoTxt, fecha: fecha, horas: horas, tipo: tipo, accion: "editarParteOK"}
            }).done(function (respuesta2)
            {
                document.getElementById('evento_titulo').value='';
                document.getElementById('evento_fecha').value='';
                $("#respuesta_accion").html(respuesta2);
                $('#dandoAlta').hide();
                //redibujar toda la pantalla de nuevo con el nuevo evento guardado
                setTimeout(function ()
                {
                    var fechaA=fecha.split('-');
                    var dia=fechaA[0];
                    var mes=fechaA[1];
                    var anio=fechaA[2];
                    evento(dia,mes,anio);
                }, 3000);
            });
        }else{
            $("#respuesta_accion").html("<p class='rojo'>Se debe introducir un texto.</p>");
            //borrar el texto a los 3 segundos
            setTimeout(function ()
            {
                $("#respuesta_accion").html("");
            }, 3000);
        }
    }


    $(document).ready(function ()
    {
        <?php
        $mes=Input::get('mes');
        $anio=Input::get('anio');
        if(Input::get('fecha')<>''){
            $fecha=explode('-',Input::get('fecha'));
            $mes=$fecha[1];
            $anio=$fecha[2];
        }
        ?>
        /* GENERAMOS CALENDARIO */
        generar_calendario("<?php echo $mes; ?>", "<?php echo $anio; ?>");
    });
    
</script>


@stop