<?php
$datos = Session::get('DatosImportar');
?>
<script>
function contabilizar(fechaIndice){
    $.ajax({
      data:{"fechaIndice":fechaIndice},  
      url: 'importandoDato',
      type:"get",
      success: function(data) {
        //recuperamos el valor del texto
        var val = document.getElementById("respuesta_accion");
        //actualizamos el indicador visual con el texto
        val.innerHTML = data+val.innerHTML;
      }
    });
}

var idFacturas=new Array();
<?php
for($i=0;$i<count($datos);$i++){
    ?>
    idFacturas[<?php echo $datos[$i]['fechaIndice'];?>]=<?php echo $datos[$i]['fechaIndice']; ?>;
    <?php
}
?>

//progreso actual
var currProgress = 0;
//esta la tarea completa
var done = false;
//cantidad total de progreso
var total = <?php echo count($datos);?>;

function startProgress() {
    //ejecuto la funcion que llama por AJAX la los procedimientos para guardar la factura    
    contabilizar(idFacturas[currProgress]);

    ////incrementamos el valor del progreso cada vez que la funciÃ³n se ejecuta
    currProgress++;
    //comprobamos si hemos terminado
    if(currProgress>(idFacturas.length-1)){
        done=true;
    }
    // sino hemos terminado, volvemos a llamar a la funciÃ³n despuÃ©s de un tiempo
    if(!done)
    {
        setTimeout("startProgress()",0);
    }  
}

$(document).ready(function ()
{
    startProgress();
});

</script>    
<div id='alta_trabajador'>
    <table>
        <tr>
            <td>
                <a href='#' onclick="javascript:main('{{ date('d-m-Y') }}');">
                    <img src='{{ URL::asset('img/volver.png') }}' height='18' width='18'>&nbsp;
                </a>
            </td>
        </tr>
        <tr>
            <td>
                <h5>Datos importar de excel terminar</h5>
            </td>
        </tr>
        <tr>
            <td>
                <div id="formNuevo">
                <?php
                
                ?>
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
