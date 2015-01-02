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
                                <td width="60%">
                                    <label>Tipo</label>
                                    <select id="tipo">
                                        <option value="Trabajo">Trabajo</option>
                                        <option value="Vacaciones">Vacaciones</option>
                                        <option value="Baja">Baja</option>
                                    </select>
                                </td>
                                <td width="40%">
                                    <label>Horas</label>
                                    <select name='evento_horas' id='evento_horas' class='required'>
                                        <?php for($i=1;$i<=24;$i++) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label>Descripción</label>
                                    <input type='text' name='evento_titulo' id='evento_titulo' class='required'>
                                <td>
                            </tr>
                            <tr>
                                <td colspan="2">
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
