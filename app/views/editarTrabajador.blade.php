<div id='editar_trabajador'>
    <table>
        <tr>
            <td>
                <h5>Editar Trabajador</h5>
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
                                    <input type='text' name='nombre' id='nombre' class='required' value="{{ $usuario->nombre }}">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label>Apellidos</label>
                                    <input type='text' name='apellidos' id='apellidos' class='required' value="{{ $usuario->apellidos }}">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label>Rol</label>
                                    <select name='rol' id='rol' class='required'>
                                        <option value="Administrador" <?php if($usuario->rol==='Administrador'){echo 'selected';}?>>Administrador</option>
                                        <option value="Trabajador" <?php if($usuario->rol==='Trabajador'){echo 'selected';}?>>Trabajador</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:50%">
                                    <label>Nick</label>
                                    <input type='text' name='nick' id='nick' class='required' value="{{ $clave->nick }}">
                                </td>
                                <td>
                                    <label>Password</label>
                                    <input type='password' name='password' id='password' class='required' value="{{ $clave->password }}">
                                </td>
                            </tr>
                            <tr>
                                <td style="width:50%">
                                    <div align="center">
                                        <br/>
                                        <input type='button' name='Enviar' value='  OK  ' onClick="editarTrabajadorOK();">
                                    </div>
                                </td>
                                <td>
                                    <div align="center">
                                        <br/>
                                        <?php if( (int)$usuario->Id <> (int)Session::has('Id')){ ?>
                                        <input type='button' name='borrar' value=' Borrar ' onClick="borrarTrabajadorOK('{{ $usuario->Id }}');">
                                        <?php } ?>
                                        <input type='hidden' name='Id' id='Id' value='{{ $usuario->Id }}'>
                                    </div>
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
