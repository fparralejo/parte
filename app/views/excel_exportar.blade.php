<div id='alta_trabajador'>
    <table>
        <tr>
            <td>
                <h5>Exportar a Excel</h5>
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
                        <table border="0">
                            <tr>
                                <td style="width: 40%;"></td>
                                <td style="width: 5%;"></td>
                                <td style="width: 40%;"></td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Desde</label>
                                    {{ HTML::script('js/datepicker_spanish.js') }}
                                    <script language="JavaScript">
                                    $(function() {
                                        $("#excelFechaDesde").datepicker();
                                    });
                                    
                                    function comprobarFecha(fecha){
                                        //sin hacer
                                        //var fechaTrozos = fecha.split('/');
                                    }
                                    </script>
                                    <style type="text/css">
                                    /* para que no salga el rectangulo inferior */        
                                    .ui-widget-content {
                                        border: 0px solid #AAAAAA;
                                    }
                                    </style>
                                    <input type='text' name='excelFechaDesde' id='excelFechaDesde' 
                                           onblur="comprobarFecha(this.value);" class='required'>
                                </td>
                                <td></td>
                                <td>
                                    <label>Hasta</label>
                                    <script language="JavaScript">
                                    $(function() {
                                        $("#excelFechaHasta").datepicker();
                                    });
                                    </script>
                                    <input type='text' name='excelFechaHasta' id='excelFechaHasta' 
                                           onblur="comprobarFecha(this.value);" class='required'>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    Si no indica nada en las casillas de las fechas el listado sera desde el inicio hasta el final cronológicamente
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
