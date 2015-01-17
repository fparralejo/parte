<script src="js/highcharts.js"></script>    
<script src="js/highcharts-3d.js"></script>    
<script src="js/modules/exporting.js"></script>    
<?php
//paleta de colores
$color[0]='#7eddb7';
$color[1]='#dbdb7f';
$color[2]='#e2dedc';
$color[3]='#abdbe8';
$color[4]='#e5a9d8';
$color[5]='#d3c6cc';


//preparo los datos a presentar
$txtDatos = '';
$indColor=0;
foreach ($anual as $tipo) {
    $txtDatos = $txtDatos . "{color:'".$color[$indColor]."',name:'".$tipo->tipo."',y:".($tipo->horas + $tipo->extras)."},";
    $indColor = $indColor + 1;
}
//quito la ultima coma
$txtDatos = substr($txtDatos,0,strlen($txtDatos)-1);
//echo $txtDatos;
?>

<script type="text/javascript">
$(function () {
    $('#containeringresos').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            },
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: '{{ $anio }}'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        credits: {
            enabled: false
        },        
        series: [{
            type: 'pie',
            name: ' ',
            data: [
                <?php echo $txtDatos; ?>
            ]
        }]
    });
});    
</script>

<div class='cal'>
    <table>
        <tr>
            <td>
                <h5>Horas Totales Resumén</h5>
            </td>
            <td>
                <a href='#' onclick="javascript:main('{{ date('d-m-Y') }}');" rel='{{ date('d-m-Y') }}'>
                    <img src='{{ URL::asset('img/volver.png') }}' height='18' width='18'>&nbsp;
                </a>
            </td>
        </tr>
        <tr>
            <td>
                <br/>
                <div id='respuesta_accion'>
                    <div id="containeringresos" style="display: block;">
                        <div id="dandoAlta_b" style="display: block;">
                            <img src='{{ URL::asset('img/loading.gif') }}'>
                        </div>
                    
                    </div>
                    
                    <div>
                        <?php
//                        var_dump($mensuales);die;
                        ?>
                        <?php if(count($mensuales)>0) { ?>
                        <table>
                            <tr style="background-color: #e4e4e4;">
                                <th><b>Mes</b></th>
                                <th><b>Tipo</b></th>
                                <th><b>Horas</b></th>
                                <th><b>Extras</b></th>
                            </tr>
                            <?php
                            $par='#dbffde';
                            $impar='#edf4f4';
                            
                            $mesTxt='';
                            foreach ($mensuales as $mes) {
                                switch ($mes->Mes) {
                                    case '1':
                                        $mesTxt='Enero';
                                        break;
                                    case '2':
                                        $mesTxt='Febrero';
                                        break;
                                    case '3':
                                        $mesTxt='Marzo';
                                        break;
                                    case '4':
                                        $mesTxt='Abril';
                                        break;
                                    case '5':
                                        $mesTxt='Mayo';
                                        break;
                                    case '6':
                                        $mesTxt='Junio';
                                        break;
                                    case '7':
                                        $mesTxt='Julio';
                                        break;
                                    case '8':
                                        $mesTxt='Agosto';
                                        break;
                                    case '9':
                                        $mesTxt='Septiembre';
                                        break;
                                    case '10':
                                        $mesTxt='Octubre';
                                        break;
                                    case '11':
                                        $mesTxt='Noviembre';
                                        break;
                                    case '12':
                                        $mesTxt='Diciembre';
                                        break;
                                }
                                $bcgd=$impar;
                                if(($mes->Mes % 2)===0){
                                    $bcgd=$par;
                                }
                                ?>
                                <tr style="background-color: <?php echo $bcgd; ?>;">
                                    <td>{{ $mesTxt }}</td>
                                    <td>{{ $mes->tipo }}</td>
                                    <td style="text-align: right;">{{ $mes->horas }}</td>
                                    <td style="text-align: right;">{{ $mes->extras }}</td>
                                </tr>
                            <?php
                            }
                            ?>
                                <tr>
                                    <td colspan="4"><hr/><hr/></td>
                                </tr>
                            <?php    
                            foreach ($anual as $tipo) {
                            ?>
                                <tr style="background-color: #d3ead5;">
                                    <td><b>TOTAL</b></td>
                                    <td><b>{{ $tipo->tipo }}</b></td>
                                    <td style="text-align: right;"><b>{{ $tipo->horas }}</b></td>
                                    <td style="text-align: right;"><b>{{ $tipo->extras }}</b></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                        <?php } ?>
                    </div>
            </td>
        </tr>
    </table>
</div>
<script>
    //por ultimo quitamos el simbolo de espera
    document.getElementById('dandoAlta_b').style.display='none';
</script>
