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
function generar_calendario(mes,anio)
{
        var agenda=$(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");
        $.ajax({
                type: "GET",
                url: "generar_calendario",
                cache: false,
                data: { mes:mes,anio:anio,accion:"generar_calendario" }
        }).done(function( respuesta ) 
        {
                agenda.html(respuesta);
        });
}

function formatDate (input) {
        var datePart = input.match(/\d+/g),
        year = datePart[0].substring(2),
        month = datePart[1], day = datePart[2];
        return day+'-'+month+'-'+year;
}

        $(document).ready(function()
        {
                /* GENERAMOS CALENDARIO CON FECHA DE HOY */
                generar_calendario("<?php Input::get("mes"); ?>","<?php Input::get("anio"); ?>");


                /* AGREGAR UN EVENTO */
                //BORRAR, COMPROBAR ANTES CLARO
                $(document).on("click",'a.add',function(e) 
                {
                        e.preventDefault();
                        var id = $(this).data('evento');
                        var fecha = $(this).attr('rel');

                        $('#mask').fadeIn(1000).html("<div id='nuevo_evento' class='window' rel='"+fecha+"'>Agregar un evento el "+formatDate(fecha)+"</h2><a href='#' class='close' rel='"+fecha+"'><img src='{{ URL::asset('img/delete.png') }}' height='18' width='18'>&nbsp;</a><div id='respuesta_form'></div><form class='formeventos'><input type='text' name='evento_titulo' id='evento_titulo' class='required'><input type='button' name='Enviar' value='Guardar' class='enviar'><input type='hidden' name='evento_fecha' id='evento_fecha' value='"+fecha+"'></form></div>");
                });

                /* LISTAR EVENTOS DEL DIA */
                $(document).on("click",'a.modal',function(e) 
                {
                        e.preventDefault();
                        var fecha = $(this).attr('rel');
                        var nuevoHtml="<form class='formeventos'><input type='text' name='evento_titulo' id='evento_titulo' class='required'><input type='button' name='Enviar' value='Guardar' class='enviar'><input type='hidden' name='evento_fecha' id='evento_fecha' value='"+fecha+"'></form>";
                        

                        $('#mask').fadeIn(1000).html("<div id='nuevo_evento' class='window' rel='"+fecha+"'>Eventos del "+formatDate(fecha)+"</h2><a href='#' class='close' rel='"+fecha+"'><img src='{{ URL::asset('img/delete.png') }}' height='18' width='18'>&nbsp;</a>"+nuevoHtml+"<div id='respuesta'></div><div id='respuesta_form'></div></div>");
                        $.ajax({
                                type: "GET",
                                url: "listar_evento",
                                cache: false,
                                data: { fecha:fecha,accion:"listar_evento" }
                        }).done(function( respuesta ) 
                        {
                                $("#respuesta_form").html(respuesta);
                        });

                });

                $(document).on("click",'.close',function (e) 
                {
                        e.preventDefault();
                        $('#mask').fadeOut();
                        setTimeout(function() 
                        { 
                                var fecha=$(".window").attr("rel");
                                var fechacal=fecha.split("-");
                                generar_calendario(fechacal[1],fechacal[0]);
                        }, 500);
                });

                //guardar evento
                $(document).on("click",'.enviar',function (e) 
                {
                        e.preventDefault();
                        if ($("#evento_titulo").valid()==true)
                        {
                                $("#respuesta_form").html("<img src='{{ URL::asset('img/loading.gif') }}'>");
                                var evento=$("#evento_titulo").val();
                                var fecha=$("#evento_fecha").val();
                                $.ajax({
                                        type: "GET",
                                        url: "guardar_evento",
                                        cache: false,
                                        data: { evento:evento,fecha:fecha,accion:"guardar_evento" }
                                }).done(function( respuesta2 ) 
                                {
                                        $("#respuesta_form").html(respuesta2);
                                        $(".formeventos,.close").hide();
                                        setTimeout(function() 
                                        { 
                                                $('#mask').fadeOut('fast');
                                                var fechacal=fecha.split("-");
                                                generar_calendario(fechacal[1],fechacal[0]);
                                        }, 3000);
                                });
                        }
                });

                //eliminar evento
                $(document).on("click",'.eliminar_evento',function (e) 
                {
                        e.preventDefault();
                        var current_p=$(this);
                        $("#respuesta").html("<img src='{{ URL::asset('img/loading.gif') }}'>");
                        var id=$(this).attr("rel");
                        $.ajax({
                                type: "GET",
                                url: "borrar_evento",
                                cache: false,
                                data: { id:id,accion:"borrar_evento" }
                        }).done(function( respuesta2 ) 
                        {
                                $("#respuesta").html(respuesta2);
                                current_p.parent("p").fadeOut();
                        });
                });

                $(document).on("click",".anterior,.siguiente",function(e)
                {
                        e.preventDefault();
                        var datos=$(this).attr("rel");
                        var nueva_fecha=datos.split("-");
                        generar_calendario(nueva_fecha[1],nueva_fecha[0]);
                });

        });
        </script>


@stop