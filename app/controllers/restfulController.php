<?php

class restfulController extends BaseController {
    
    public function listar($Id) {
        //listo el parte de esta empresa por id
        
        $query = DB::select("select IdParte,fecha,tipo,horas,extras,descripcion from partefpp2_partes where Id=".$Id." and borrado=1 order by fecha");
        
        return Response::json($query);
    }
    
    
}
?>

