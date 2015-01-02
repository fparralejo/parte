<div id='alta_trabajador'>
    <table>
        <tr>
            <td>
                <h5>Alta Trabajador</h5>
            </td>
            <td>
                <a href='#' onclick="javascript:main('{{ date('d-m-Y') }}');">
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
                                <td colspan="2">
                                    <label>Nombre</label>
                                    <input type='text' name='nombre' id='nombre' class='required'>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label>Apellidos</label>
                                    <input type='text' name='apellidos' id='apellidos' class='required'>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label>Rol</label>
                                    <select name='rol' id='rol' class='required'>
                                        <option value="Administrador">Administrador</option>
                                        <option value="Trabajador" selected>Trabajador</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:50%">
                                    <label>Nick</label>
                                    <input type='text' name='nick' id='nick' class='required'>
                                </td>
                                <td>
                                    <label>Password</label>
                                    <input type='password' name='password' id='password' class='required'>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <br/>
                                    <input type='button' name='Enviar' value='  OK  ' onClick="altaTrabajadorOK();">
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
