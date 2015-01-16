<div class='cal'>
    <table>
        <tr>
            <td>
                <h5>Listado por Tipo</h5>
            </td>
            <td>
                <a href='#' onclick="javascript:main('{{ date('d-m-Y') }}');" rel='{{ date('d-m-Y') }}'>
                    <img src='{{ URL::asset('img/volver.png') }}' height='18' width='18'>&nbsp;
                </a>
            </td>
        </tr>
        <tr>
            <td>
                <div id="formNuevo_b">
                    <form class='formeventos'>
                        <table>
                            <tr>
                                <td colspan="2">
                                    <label>Tipo</label>
                                    <select id="tipo">
                                        @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo['tipo'] }}">{{ $tipo['tipo'] }}</option>
                                        @endforeach
                                    </select>
                                    <br/>
                                    <label>Palabra a buscar</label>
                                    <input type='text' name='campo1' id='campo1' class='required'>
                                    <br/>
                                    <label>Año</label>
                                    <select name='anio' id='anio' class='required'>
                                        <option value="2014">2014</option>
                                        <option value="2015" selected>2015</option>
                                        <option value="2016">2016</option>
                                        <option value="2017">2017</option>
                                        <option value="2018">2018</option>
                                    </select>
                                <td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <br/>
                                    <input type='button' name='Enviar' value='  OK  ' onClick="listadoTipoOK();">
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
