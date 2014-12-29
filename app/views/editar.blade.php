<div id='editar_evento'>
    <table>
        <tr>
            <td>
                <h5>Editar Parte</h5>
            </td>
            <td>
                <a href='#' onclick="javascript:main('{{ $fecha }}');" rel='{{ $fecha }}'>
                    <img src='{{ URL::asset('img/volver.png') }}' height='10' width='10'>&nbsp;
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
                                        <option value="Trabajo" <?php if($datos_parte[0]->tipo==='Trabajo'){echo 'selected';}?>>Trabajo</option>
                                        <option value="Vacaciones" <?php if($datos_parte[0]->tipo==='Vacaciones'){echo 'selected';}?>>Vacaciones</option>
                                        <option value="Baja" <?php if($datos_parte[0]->tipo==='Baja'){echo 'selected';}?>>Baja</option>
                                    </select>
                                </td>
                                <td width="40%">
                                    <label>Horas</label>
                                    <select name='evento_horas' id='evento_horas_e' class='required'>
                                        <?php for($i=1;$i<=24;$i++) { ?>
                                        <option value="<?php echo $i; ?>" <?php if($datos_parte[0]->horas===(string)$i){echo 'selected';}?>><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label>Descripción</label>
                                    <input type='text' name='evento_titulo' id='evento_titulo' class='required' value="{{ $datos_parte[0]->descripcion }}">
                                <td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <br/>
                                    <input type='button' name='Enviar' value='  OK  ' onClick="editarParteOK();">
                                    <input type='hidden' name='evento_fecha' id='evento_fecha' value='{{ $fecha }}'>
                                    <input type='hidden' name='IdParte' id='IdParte' value='{{ $datos_parte[0]->IdParte }}'>
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
    </table>
</div>
