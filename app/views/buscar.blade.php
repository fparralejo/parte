@extends('calendar')


@section('content')
<div id='buscar'>
    <table>
        <tr>
            <td>
                <h5>Buscar</h5>
            </td>
            <td>
                <a href='#' onclick="javascript:main('{{ date('d-m-Y') }}');" rel='{{ date('d-m-Y') }}'>
                    <img src='{{ URL::asset('img/volver.png') }}' height='10' width='10'>&nbsp;
                </a>
            </td>
        </tr>
        <tr>
            <td>
                <div id="formNuevo">
                    <form class='formeventos_b'>
                        <table>
                            <tr>
                                <td colspan="2">
                                    <input type='text' name='buscar_txt' id='buscar_txt' class='required'>
                                <td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <br/>
                                    <input type='button' name='Enviar' value='  OK  ' onClick="buscarOK();">
                                <td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div id="dandoAlta_b" style="display: none;">
                    <img src='{{ URL::asset('img/loading.gif') }}'>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <br/>
                <div id='respuesta_accion'></div>
                <hr/>
            </td>
        </tr>
    </table>
</div>
@stop
