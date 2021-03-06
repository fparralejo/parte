<script>
function parte_alta(){
    window.open ('{{ URL::to('parte_alta') }}',"nueva","resizable=yes, scrollbars=yes, width=650,height=450");
}
function parte_editar(){
    window.open ('{{ URL::to('parte_editar') }}',"nueva","resizable=yes, scrollbars=yes, width=650,height=450");
}
function parte_borrar(){
    window.open ('{{ URL::to('parte_borrar') }}',"nueva","resizable=yes, scrollbars=yes, width=650,height=450");
}
function buscar(){
    window.open ('{{ URL::to('parte_buscar') }}',"nueva","resizable=yes, scrollbars=yes, width=650,height=450");
}
function listar_mes(){
    window.open ('{{ URL::to('listar_mes') }}',"nueva","resizable=yes, scrollbars=yes, width=650,height=450");
}
function cambiar_mes(){
    window.open ('{{ URL::to('cambiar_mes') }}',"nueva","resizable=yes, scrollbars=yes, width=650,height=450");
}
function listar_tipo(){
    window.open ('{{ URL::to('listar_tipo') }}',"nueva","resizable=yes, scrollbars=yes, width=650,height=450");
}
function totales_horas(){
    window.open ('{{ URL::to('totales_horas') }}',"nueva","resizable=yes, scrollbars=yes, width=650,height=450");
}
function ayuda_excel_exportar(){
    window.open ('{{ URL::to('ayuda_excel_exportar') }}',"nueva","resizable=yes, scrollbars=yes, width=650,height=450");
}
function ayuda_excel_importar(){
    window.open ('{{ URL::to('ayuda_excel_importar') }}',"nueva","resizable=yes, scrollbars=yes, width=650,height=450");
}
</script>

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
                    <table>
                        <tr>
                            <td style="width:50%; text-align: right;">PARTES</td>
                            <td style="text-align: left;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;"><a href="#" onclick="parte_alta();">Alta</a></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;"><a href="#" onclick="parte_editar();">Editar</a></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;"><a href="#" onclick="parte_borrar();">Borrar</a></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">BUSCAR</td>
                            <td style="text-align: left;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;"><a href="#" onclick="buscar();">Buscar</a></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">LISTADOS</td>
                            <td style="text-align: left;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;"><a href="#" onclick="listar_mes();">Listar Partes del Mes</a></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;"><a href="#" onclick="cambiar_mes();">Cambiar de Mes</a></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;"><a href="#" onclick="listar_tipo();">Listado Por Tipos</a></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;"><a href="#" onclick="totales_horas();">Totales por Horas</a></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">EXCEL</td>
                            <td style="text-align: left;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;"><a href="#" onclick="ayuda_excel_exportar();">Exportar</a></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;"><a href="#" onclick="ayuda_excel_importar();">Importar</a></td>
                        </tr>
                    </table>
                </div>
                <div id="dandoAlta" style="display: none;">
                    <img src='{{ URL::asset('img/loading.gif') }}'>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br/>
                <div id='respuesta_accion'></div>
                <hr/>
            </td>
        </tr>
    </table>
</div>
