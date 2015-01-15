<div id='alta_trabajador'>
    <table>
        <tr>
            <td>
                <h5>Editar Tipo de Parte</h5>
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
                                    <input type='text' name='nombre_n' id='tipo_n' value="{{ $tipo->tipo }}" class='required' />
                                    <input type='hidden' name='nombre_a' id='tipo_a' value="{{ $tipo->tipo }}" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <br/>
                                    <input type='button' name='Enviar' value='  OK  ' onClick="editarTipoOK({{ $tipo->id }});">
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
