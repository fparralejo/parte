@extends('main')



@section('head')
<!--<meta http-equiv="PRAGMA" content="NO-CACHE">
<meta http-equiv="EXPIRES" content="-1">-->

<link type="text/css" rel="stylesheet" media="all" href="{{ URL::asset('css/estilos.css') }}">
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
        var extras = $("#evento_extras").val();
        var tipo = $("#tipo").val();

        //comprobamos que haya texto en '#evento_titulo'
        var comprobacionEventoTxt = false;
        if(eventoTxt !== ''){
            comprobacionEventoTxt = true;
        }
        var comprobacionHoras = false;
        if(horas === '0' && extras === '0'){
        }else{
            comprobacionHoras = true;
        }    

        if(comprobacionEventoTxt === false){
            $("#respuesta_accion").html("<p class='rojo'>Se debe introducir un texto.</p>");
            //borrar el texto a los 3 segundos
            setTimeout(function ()
            {
                $("#respuesta_accion").html("");
            }, 3000);
        }else
        if(comprobacionHoras === false){
            $("#respuesta_accion").html("<p class='rojo'>Horas y Extras no pueden ser 0 a la vez.</p>");
            //borrar el texto a los 3 segundos
            setTimeout(function ()
            {
                $("#respuesta_accion").html("");
            }, 3000);
        }else{
            $('#formNuevo').hide();
            $('#dandoAlta').show();

            $.ajax({
                type: "GET",
                url: "guardar_evento",
                cache: false,
                data: {evento: eventoTxt, fecha: fecha, horas: horas, extras: extras, tipo: tipo, accion: "guardar_evento"}
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
        }

//        if((comprobacionEventoTxt === true) && (comprobacionHoras === true)){
//            $('#formNuevo').hide();
//            $('#dandoAlta').show();
//
//            $.ajax({
//                type: "GET",
//                url: "guardar_evento",
//                cache: false,
//                data: {evento: eventoTxt, fecha: fecha, horas: horas, extras: extras, tipo: tipo, accion: "guardar_evento"}
//            }).done(function (respuesta2)
//            {
//                document.getElementById('evento_titulo').value='';
//                document.getElementById('evento_fecha').value='';
//                $("#respuesta_accion").html(respuesta2);
//                $('#dandoAlta').hide();
//                //redibujar toda la pantalla de nuevo con el nuevo evento guardado
//                setTimeout(function ()
//                {
//                    var fechaA=fecha.split('-');
//                    var dia=fechaA[0];
//                    var mes=fechaA[1];
//                    var anio=fechaA[2];
//                    evento(dia,mes,anio);
//                }, 3000);
//            });
//        }else{
//            $("#respuesta_accion").html("<p class='rojo'>Se debe introducir un texto.</p>");
//            //borrar el texto a los 3 segundos
//            setTimeout(function ()
//            {
//                $("#respuesta_accion").html("");
//            }, 3000);
//        }
        
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
        var horas = $("#evento_horas_e").val();
        var extras = $("#evento_extras_e").val();
        var tipo = $("#tipo").val();
        var IdParte = $("#IdParte").val();

        //comprobamos que haya texto en '#evento_titulo'
        var comprobacionEventoTxt = false;
        if(eventoTxt !== ''){
            comprobacionEventoTxt = true;
        }
        var comprobacionHoras = false;
        if(horas === '0' && extras === '0'){
        }else{
            comprobacionHoras = true;
        }    

        if(comprobacionEventoTxt === false){
            $("#respuesta_accion").html("<p class='rojo'>Se debe introducir un texto.</p>");
            //borrar el texto a los 3 segundos
            setTimeout(function ()
            {
                $("#respuesta_accion").html("");
            }, 3000);
        }else
        if(comprobacionHoras === false){
            $("#respuesta_accion").html("<p class='rojo'>Horas y Extras no pueden ser 0 a la vez.</p>");
            //borrar el texto a los 3 segundos
            setTimeout(function ()
            {
                $("#respuesta_accion").html("");
            }, 3000);
        }else{
            $('#formNuevo').hide();
            $('#dandoAlta').show();

            $.ajax({
                type: "GET",
                url: "editarParteOK",
                cache: false,
                data: {IdParte: IdParte, evento: eventoTxt, fecha: fecha, horas: horas, extras: extras, tipo: tipo, accion: "editarParteOK"}
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
        }
    }

    function buscar(){
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");
        $.ajax({
            type: "GET",
            url: "buscar",
            cache: false,
            data: {accion: "buscar"}
        }).done(function (respuesta)
        {
            agenda.html(respuesta);
        });
    }



    function buscarOK(){
        var buscar = $("#buscar_txt_b").val();
        
        if(buscar.length>0){
            var agenda = $(".cal");
            agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");

            $.ajax({
                type: "GET",
                url: "buscarOK",
                cache: false,
                data: {buscar: buscar, accion: "buscarOK"}
            }).done(function (respuesta2)
            {
                $(".cal").html(respuesta2);
            });
        }else{
            alert('Minimo de 1 caracter');
        }
    }
    
    function prepararListado(){
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");

        $.ajax({
            type: "GET",
            url: "listadoTipo",
            cache: false,
            data: {accion: "listadoTipo"}
        }).done(function (respuesta)
        {
            $(".cal").html(respuesta);
        });
    }
    
    function listadoTipoOK(){
        var tipo = $("#tipo").val();
        var campo1 = $("#campo1").val();
        var anio = $("#anio").val();
        

        if(campo1.length > 0){
            var agenda = $(".cal");
            agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");
        
            $.ajax({
                type: "GET",
                url: "listadoTipoOK",
                cache: false,
                data: {tipo: tipo, campo1: campo1, anio: anio, accion: "listadoTipoOK"}
            }).done(function (respuesta)
            {
                $(".cal").html(respuesta);
            });
        }else{
            alert('Palabra a Buscar mínimo de 1 carácter');
        }    
    }
    
    function listadoTrab(){
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");

        $.ajax({
            type: "GET",
            url: "listadoTrab",
            cache: false,
            data: {accion: "listadoTrab"}
        }).done(function (respuesta)
        {
            $(".cal").html(respuesta);
        });
    }
    
    function editarTrabajador(id){
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");

        $.ajax({
            type: "GET",
            url: "editarTrab",
            cache: false,
            data: {id: id, accion: "editarTrab"}
        }).done(function (respuesta)
        {
            $(".cal").html(respuesta);
        });
    }
    
    function editarTrabajadorOK(){
        var id = $("#Id").val();
        var nombre = $("#nombre").val();
        var apellidos = $("#apellidos").val();
        var rol = $("#rol").val();
        var nick = $("#nick").val();
        var pass = $("#password").val();
        
        //comprobamos que haya texto en '#evento_titulo'
        if(nombre!=='' && apellidos!=='' && nick!=='' && pass!==''){
            $('#formNuevo').hide();
            $('#dandoAlta').show();

            $.ajax({
                type: "GET",
                url: "editarTrabOK",
                cache: false,
                data: {id: id, nombre: nombre, apellidos: apellidos, rol: rol, nick: nick, pass: pass, accion: "editarTrabOK"}
            }).done(function (respuesta2)
            {
                $("#respuesta_accion").html(respuesta2);
                $('#dandoAlta').hide();
                //redibujar toda la pantalla de nuevo con el nuevo evento guardado
                setTimeout(function ()
                {
                    listadoTrab();
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

    function borrarTrabajadorOK(id){
        if (confirm("¿Desea borrar el trabajador?"))
        {
            //cargo gif accion
            $('#formNuevo').hide();
            $('#dandoAlta').show();

            $.ajax({
                type: "GET",
                url: "borrarTrabajadorOK",
                cache: false,
                data: {id: id, accion: "borrarTrabajadorOK"}
            }).done(function (respuesta2)
            {
                $("#respuesta_accion").html(respuesta2);
                $('#dandoAlta').hide();
                //redibujar toda la pantalla de nuevo con el nuevo evento guardado
                setTimeout(function ()
                {
                    listadoTrab();
                }, 3000);
            });
        }
    }
    
    function altaTrab(){
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");

        $.ajax({
            type: "GET",
            url: "altaTrab",
            cache: false,
            data: {accion: "altaTrab"}
        }).done(function (respuesta)
        {
            $(".cal").html(respuesta);
        });
    }
    
    function altaTrabajadorOK(){
        var nombre = $("#nombre").val();
        var apellidos = $("#apellidos").val();
        var rol = $("#rol").val();
        var nick = $("#nick").val();
        var pass = $("#password").val();
        
        //comprobamos que haya texto en '#evento_titulo'
        if(nombre!=='' && apellidos!=='' && nick!=='' && pass!==''){
            $('#formNuevo').hide();
            $('#dandoAlta').show();

            $.ajax({
                type: "GET",
                url: "altaTrabOK",
                cache: false,
                data: {nombre: nombre, apellidos: apellidos, rol: rol, nick: nick, pass: pass, accion: "altaTrabOK"}
            }).done(function (respuesta2)
            {
                $("#respuesta_accion").html(respuesta2);
                $('#dandoAlta').hide();
                //redibujar toda la pantalla de nuevo con el nuevo evento guardado
                setTimeout(function ()
                {
                    listadoTrab();
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
    
    function ayuda(){
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");

        $.ajax({
            type: "GET",
            url: "ayuda",
            cache: false,
            data: {accion: "ayuda"}
        }).done(function (respuesta)
        {
            $(".cal").html(respuesta);
        });
    }
    
    function totalHoras(){
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");

        $.ajax({
            type: "GET",
            url: "totalHoras",
            cache: false,
            data: {accion: "ayuda"}
        }).done(function (respuesta)
        {
            $(".cal").html(respuesta);
        });
    }
    
    function totalHorasOK(){
        var anio = $("#anio").val();
        
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");

        $.ajax({
            type: "GET",
            url: "totalHorasOK",
            cache: false,
            data: {anio: anio, accion: "totalHorasOK"}
        }).done(function (respuesta)
        {
            $(".cal").html(respuesta);
        });
    }
    
    function altaTipo(){
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");

        $.ajax({
            type: "GET",
            url: "altaTipo",
            cache: false,
            data: {accion: "altaTipo"}
        }).done(function (respuesta)
        {
            $(".cal").html(respuesta);
        });
    }

    function altaTipoOK(){
        var nombre = $("#nombre").val();
        
        //comprobamos que haya texto en '#evento_titulo'
        if(nombre!==''){
            $('#formNuevo').hide();
            $('#dandoAlta').show();

            $.ajax({
                type: "GET",
                url: "altaTipoOK",
                cache: false,
                data: {nombre: nombre, accion: "altaTipoOK"}
            }).done(function (respuesta2)
            {
                $("#respuesta_accion").html(respuesta2);
                $('#dandoAlta').hide();
                //redibujar toda la pantalla de nuevo con el nuevo evento guardado
                setTimeout(function ()
                {
                    $("#respuesta_accion").html('');
                    listadoTipo();
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

    function listadoTipo(){
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");

        $.ajax({
            type: "GET",
            url: "listadoTipoL",
            cache: false,
            data: {accion: "listadoTipoL"}
        }).done(function (respuesta)
        {
            $(".cal").html(respuesta);
        });
    }
    

    //para comprobar que en este campo solo se introduce datos numericos
    function solonumerosM(objeto){
        if($(objeto).val()!=''){
            //comprobar que no se introduzco texto no numerico
            if(isNaN($(objeto).val())){
                alert('Los campos de horas y extras son numéricos');
                objeto.value='0';
            }
            //comprobar que no se introduzco numero negativo
            var res = objeto.value.substring(0, 1); 
            if(res==='-'){
                alert('Los campos de horas y extras deben ser positivos');
                objeto.value='0';
            }
        }
    }
    
    function editarTipo(id){
        var agenda = $(".cal");
        agenda.html("<img src='{{ URL::asset('img/loading.gif') }}'>");

        $.ajax({
            type: "GET",
            url: "editarTipo",
            cache: false,
            data: {id: id, accion: "editarTipo"}
        }).done(function (respuesta)
        {
            $(".cal").html(respuesta);
        });
    }
    
    function editarTipoOK(id){
        var tipo_n = $("#tipo_n").val();
        var tipo_a = $("#tipo_a").val();
        
        //comprobamos que haya texto en '#tipo_n'
        if(tipo_n !== ''){
            $('#formNuevo').hide();
            $('#dandoAlta').show();

            $.ajax({
                type: "GET",
                url: "editarTipoOK",
                cache: false,
                data: {id: id, tipo_n: tipo_n, tipo_a: tipo_a, accion: "editarTipoOK"}
            }).done(function (respuesta2)
            {
                $("#respuesta_accion").html(respuesta2);
                $('#dandoAlta').hide();
                //redibujar toda la pantalla de nuevo con el nuevo evento guardado
                setTimeout(function ()
                {
                    listadoTipo();
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
<style>
</style>


@stop

@section('menu')

<nav id="nav">
    <ul>
        <li><a class="icon fa-bar-chart-o" href="javascript:buscar();"><span>Buscar</span></a></li>
        <li>
            <a class="icon fa-retweet" href="">Listados</a>
            <ul>
                <li><a href="javascript:prepararListado();">Listado por Tipo</a></li>
                <li><a href="javascript:totalHoras();">Totales Horas</a></li>
            </ul>
        </li>
        @if(Session::has('rol') && Session::get('rol')==='Administrador')
        <li>
            <a class="icon fa-cog" href="">Trabajadores</a>
            <ul>
                <li><a href="javascript:altaTrab();">Alta</a></li>
                <li><a href="javascript:listadoTrab();">Editar Datos/Baja</a></li>
            </ul>
        </li>
        <li>
            <a class="icon fa-bell" href="">Tipos</a>
            <ul>
                <li><a href="javascript:altaTipo();">Alta</a></li>
                <li><a href="javascript:listadoTipo();">Editar</a></li>
            </ul>
        </li>
        @endif
        <li><a class="icon fa-info-circle" href="javascript:ayuda();"><span>Ayuda</span></a></li>
        <li><a class="icon fa-close" href="logout"><span>Salir</span></a></li>
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
</script>


@stop