<div id='editar_evento'>
    <table>
        <tr>
            <td>
                <h5>Editar Parte</h5>
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
                                        <option value="{{ $tipo['tipo'] }}" <?php if($datos_parte[0]->tipo===$tipo['tipo']){echo 'selected';}?>>{{ $tipo['tipo'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td width="25%">
                                    <label>Horas</label>
                                    <input type='text' name='evento_horas' id='evento_horas_e' class='required'
                                           value="<?php echo $datos_parte[0]->horas; ?>" onBlur="solonumerosM(this);" />
                                </td>
                                <td width="25%">
                                    <label>Extras</label>
                                    <input type='text' name='evento_extras' id='evento_extras_e' class='required'
                                           value="<?php echo $datos_parte[0]->extras; ?>" onBlur="solonumerosM(this);" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label>Descripción</label>
                                    <input type='text' name='evento_titulo' id='evento_titulo' class='required' value="{{ htmlentities($datos_parte[0]->descripcion) }}">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <br/>
                                    <input type='button' name='Enviar' value='  OK  ' onClick="editarParteOK();">
                                    <input type='hidden' name='evento_fecha' id='evento_fecha' value='{{ $fecha }}'>
                                    <input type='hidden' name='IdParte' id='IdParte' value='{{ $datos_parte[0]->IdParte }}'>
                                </td>
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
    </table>
</div>
