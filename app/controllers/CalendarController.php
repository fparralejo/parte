<?php

class CalendarController extends BaseController {

    public function login() {
        Session::flush();
        return View::make('login');
    }

    public function logout() {
        Session::flush();
        return View::make('login');
    }

    public function getControl() {
        //controlamos si estaamos en sesion por las distintas paginas de la app
        //controlamos las vbles sesion 'nombre', 'apellidos' y 'rol'
        if (Session::get('nombre') && Session::get('apellidos') && Session::get('rol')) {
            //chequeamos que estos valores del usuario existan en la tabla 'usuarios'
            $existe = usuario::where('nombre', '=', Session::get('nombre'))
                    ->where('apellidos', '=', Session::get('apellidos'))
                    ->where('rol', '=', Session::get('rol'))
                    ->where('borrado', '=', '1')
                    ->get();

            //si existe el contador es mayor que 0
            if (count($existe) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function postLogin() {
        //busco en la tabla de claves si existe
        $encontrado = clave::where('nick', '=', Input::get('nick'))
                ->where('password', '=', Input::get('password'))
                ->where('borrado', '=', '1')
                ->get();

        if (count($encontrado) > 0) {
            //guardo las vbles de sesion para navegar por la app
            $datosUsuario = usuario::where('Id', '=', $encontrado[0]->IdClave)
                    ->where('borrado', '=', '1')
                    ->get();

            Session::put('Id', $datosUsuario[0]->Id);
            Session::put('nombre', $datosUsuario[0]->nombre);
            Session::put('apellidos', $datosUsuario[0]->apellidos);
            Session::put('rol', $datosUsuario[0]->rol);


            return Redirect::to('main');
        } else {
            return $this->login()->with('error', '<font color="#ff0000">Nick o clave incorrectos.</font>');
        }
    }

    public function generar_calendario_inicio() {
        //control de sesion
        if (!$this->getControl()) {
            return $this->login()->with('error', 'La sesión a expirado. Vuelva a logearse.');
        }

        return View::make('calendar', array('html' => '','fecha'=>Input::get('fecha')));
    }

    public function generar_calendario() {
        //control de sesion
        if (!$this->getControl()) {
            return $this->login()->with('error', 'La sesión a expirado. Vuelva a logearse.');
        }

        $fecha_calendario = array();
        if (Input::get("mes") == "" || Input::get("anio") == "") {
            $fecha_calendario[1] = intval(date("m"));
            if ($fecha_calendario[1] < 10)
                $fecha_calendario[1] = "0" . $fecha_calendario[1];
            $fecha_calendario[0] = date("Y");
        }
        else {
            $fecha_calendario[1] = intval(Input::get("mes"));
            if ($fecha_calendario[1] < 10)
                $fecha_calendario[1] = "0" . $fecha_calendario[1];
            else
                $fecha_calendario[1] = $fecha_calendario[1];
            $fecha_calendario[0] = Input::get("anio");
        }
        $fecha_calendario[2] = "01";

        /* obtenemos el dia de la semana del 1 del mes actual */
        $primeromes = date("N", mktime(0, 0, 0, $fecha_calendario[1], 1, $fecha_calendario[0]));

        /* comprobamos si el a�o es bisiesto y creamos array de d�as */
        if (($fecha_calendario[0] % 4 == 0) && (($fecha_calendario[0] % 100 != 0) || ($fecha_calendario[0] % 400 == 0)))
            $dias = array("", "31", "29", "31", "30", "31", "30", "31", "31", "30", "31", "30", "31");
        else
            $dias = array("", "31", "28", "31", "30", "31", "30", "31", "31", "30", "31", "30", "31");

        //ESTA CONSULTA NO FUNCIONA BIEN
//            $query=calendario::where('month(fecha)','=',$fecha_calendario[1])
//                    ->where('year(fecha)', '=', $fecha_calendario[0])
//                    ->groupBy('fecha')
//                    ->get(array('fecha', 'count(id) as total'));


        //$query = DB::select("select fecha,count(id) as total from partefpp_calendario where month(fecha)='" . $fecha_calendario[1] . "' and year(fecha)='" . $fecha_calendario[0] . "' group by fecha");
        $query = DB::select("select fecha,count(Id) as total from partefpp2_partes where month(fecha)='" . $fecha_calendario[1] . "' and year(fecha)='" . $fecha_calendario[0] . "' and Id=".Session::get('Id')." and borrado=1 group by fecha");

        $eventos = array();
        for ($i = 0; $i < count($query); $i++) {
            $eventos[$query[$i]->fecha] = $query[$i]->total;
        }

        $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        /* calculamos los d�as de la semana anterior al d�a 1 del mes en curso */
        $diasantes = $primeromes - 1;

        /* los d�as totales de la tabla siempre ser�n m�ximo 42 (7 d�as x 6 filas m�ximo) */
        $diasdespues = 42;

        /* calculamos las filas de la tabla */
        $tope = $dias[intval($fecha_calendario[1])] + $diasantes;
        if ($tope % 7 != 0)
            $totalfilas = intval(($tope / 7) + 1);
        else
            $totalfilas = intval(($tope / 7));

        $mesanterior = date("Y-m-d", mktime(0, 0, 0, $fecha_calendario[1] - 1, 01, $fecha_calendario[0]));
        $messiguiente = date("Y-m-d", mktime(0, 0, 0, $fecha_calendario[1] + 1, 01, $fecha_calendario[0]));
        
        
        /* empezamos a pintar la tabla */
        //lo guardamos en una vble $html
        $html = '';
        $html = $html . "<p class='toggle'><h2><a href='#' onClick='cambiaMes(this);' rel='$mesanterior' class='anterior'>&laquo; </a>" ;
        $html = $html . "<a href='#' onClick='listarMes(".$fecha_calendario[1].",".$fecha_calendario[0].");'>" . $meses[intval($fecha_calendario[1])] . " - " . $fecha_calendario[0] .'</a>';
        $html = $html .  "<a href='#' onClick='cambiaMes(this);' class='siguiente' rel='$messiguiente'> &raquo;</a></h2></p>";
        if (isset($mostrar))
            $html = $html . $mostrar;

        $html = $html . "<table class='calendario' cellspacing='0' cellpadding='0'>";
        $html = $html . "<tr><th>L</th><th>M</th><th>X</th><th>J</th><th>V</th><th>S</th><th>D</th></tr><tr>";

        /* inicializamos filas de la tabla */
        $tr = 0;
        $dia = 1;

        for ($i = 1; $i <= $diasdespues; $i++) {
            if ($tr < $totalfilas) {
                if ($i >= $primeromes && $i <= $tope) {
                    /* creamos fecha completa */
                    if ($dia < 10)
                        $dia_actual = "0" . $dia;
                    else
                        $dia_actual = $dia;
                    $fecha_completa = $fecha_calendario[0] . "-" . $fecha_calendario[1] . "-" . $dia_actual;
                    
                    //busco si el dia tiene datos o no
                    $bgcolor = $this->tieneDatos($fecha_completa);
                    
                    $html = $html . "<td style='$bgcolor' class='";

                    $hayevento = 0;
                    if (isset($eventos[$fecha_completa])) {
                        if (intval($eventos[$fecha_completa]) > 0) {
                            $html = $html . "evento";
                            $hayevento = $eventos[$fecha_completa];
                        }
                    }

                    /* si es hoy coloreamos la celda */
                    if (date("Y-m-d") == $fecha_completa)
                        $html = $html . " hoy";

                    $html = $html . "'>";

                    /* recorremos el array de eventos para mostrar los eventos del d�a de hoy */
                    //ANTIGUO
                    //$html = $html . "<a href='#' data-evento='#evento".$dia_actual."' class='modal' rel='".$fecha_completa."' title='Hay ".$hayevento." eventos'>".$dia."</a>";
                    $html = $html . "<a href='javascript:evento(" . $dia_actual . ",".$fecha_calendario[1].",".$fecha_calendario[0].");' class='modal' rel='" . $fecha_completa . "' title='Hay " . $hayevento . " eventos'>" . $dia . "</a>";
                    //else $html = $html . "$dia";

                    /* agregamos enlace a nuevo evento si la fecha no ha pasado */
                    //$html = $html . "<a href='#' data-evento='#nuevo_evento' title='Agregar un Evento el ".$this->fecha($fecha_completa)."' class='add agregar_evento' rel='".$fecha_completa."'><img src='". URL::asset('img/add.png') ."' height='18' width='18'>&nbsp;</a>";

                    $html = $html . "</td>";
                    $dia+=1;
                } else
                    $html = $html . "<td class='desactivada'>&nbsp;</td>";
                if ($i == 7 || $i == 14 || $i == 21 || $i == 28 || $i == 35 || $i == 42) {
                    $html = $html . "<tr>";
                    $tr+=1;
                }
            }
        }
        $html = $html . "</table><br/><br/>";
        
        //otros años y meses
        //select de los meses
        $selectMeses='';
        $selectMeses=$selectMeses."<select id='mesc' name='mes'>";
        $selectMeses=$selectMeses."<option value='1'>Enero</option>";
        $selectMeses=$selectMeses."<option value='2'>Febrero</option>";
        $selectMeses=$selectMeses."<option value='3'>Marzo</option>";
        $selectMeses=$selectMeses."<option value='4'>Abril</option>";
        $selectMeses=$selectMeses."<option value='5'>Mayo</option>";
        $selectMeses=$selectMeses."<option value='6'>Junio</option>";
        $selectMeses=$selectMeses."<option value='7'>Julio</option>";
        $selectMeses=$selectMeses."<option value='8'>Agosto</option>";
        $selectMeses=$selectMeses."<option value='9'>Septiembre</option>";
        $selectMeses=$selectMeses."<option value='10'>Octubre</option>";
        $selectMeses=$selectMeses."<option value='11'>Noviembre</option>";
        $selectMeses=$selectMeses."<option value='12'>Diciembre</option>";
        $selectMeses=$selectMeses."</select>";

        //select de los años
        $selectAnio='';
        $selectAnio=$selectAnio."<select id='anioc' name='anio'>";
        $selectAnio=$selectAnio."<option value='2014'>2014</option>";
        $selectAnio=$selectAnio."<option value='2015' selected>2015</option>";
        $selectAnio=$selectAnio."<option value='2016'>2016</option>";
        $selectAnio=$selectAnio."<option value='2017'>2017</option>";
        $selectAnio=$selectAnio."<option value='2018'>2018</option>";
        $selectAnio=$selectAnio."</select>";

        
        
        //lo incluyo en una tabla nueva
        $html = $html . "<form>";
        $html = $html . "<table cellspacing='0' cellpadding='0'>";
        $html = $html . "<tr>";
        $html = $html . "<td colspan='3'>";
        $html = $html . "<p class='toggle'>Ir a...</p>";
        $html = $html . "</td>";
        $html = $html . "</tr>";
        $html = $html . "<tr>";
        $html = $html . "<td width='40%'>";
        $html = $html . $selectMeses;
        $html = $html . "</td>";
        $html = $html . "<td width='25%'>";
        $html = $html . $selectAnio;
        $html = $html . "</td>";
        $html = $html . "<td width='15%'>";
        $html = $html . "<a href='#' onClick='cambiaMesAnio();' class='anterior'><img src='". URL::asset('img/Ok.png') ."' height='16' width='16'></a>";
        $html = $html . "</td>";
        $html = $html . "</tr>";
        $html = $html . "</table>";
        $html = $html . "</form>";

        
        return $html;
    }
    
    //si tiene dato sombrea la casilla del calendario, sino lo deja en blanco
    private function tieneDatos($fecha_completa){
        $query = parte::where('fecha', '=', $fecha_completa)
                        ->where('Id','=', Session::get('Id'))
                        ->where('borrado','=', '1')
                        ->orderBy('IdParte', 'asc')
                        ->get();
        
        $html='';
        if(count($query)>0){
            $html='background-color: #cbf2de;';
        }else{
            $html='';
        }

        return $html;
    }
    
    public function evento_nuevo(){
        $fecha=Input::get('dia')."-".Input::get('mes')."-".Input::get('anio');
        
        $listar_eventos=$this->listar_evento();
        $tipos = tipo::all();
        
        return View::make('eventos', array('fecha' => $fecha,'listar_eventos' => $listar_eventos,'tipos'=>$tipos));
    }
    
    function listarMes(){
        $fecha='01-'.Input::get('mes').'-'.Input::get('anio');
        $query = DB::select("select IdParte,fecha,tipo,horas,extras,descripcion from partefpp2_partes where month(fecha)='" . Input::get('mes') . "' and year(fecha)='" . Input::get('anio') . "' and Id=".Session::get('Id')." and borrado=1 order by fecha");

        return $this->htmlListado($query,$fecha);
    }
    
    private function htmlListado($query,$fecha){
        $html = '<a href="#" onclick="javascript:main(\''. $fecha .'\');" rel="'. $fecha .'">';
        $html = $html . '<img src="'. URL::asset('img/volver.png') .'" height="18" width="18">&nbsp';
        $html = $html . '</a><br/><br/>';
        
        $html = $html . '<ul>';
        for ($i = 0; $i < count($query); $i++) {
            $fecha = explode('-',$query[$i]->fecha);
            $fecha = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
            $html = $html . "<li class='listadoParte'><a href='#' onclick='editarParte(" . $query[$i]->IdParte . ");'>";
            $html = $html . "Fecha: <b>".$fecha.'</b><br/>';
            $html = $html . "Tipo: <b>".$query[$i]->tipo.'</b><br/>';
            $html = $html . "Horas: <b>".$query[$i]->horas.'</b> - Extras: <b>'.$query[$i]->extras.'</b> <br/>';
            $html = $html . "Descripción: <b>".$query[$i]->descripcion.'</b><br/>';
            $html = $html . "</a></li>";
            $html = $html . "<li>&nbsp;</li>";
        }
        $html = $html . "</ul>";
        
        
        return $html;
    }

    public function guardar_evento() {
        $parte = new parte();
        
        $fecha=explode('-',Input::get("fecha"));
        $fecha=$fecha[2].'-'.$fecha[1].'-'.$fecha[0];
        
        $parte->fecha = $fecha;
        $parte->descripcion = strip_tags(Input::get("evento"));
        $parte->tipo = Input::get("tipo");
        $parte->horas = Input::get("horas");
        $parte->extras = Input::get("extras");
        $parte->Id = Session::get("Id");
        $parte->borrado = "1";

        $html = '';
        if ($parte->save()) {
            $html = $html . "<input type='hidden' name='parteInsertado' value='".$parte->IdParte."'><p class='ok'>Parte guardado correctamente.</p>";
        } else {
            $html = $html . "<p class='error'>Se ha producido un error guardando el parte.</p>";
        }
        return $html;
    }

    public function editarParteOK() {
        $parte = parte::find(Input::get("IdParte"));
        
        $fecha=explode('-',Input::get("fecha"));
        $fecha=$fecha[2].'-'.$fecha[1].'-'.$fecha[0];
        
        $parte->fecha = $fecha;
        $parte->descripcion = strip_tags(Input::get("evento"));
        $parte->tipo = Input::get("tipo");
        $parte->horas = Input::get("horas");
        $parte->extras = Input::get("extras");
        $parte->Id = Session::get("Id");
        $parte->borrado = "1";

        $html = '';
        if ($parte->save()) {
            $html = $html . "<p class='ok'>Parte editado correctamente.</p>";
        } else {
            $html = $html . "<p class='error'>Se ha producido un error editando el parte.</p>";
        }
        return $html;
    }

    //esta funcion la llama evento_nuevo()
    public function listar_evento() {
        $fecha=Input::get('anio')."-".Input::get('mes')."-".Input::get('dia');
        
        $query = parte::where('fecha', '=', $fecha)
                        ->where('Id','=', Session::get('Id'))
                        ->where('borrado','=', '1')
                        ->orderBy('IdParte', 'asc')
                        ->get();
        
        $html = '<table>';
        $html = $html.'<tr><td colspan="3"><b>Listado de Partes</b></td></tr>';
        for ($i = 0; $i < count($query); $i++) {
            $html = $html . "<tr class='bgtr'><td colspan='3'><a href='#' onclick='editarParte(" . $query[$i]->IdParte . ");'>" . $query[$i]->descripcion . "</a></td><td align='right'><a href='#' class='eliminar_evento' onClick='borrarEvento(" . $query[$i]->IdParte . ",".Input::get('anio').",".Input::get('mes').",".Input::get('dia').");' title='Eliminar este parte'><div  id='evIcono" . $query[$i]->IdParte . "'><img src='" . URL::asset('img/delete.png') . "' height='10' width='10'></div></a></td></tr>";
            $html = $html . "<tr class='bgtr2'><td colspan='2'><a href='#' onclick='editarParte(" . $query[$i]->IdParte . ");'><b>" . $query[$i]->tipo . "</b></td><td align='right'><b>" . $query[$i]->horas . " horas - ".$query[$i]->extras." extras</b></a></td><td></td></tr>";
            $html = $html . "<tr><td colspan='4'><hr/><br/></td></tr>";
        }
        $html = $html.'</table>';

        return $html;
    }

    public function borrar_evento() {
        $parte = parte::find(Input::get("id"));

        $parte->borrado='0';
        
        
        $html = '';
        if ($parte->save()) {
            $html = $html . "<p class='ok'>Parte eliminado correctamente.</p>";
        } else {
            $html = $html . "<p class='error'>Se ha producido un error eliminando el parte.</p>";
        }
        return $html;
    }
    
    function editarParte(){
        $datos_parte = parte::where('IdParte', '=', Input::get('IdParte'))
                      ->where('borrado', '=', '1')
                      ->get();
        
        $fecha=explode('-',$datos_parte[0]->fecha);
        $fecha=$fecha[2].'-'.$fecha[1].'-'.$fecha[0];
        
        $tipos = tipo::all();
        
        return View::make('editar', array('datos_parte' => $datos_parte,'fecha' => $fecha,'tipos'=>$tipos));
    }

    function buscar(){
        return View::make('buscar');
    }
    
    function buscarOK(){
        //hacer una busqueda en los campos tipo y descripcion
        $termino=Input::get('buscar');

        
        $query=parte::where('Id','=', Session::get('Id'))
                      ->where('borrado','=', "1")
                      ->where(function($query2) use ($termino)
                      {
                        $query2->where('tipo', 'LIKE', "%$termino%")
                               ->orWhere('descripcion', 'LIKE', "%$termino%");
                      })
                      ->get();
            
        return $this->htmlListado($query,date('d-m-Y'));
    }
    
    public function listadoTipo() {
        //preparo el form para listar
        $tipos = tipo::all();
        
        return View::make('listarTipo', array('tipos' => $tipos));
    }
    
    public function listadoTipoOK() {
        //recojo los get de tipo(Trabajo, Vacaciones o Baja) y campo1(filtro para listar)
        $tipo=Input::get('tipo');
        $campo1=Input::get('campo1');
        $anio=Input::get('anio');
        
        //si $tipo es Vacaciones o Baja en $campo1 viene el año, se filtra por fecha
        //si $tipo es trabajo se filtra por descripcion y por defecto el año actual

        $query=parte::where('Id','=', Session::get('Id'))
                      ->where('borrado','=', "1")
                      ->where('tipo','=', $tipo)
                      ->where('descripcion','LIKE', "%$campo1%")
                      ->where(DB::raw("year(fecha)"),'=', $anio)
                      ->get();

        
        $html = '<a href="#" onclick="javascript:main(\''. date('d-m-Y') .'\');" rel="'. date('d-m-Y') .'">';
        $html = $html . '<img src="'. URL::asset('img/volver.png') .'" height="18" width="18">&nbsp';
        $html = $html . '</a><br/><br/>';
        
        $html = $html . "<b>Listado $tipo</b>";
        $html = $html . '<ul>';
        for ($i = 0; $i < count($query); $i++) {
            $fecha = explode('-',$query[$i]->fecha);
            $fecha = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
            $html = $html . "<li class='listadoParte'>";
            $html = $html . "Fecha: <b>".$fecha.'  </b>';
            $html = $html . "Horas: <b>".$query[$i]->horas."</b> - Extras: <b>".$query[$i]->extras."</b><br/>";
            $html = $html . "Descripción: <b>".$query[$i]->descripcion.'</b><br/>';
            $html = $html . "</li>";
            $html = $html . "<li>&nbsp;</li>";
        }
        $html = $html . "</ul>";
        
        
        return $html;
    }
    
    //funciones de ayuda
    public function ayuda() {
        return View::make('ayuda');
    }
    
    public function parteAlta() {
        return View::make('videos_ayuda.parte_alta');
    }
    
    public function parteEditar() {
        return View::make('videos_ayuda.parte_editar');
    }
    
    public function parteBorrar() {
        return View::make('videos_ayuda.parte_borrar');
    }
    
    public function parteBuscar() {
        return View::make('videos_ayuda.parte_buscar');
    }
    
    public function listadoMes() {
        return View::make('videos_ayuda.listar_mes');
    }
    
    public function cambiarMes() {
        return View::make('videos_ayuda.cambiar_mes');
    }
    
    public function listar_tipo() {
        return View::make('videos_ayuda.listar_tipo');
    }
    
    public function totales_horas() {
        return View::make('videos_ayuda.totales_horas');
    }
    
//    public function baja() {
//        return View::make('videos_ayuda.baja');
//    }
    
    //funciones de total horas
    public function totalHoras(){
        return View::make('horasTotales');
    }
    
    public function totalHorasOK(){
        //recojo el get del año 
        $anio = Input::get('anio');
        $Id = Session::get('Id');

        $mensuales = DB::select("
                            SELECT MONTH(P.fecha) AS Mes,P.tipo,ROUND(SUM(P.horas),2) AS horas,ROUND(SUM(P.extras),2) AS extras
                            FROM partefpp2_partes P
                            WHERE P.borrado=1
                            AND P.Id=$Id
                            AND YEAR(P.fecha)=$anio
                            GROUP BY MONTH(P.fecha),P.tipo
                            ");
        
        $anual = DB::select("
                            SELECT P.tipo,ROUND(SUM(P.horas),2) AS horas,ROUND(SUM(P.extras),2) AS extras
                            FROM partefpp2_partes P
                            WHERE P.borrado=1
                            AND P.Id=$Id
                            AND YEAR(P.fecha)=$anio
                            GROUP BY P.tipo
                            ");

        return View::make('horasTotalesPresentar', array('mensuales' => $mensuales , 'anual' => $anual , 'anio' => $anio));
    }
    
    public function altaTipo(){
        //presentamos el form de alta de tipo de parte
        return View::make('altaTipo');
    }
    
    function altaTipoOK(){
        //primero comprobamos que este tipo no exista ya
        $listado = tipo::all();

        for ($i = 0; $i < count($listado); $i++) {
            if($listado[$i]->tipo === Input::get('nombre')){
                return "<p class='ok'>Este tipo ya existe.</p>";
            }
        }

        //sino existe se inserta
        $tipo = new tipo();
        $tipo->tipo = Input::get('nombre');
        if($tipo->save()){
            return "<p class='ok'>Este tipo se ha dado de alta correctamente.</p>";
        }else{
            return "<p class='error'>Se ha producido un error al dar de alta este tipo.</p>";
        }
    }
    
    public function listadoTipoL() {
        //listo los trabajadores y administradores que hay
        
        $query = tipo::all();
        

        $html = '<a href="#" onclick="javascript:main(\''. date('d-m-Y') .'\');" rel="'. date('d-m-Y') .'">';
        $html = $html . '<img src="'. URL::asset('img/volver.png') .'" height="18" width="18">&nbsp';
        $html = $html . '</a><br/><br/>';
        
        $html = $html . "<b>Listado Tipos</b>";
        $html = $html . '<ul>';
        for ($i = 0; $i < count($query); $i++) {
            $html = $html . "<li class='listadoParte'><a href='#' onclick='editarTipo(" . $query[$i]->id . ");'>";
            $html = $html . "Nombre Tipo: <b>".$query[$i]->tipo.'</b><br/>';
            $html = $html . "</a></li>";
            $html = $html . "<li>&nbsp;</li>";
        }
        $html = $html . "</ul>";
        
        return $html;
    }
    
    public function editarTipo(){
        $tipo = tipo::find(Input::get('id'));
        
        return View::make('editarTipo', array('tipo' => $tipo));
    }
    
    public function editarTipoOK(){
        //se editan en la tabla tipo y en la de partes
        
        //1º en la tabla partes
        $OK = parte::where("tipo","=",Input::get('tipo_a'))
                    ->where("Borrado","=","1")
                    ->update(array('tipo' => Input::get('tipo_n')));
        
        
        //2º en la tabla tipo
        $tipo = tipo::find(Input::get('id'));
        $tipo->tipo = Input::get('tipo_n');
        
        if($tipo->save()){
            return "<p class='ok'>Este tipo se ha editado correctamente.</p>";
        }else{
            return "<p class='error'>Se ha producido un error en la edición de este tipo.</p>";
        }
    }

    public function excel_importar(){
        //presentamos el form para indicar filtros del listado
        return View::make('excel_importar');
    }
    
    //SIN TERMINAR
    public function excel_importarFichero(){
        $file = Input::file('excelFicheroSubir');
        
        if($file->getMimeType() === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
            $destinoPath = public_path().'/excel/';
            $subir = $file->move($destinoPath,'importar.xls');
        
            //??
            $url = '../public/excel/importar.xls';

            //leo el fichero
            $XLFileType = PHPExcel_IOFactory::identify($url);  
            $objReader = PHPExcel_IOFactory::createReader($XLFileType);  
            $objPHPExcel = $objReader->load($url);  

            //leo los nombres de las hojas (meses)
            $meses = $objReader->listWorksheetNames($url);
            $datos = '';
            foreach($meses as $mes){
                $datoMes = $objPHPExcel->setActiveSheetIndexByName($mes);
                //la primera fila es de titulos de las columnas
                $posDatosFila = 2;
                while(true){
                    $datoA = trim(utf8_decode($datoMes->getCell('A'.$posDatosFila)->getFormattedValue()));
                    if($datoA !== ''){
                        $dato['fechaIndice'] = 0;
                        $dato['fecha'] = $datoA;
                        $dato['horas'] = trim(utf8_decode($datoMes->getCell('B'.$posDatosFila)->getFormattedValue()));
                        $dato['extras'] = trim(utf8_decode($datoMes->getCell('C'.$posDatosFila)->getFormattedValue()));
                        $dato['tipo'] = trim($datoMes->getCell('D'.$posDatosFila)->getFormattedValue());
                        $dato['descripcion'] = trim($datoMes->getCell('E'.$posDatosFila)->getFormattedValue());
                        $dato['insertar_tipo'] = 'NO';
                        //guardo en el array
                        $datos[] = $dato;
                    }else{
                        //dejo termino de insertar mas filas (ya esta vacio)
                        break;
                    }
                    $posDatosFila++;
                }
            }
            
            //ordenar por fecha
            //primero obtengo la columna fecha
            foreach ($datos as $clave => $fila) {
                $fecha[$clave] = $fila['fecha'];
            }

            // Ordenar los datos con volumen descendiente, edición ascendiente
            // Agregar $datos como el último parámetro, para ordenar por la clave común
            array_multisort($fecha, SORT_ASC, $datos);
            
            
            //ahora presentamos los datos e indicamos si hay algun tipo nuevo que no exista en la tabla de tipos
            //lo indicamos en color rojo
            $indice = 0;
            $fechaAnt = '';
            $html = '<h4>Datos importados</h4>';
            $html = $html . '<table>';
            for ($i = 0; $i < count($datos); $i++) {
                //comparo la fecha con la anterior, si es la misma pongo en mismo indice, si es distinta aumento este 
                //indice y lo pongo
                $fecha = $datos[$i]['fecha'];
                if($fecha <> $fechaAnt){
                    $indice++;
                }
                $datos[$i]['fechaIndice'] = $indice;
                
                //miro a ver si existe es tipo, sino existe lo marco en rojo y negrita
                $existe = tipo::where("tipo","=",$datos[$i]['tipo'])->get();
                //$existe = parte::find($datos[$i]['tipo']);

                $txtColorRojo = '';
                if($existe->isEmpty() === true){
                    $txtColorRojo = ' - (NO EXISTE)';
                    $datos[$i]['insertar_tipo'] = 'SI';
                } 
                
                $html = $html . '<tr class="bgtr"><td colspan="3"> Fecha: <b>' . $datos[$i]['fecha'] . '</b></td></tr>';
                $html = $html . '<tr class="bgtr2"><td> Horas: <b>' . $datos[$i]['horas'] . '</b></td>';
                $html = $html . '<td> Extras: <b>' . $datos[$i]['extras'] . '</b></td></tr>';
                $html = $html . "<tr class='bgtr2'><td colspan='3'> Tipo: <b>" . $datos[$i]['tipo'] . $txtColorRojo . ' </b></td></tr>';
                $html = $html . '<tr class="bgtr2"><td colspan="3"> Descripción:<br/><b> ' . $datos[$i]['descripcion'] . '</b></td></tr>';
                $html = $html . '<tr><td colspan="3">&nbsp;</td></tr>';
                
                $fechaAnt = $fecha;
            }
            $html = $html . '</table>';
            $html = $html . '<br/><br/><p>Estos datos sustituiran a los que esten en la base de datos cronologicamente (los datos que haya de un dia sustituira a los datos que esten de ese dia en la base de datos)</p>';
            $html = $html . '<p>En el "Tipo" si no existiese en la base de datos actual, saldrá marcado con (NO EXISTE). Si diesemos OK este tipo se crearia </p>';

            $html = $html . '<table>';
            $html = $html . '<tr><td>';
            $html = $html . "<input type='button' name='Enviar' value='  OK  ' onClick='excel_importarFicheroTerminar();'>";
            $html = $html . "</td>";
            $html = $html . "<td></td>";
            $html = $html . '<td>';
            $html = $html . "<input type='button' name='Cancelar' value='Cancelar' onClick='excel_importarFicheroCancelar();'>";
            $html = $html . "</td></tr>";
            $html = $html . "</table><br/>";
            
            
            //ahora le paso la vble de la tabla datos a Session por si lo utilizamos
            Session::put('DatosImportar', $datos);
            
            echo $html;
        }else{
            echo 'El fichero no es de tipo excel';
        }
    }
    
    public function importandoDato(){
        $fechaIndice = Input::get('fechaIndice');
        
        //traigo los datos de Session
        $datos = Session::get('DatosImportar');
        
        //HAY QUE GUARDAR EN LA BBDD
        //1- SE BUSCA SI HAY DATOS DE ESE DIA Y SE BORRAN (BORRADO=0)
        //2- SE INSERTAN LOS NUEVOS DATOS
        //3- SE MARCA EN EL ARRAY QUE ESTAN INSERTADOS
        //3- SI TODO A IDO CORRECTAMENTE SE DEVUELVE OK POR ECHO, SINO ERROR POR ECHO
        
        //1- se busca datos de ese dia en la BBDD y se borran
        $fecha = '';
        for ($i = 0; $i < count($datos); $i++) {
            if($datos[$i]['fechaIndice'] === (int)$fechaIndice){
                $fecha = $datos[$i]['fecha'];
                break;
            }
        }
        
        if($fecha === ''){
            echo '';die;
        }
        
        //actualizo el campo Borrado=0 en la tabla partes
        $OK = parte::where("fecha","=",$fecha)
                    ->update(array('borrado' => '0'));

        
        
        //2- Insertamos los nuevos datos en la BBDD

        $OK = true;
        for ($i = 0; $i < count($datos); $i++) {
            //insertamos en la tabla de parte
            if($datos[$i]['fechaIndice'] === (int)$fechaIndice){
                $parteNuevo = new parte();
                $parteNuevo->Id = Session::get('Id');
                $parteNuevo->fecha = $datos[$i]['fecha'];
                $parteNuevo->horas = $datos[$i]['horas'];
                $parteNuevo->extras = $datos[$i]['extras'];
                $parteNuevo->tipo = $datos[$i]['tipo'];
                $parteNuevo->descripcion = $datos[$i]['descripcion'];
                $parteNuevo->borrado = '1';
                
                if(!$parteNuevo->save()){
                    $OK = false;
                }
            }
            
            //ahora comprobamos si este tipo existe o no en la tabla tipo
            $encontrado = tipo::where("tipo","=",$datos[$i]['tipo'])->get();
            
            //sino existe se inserta en la tabla tipo
            if($encontrado->isEmpty() === true){
                $tipo = new tipo();
                $tipo->tipo = $datos[$i]['tipo'];
                
                if(!$tipo->save()){
                    $OK = false;
                }
            }
        }
        
        if($OK === true){
            echo 'OK. Insertado partes de fecha: '.$parteNuevo->fecha.'<br/>';
        }else{
            echo 'ERROR. NO se ha insertado partes de fecha: '.$parteNuevo->fecha.'<br/>';
        }
    }
    
    public function excel_importarTerminar(){
        return View::make('excel_importarTerminar');
    }
    
    
    public function excel_exportar(){
        //presentamos el form para indicar filtros del listado
        return View::make('excel_exportar');
    }
    
    public function excel_exportarFichero(){
        //donde se va a guardar los datos a exportar
        $url = './excel/exportar.xls';
        
        $desde = Input::get('desde');
        $hasta = Input::get('hasta');
        
        //compruebo que el formato de las fechas es correcto
        //si lo es, l paso a DATE, sino lo dejo en blanco
        //la fecha debe venir con este formato '05/01/2015' (d/m/Y)
        if($desde !== ''){
            $d = DateTime::createFromFormat('d/m/Y', $desde);
            if($d->format('d/m/Y') === $desde){
                $desde = explode('/',$desde);
                $desde = $desde[2] . '-' . $desde[1] . '-' . $desde[0];
            }else{    
                $desde = '';
            }
        }
        if($hasta !== ''){
            $d = DateTime::createFromFormat('d/m/Y', $hasta);
            if($d->format('d/m/Y') === $hasta){
                $hasta = explode('/',$hasta);
                $hasta = $hasta[2] . '-' . $hasta[1] . '-' . $hasta[0];
            }else{    
                $hasta = '';
            }
        }
        
        
        //si la fecha viene vacia no se indica ese filtro en la consulta
        //como puede venir las dos vacias, o una vacia y otra con fecha o las dos con fechas
        //preparamos estas tres posibles opciones (tres consultas distintas)
        if($desde === '' && $hasta === ''){
            $query = parte::where('Id','=', Session::get('Id'))
                          ->where('borrado','=', "1")
                          ->get();
        }else
        if($desde !== '' && $hasta === ''){
            $query = parte::where('Id','=', Session::get('Id'))
                          ->where('borrado','=', "1")
                          ->where('fecha','>=', $desde)
                          ->get();
        }else
        if($desde === '' && $hasta !== ''){
            $query = parte::where('Id','=', Session::get('Id'))
                          ->where('borrado','=', "1")
                          ->where('fecha','<=', $hasta)
                          ->get();
        }else
        if($desde !== '' && $hasta !== ''){
            $query = parte::where('Id','=', Session::get('Id'))
                          ->where('borrado','=', "1")
                          ->where('fecha','>=', $desde)
                          ->where('fecha','<=', $hasta)
                          ->get();
        }    
        
        //creo el objeto de PHPExcel
        $objPHPExcel = new PHPExcel();
        
        $objPHPExcel->getProperties()->setCreator("Partes")
                                     ->setLastModifiedBy("Partes")
                                     ->setTitle("Excel para exportar datos")
                                     ->setSubject("Exportar datos")
                                     ->setDescription("")
                                     ->setKeywords("office PHPExcel php")
                                     ->setCategory("");


        //primero esribimos las cabeceras de la tabla
        $objPHPExcel->getActiveSheet()->setCellValue('A1',"fecha");
        $objPHPExcel->getActiveSheet()->setCellValue('B1',"horas");
        $objPHPExcel->getActiveSheet()->setCellValue('C1',"extras");
        $objPHPExcel->getActiveSheet()->setCellValue('D1',"tipo");
        $objPHPExcel->getActiveSheet()->setCellValue('E1',"descripcion");


        //recorremos todo el array del listado y l ovamos guardando en las siguientes filas
        $fila = 2;
        for ($i = 0; $i < count($query); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$fila,$query[$i]->fecha);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila,$query[$i]->horas);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$fila,$query[$i]->extras);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$fila,$query[$i]->tipo);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$fila,$query[$i]->descripcion);
            $fila ++;
        }
        
        //guardamos el fichero
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($url);

        //y por ultimo se descarga al cliente remoto
         $headers = array(
             'Content-Type' => 'application/vnd.ms-excel',
        );
        
        return Response::download($url,'exportar.xls',$headers);
    }
    
}
