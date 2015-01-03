<div id='editar_trabajador'>
    <table>
        <tr>
            <td>
                <h5>Ayuda</h5>
            </td>
            <td>
                <a href='#' onclick="javascript:main('{{ date('d-m-Y') }}');">
                    <img src='{{ URL::asset('img/volver.png') }}' height='18' width='18'>&nbsp;
                </a>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="formNuevo">
                    <ul>
                        <li>Partes</li>
                        <li><a href="parte_alta">Alta</a></li>
                    </ul>
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
