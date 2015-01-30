<div id='importar_calculos'>
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
            <td></td>
        </tr>
        <tr>
            <td>
                <script>
                $("input[name='excelFicheroSubir']").on('change', function(){
                    $('#dandoAlta').show();
                    var formData = new FormData($('#formSubir')[0]);
                    var ruta = 'excel_importarFichero';
                    $.ajax({
                        url: ruta,
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false
                    }).done(function (respuesta)
                    {
                        $('#formNuevo').hide();

                        $('#dandoAlta').html(respuesta);
                    });
                });    
                </script>    
                <div id="formNuevo">
                    {{ Form::open(array(
                                        "id"=>"formSubir",
                                        "url"=>"excel_importarFichero",
                                        "files"=>true,
                                        'enctype'  => 'form-data'
                                        )) }}
                        <table border="0">
                            <tr>
                                <td style="width: 40%;"></td>
                                <td style="width: 5%;"></td>
                                <td style="width: 40%;"></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label>Fichero</label>
                                    <input type='file' name='excelFicheroSubir' id='excelFicheroSubir' /><br/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <p>el fichero de excel debe ser una tabla con los datos de las columnas dispuestos de esta forma:
                                    fecha(2015-01-17), horas, extras, tipo y descripción</p>
                                </td>
                            </tr>
                        </table>
                    {{ Form::close() }}                        
                </div>
                <div id="dandoAlta" style="display: none;">
                    <img src='{{ URL::asset('img/loading.gif') }}'>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id='respuesta_accion'></div>
                <div id='respuesta_accion2'></div>
                <hr/>
            </td>
        </tr>
    </table>
</div>
