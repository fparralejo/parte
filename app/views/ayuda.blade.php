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
function vacaciones(){
    window.open ('{{ URL::to('vacaciones') }}',"nueva","resizable=yes, scrollbars=yes, width=650,height=450");
}
function trabajo(){
    window.open ('{{ URL::to('trabajo') }}',"nueva","resizable=yes, scrollbars=yes, width=650,height=450");
}
function baja(){
    window.open ('{{ URL::to('baja') }}',"nueva","resizable=yes, scrollbars=yes, width=650,height=450");
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
                            <td style="text-align: left;"><a href="#" onclick="vacaciones();">Vacaciones</a></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;"><a href="#" onclick="trabajo();">Trabajo (Buscar)</a></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;"><a href="#" onclick="baja();">Baja</a></td>
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
