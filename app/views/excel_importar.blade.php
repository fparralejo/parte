<div id='alta_trabajador'>
    <table>
        <tr>
            <td>
                <h5>Importar de Excel</h5>
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
                        <table border="1">
                            <tr>
                                <td style="width: 40%;"></td>
                                <td style="width: 5%;"></td>
                                <td style="width: 40%;"></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label>Fichero</label>
                                    <input type='file' name='excelFichero' id='excelFichero' />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <p>el fichero de excel debe ser una tabla con los datos de las columnas dispuestos de esta forma:
                                    fecha(2015-01-17), horas, extras, tipo y descripción</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <div align="center">
                                        <input type='button' name='Enviar' value='  OK  ' onClick="excel_exportarFichero();">
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
