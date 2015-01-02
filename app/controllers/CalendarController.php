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
        if (Session::has('nombre') && Session::has('apellidos') && Session::has('rol')) {
            //chequeamos que estos valores del usuario existan en la tabla 'usuarios'
            $existe = usuario::where('nombre', '=', Session::get('nombre'))
                    ->where('apellidos', '=', Session::get('apellidos'))
                    ->where('rol', '=', Session::get('rol'))
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
            return $this->login()->with('error', 'Nick o clave incorrectos.');
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
        $query = DB::select("select fecha,count(Id) as total from partefpp_partes where month(fecha)='" . $fecha_calendario[1] . "' and year(fecha)='" . $fecha_calendario[0] . "' and Id=".Session::get('Id')." and borrado=1 group by fecha");

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
                    $html = $html . "<td class='";
                    /* creamos fecha completa */
                    if ($dia < 10)
                        $dia_actual = "0" . $dia;
                    else
                        $dia_actual = $dia;
                    $fecha_completa = $fecha_calendario[0] . "-" . $fecha_calendario[1] . "-" . $dia_actual;

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
    
    public function evento_nuevo(){
        $fecha=Input::get('dia')."-".Input::get('mes')."-".Input::get('anio');
        
        $listar_eventos=$this->listar_evento();
        
        return View::make('eventos', array('fecha' => $fecha,'listar_eventos' => $listar_eventos));
    }
    
    function listarMes(){
        $fecha='01-'.Input::get('mes').'-'.Input::get('anio');
        $query = DB::select("select IdParte,fecha,tipo,horas,descripcion from partefpp_partes where month(fecha)='" . Input::get('mes') . "' and year(fecha)='" . Input::get('anio') . "' and Id=".Session::get('Id')." and borrado=1 order by fecha");

        return $this->htmlListado($query,$fecha);
    }
    
    private function htmlListado($query,$fecha){
        $html='<ul>';
        for ($i = 0; $i < count($query); $i++) {
            $fecha = explode('-',$query[$i]->fecha);
            $fecha = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
            $html = $html . "<li class='listadoParte'><a href='#' onclick='editarParte(" . $query[$i]->IdParte . ");'>";
            $html = $html . "Fecha: <b>".$fecha.'</b><br/>';
            $html = $html . "Tipo: <b>".$query[$i]->tipo.'</b><br/>';
            $html = $html . "Horas: <b>".$query[$i]->horas.'</b><br/>';
            $html = $html . "Descripción: <b>".$query[$i]->descripcion.'</b><br/>';
            $html = $html . "</a></li>";
            $html = $html . "<li>&nbsp;</li>";
        }
        $html = $html . "</ul>";
        
        $html = $html . '<a href="#" onclick="javascript:main(\''. $fecha .'\');" rel="'. $fecha .'">';
        $html = $html . '<img src="'. URL::asset('img/volver.png') .'" height="10" width="10">&nbsp';
        $html = $html . '</a>';
        
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
        $parte->Id = Session::get("Id");
        $parte->borrado = "1";

        $html = '';
        if ($parte->save()) {
            $html = $html . "<p class='ok'>Parte guardado correctamente.</p>";
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
            $html = $html . "<tr class='bgtr'><td colspan='2'><a href='#' onclick='editarParte(" . $query[$i]->IdParte . ");'>" . $query[$i]->descripcion . "</a></td><td align='right'><a href='#' class='eliminar_evento' onClick='borrarEvento(" . $query[$i]->IdParte . ",".Input::get('anio').",".Input::get('mes').",".Input::get('dia').");' title='Eliminar este parte'><div  id='evIcono" . $query[$i]->IdParte . "'><img src='" . URL::asset('img/delete.png') . "' height='10' width='10'></div></a></td></tr>";
            $html = $html . "<tr class='bgtr2'><td colspan='2'><a href='#' onclick='editarParte(" . $query[$i]->IdParte . ");'><b>" . $query[$i]->tipo . "</b></td><td align='right'><b>" . $query[$i]->horas . "</b></a></td></tr>";
            $html = $html . "<tr><td colspan='3'><hr/><br/></td></tr>";
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
        
        return View::make('editar', array('datos_parte' => $datos_parte,'fecha' => $fecha));
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
        $tipo=Input::get('tipo');
        
        return View::make('listarTipo', array('tipo' => $tipo));
    }
    
    public function listadoTipoOK() {
        //recojo los get de tipo(Trabajo, Vacaciones o Baja) y campo1(filtro para listar)
        $tipo=Input::get('tipo');
        $campo1=Input::get('campo1');
        
        //si $tipo es Vacaciones o Baja en $campo1 viene el año, se filtra por fecha
        //si $tipo es trabajo se filtra por descripcion y por defecto el año actual

        $query='';
        if($tipo==='Vacaciones' || $tipo==='Baja'){
            $query=parte::where('Id','=', Session::get('Id'))
                          ->where('borrado','=', "1")
                          ->where('tipo','=', $tipo)
                          ->where(DB::raw("year(fecha)"),'=', $campo1)
                          ->get();
        }else if($tipo==='Trabajo'){
            $query=parte::where('Id','=', Session::get('Id'))
                          ->where('borrado','=', "1")
                          ->where('tipo','=', $tipo)
                          ->where('descripcion','LIKE', "%$campo1%")
                          ->get();
        }

        
        $html = '<a href="#" onclick="javascript:main(\''. date('d-m-Y') .'\');" rel="'. date('d-m-Y') .'">';
        $html = $html . '<img src="'. URL::asset('img/volver.png') .'" height="10" width="10">&nbsp';
        $html = $html . '</a><br/><br/>';
        
        $html = $html . "<b>Listado $tipo</b>";
        $html = $html . '<ul>';
        for ($i = 0; $i < count($query); $i++) {
            $fecha = explode('-',$query[$i]->fecha);
            $fecha = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
            $html = $html . "<li class='listadoParte'>";
            $html = $html . "Fecha: <b>".$fecha.'  </b>';
            $html = $html . "Horas: <b>".$query[$i]->horas.'</b><br/>';
            $html = $html . "Descripción: <b>".$query[$i]->descripcion.'</b><br/>';
            $html = $html . "</li>";
            $html = $html . "<li>&nbsp;</li>";
        }
        $html = $html . "</ul>";
        
        
        return $html;
    }
}
