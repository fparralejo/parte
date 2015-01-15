<div id='alta_trabajador'>
    <table>
        <tr>
            <td>
                <h5>Alta Tipo de Parte</h5>
            </td>
            <td>
                <a href='#' onclick="javascript:main('{{ date('d-m-Y') }}');">
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
                                <td colspan="2">
                                    <label>Nombre</label>
                                    <input type='text' name='nombre' id='nombre' class='required'>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <br/>
                                    <input type='button' name='Enviar' value='  OK  ' onClick="altaTipoOK();">
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
