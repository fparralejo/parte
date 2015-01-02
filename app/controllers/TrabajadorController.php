<?php

class TrabajadorController extends BaseController {
    
    public function listadoTrab() {
        //listo los trabajadores y administradores que hay
        
        $query=usuario::where('borrado','=', "1")->get();
        

        $html = '<a href="#" onclick="javascript:main(\''. date('d-m-Y') .'\');" rel="'. date('d-m-Y') .'">';
        $html = $html . '<img src="'. URL::asset('img/volver.png') .'" height="10" width="10">&nbsp';
        $html = $html . '</a><br/><br/>';
        
        $html = $html . "<b>Listado Trabajadores</b>";
        $html = $html . '<ul>';
        for ($i = 0; $i < count($query); $i++) {
            $html = $html . "<li class='listadoParte'><a href='#' onclick='editarTrabajador(" . $query[$i]->Id . ");'>";
            $html = $html . "Nombre: <b>".$query[$i]->nombre.'</b><br/>';
            $html = $html . "Apellidos: <b>".$query[$i]->apellidos.'</b><br/>';
            $html = $html . "Rol: <b>".$query[$i]->rol.'</b><br/>';
            $html = $html . "</a></li>";
            $html = $html . "<li>&nbsp;</li>";
        }
        $html = $html . "</ul>";
        
        return $html;
        
    }
    
    public function editarTrab() {
        //get de id del trabajador
        $usuario=usuario::find(Input::get('id'));
        $clave=clave::find(Input::get('id'));
        
        return View::make('editarTrabajador', array('usuario' => $usuario,'clave' => $clave));
    }
    
    public function editarTrabOK() {
        //recojemos los datos del form
        //y actualizamos las tablas usuario y clave (en transaccion)

        DB::beginTransaction();
        $html='';
        
        try{
            $usuario=usuario::find(Input::get('id'));
            $clave=clave::find(Input::get('id'));
        
            $usuario->nombre=Input::get('nombre');
            $usuario->apellidos=Input::get('apellidos');
            $usuario->rol=Input::get('rol');
            $usuario->save();

            $clave->nick=Input::get('nick');
            $clave->password=Input::get('pass');
            $clave->save();
            
            
            DB::commit();
            $html = $html . "<p class='ok'>Los datos del trabajador se han editado correctamente.</p>";
        }
        catch (Exception $e){
            DB::rollback();
            $html = $html . "<p class='error'>Se ha producido un error editando los datos del trabajador.</p>";
        }
        
        return $html;
    }
    
    public function borrarTrabajadorOK() {
        //recojemos los datos del form
        //y actualizamos las tablas usuario y clave (en transaccion)

        DB::beginTransaction();
        $html='';
        
        try{
            $usuario=usuario::find(Input::get('id'));
            $clave=clave::find(Input::get('id'));
        
            $usuario->borrado='0';
            $usuario->save();

            $clave->borrado='0';
            $clave->save();
            
            DB::commit();
            $html = $html . "<p class='ok'>Los datos del trabajador se han borrado correctamente.</p>";
        }
        catch (Exception $e){
            DB::rollback();
            $html = $html . "<p class='error'>Se ha producido un error borrando los datos del trabajador.</p>";
        }
        
        return $html;
    }
    
    function altaTrab(){
        //presentamos el form de alta trabajador
        return View::make('altaTrabajador');
    }
    
    function altaTrabOK(){
        DB::beginTransaction();
        $html='';
        
        try{
            $usuario=new usuario();
            $clave=new clave();
        
            $usuario->nombre=Input::get('nombre');
            $usuario->apellidos=Input::get('apellidos');
            $usuario->fecha=date('Y-m-d');
            $usuario->rol=Input::get('apellidos');
            $usuario->borrado='1';
            $usuario->save();

            $clave->Id=$usuario->Id;
            $clave->nick=Input::get('nick');
            $clave->password=Input::get('pass');
            $clave->borrado='1';
            $clave->save();
            
            DB::commit();
            $html = $html . "<p class='ok'>Los datos del trabajador se han dado de alta correctamente.</p>";
        }
        catch (Exception $e){
            DB::rollback();
            $html = $html . "<p class='error'>Se ha producido un error al dar de alta los datos del trabajador.</p>";
        }
        
        return $html;
    }
    
}
?>

