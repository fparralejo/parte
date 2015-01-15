<div id='nuevo_evento' rel='{{ $fecha }}'>
    <table>
        <tr>
            <td>
                <h5>Partes del {{ $fecha }}</h5>
            </td>
            <td>
                <a href='#' onclick="javascript:main('{{ $fecha }}');" rel='{{ $fecha }}'>
                    <img src='{{ URL::asset('img/volver.png') }}' height='18' width='18'>&nbsp;
                </a>
            </td>
        </tr>
        <tr>
            <td>
                <div id="formNuevo">
                    <form class='formeventos'>
                        <table>
                            <tr>
                                <td width="50%">
                                    <label>Tipo</label>
                                    <select id="tipo">
                                        @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo['tipo'] }}">{{ $tipo['tipo'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td width="25%">
                                    <label>Horas</label>
                                    <input type='text' name='evento_horas' id='evento_horas' class='required'
                                           value="0" onBlur="solonumerosM(this);" />
                                </td>
                                <td width="25%">
                                    <label>Extras</label>
                                    <input type='text' name='evento_extras' id='evento_extras' class='required'
                                           value="0" onBlur="solonumerosM(this);" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label>Descripción</label>
                                    <input type='text' name='evento_titulo' id='evento_titulo' class='required'>
                                <td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <br/>
                                    <input type='button' name='Enviar' value='Nuevo' onClick="darAltaEvento();">
                                    <input type='hidden' name='evento_fecha' id='evento_fecha' value='{{ $fecha }}'>
                                <td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div id="dandoAlta" style="display: none;">
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
        <tr>
            <td>
                <div id='respuesta_form'>{{ $listar_eventos }}</div>
            </td>
        </tr>
    </table>
</div>
