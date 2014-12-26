<?php

class CalendarController extends BaseController {
    /*
      |--------------------------------------------------------------------------
      | Default Home Controller
      |--------------------------------------------------------------------------
      |
      | You may wish to use controllers instead of, or in addition to, Closure
      | based routes. That's great! Here is an example controller method to
      | get you started. To route to this controller, just add the route:
      |
      |	Route::get('/', 'HomeController@showWelcome');
      |
     */

    public function login() {
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

        return View::make('calendar', array('html' => ''));
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
        $query = DB::select("select fecha,count(Id) as total from partefpp_partes where month(fecha)='" . $fecha_calendario[1] . "' and year(fecha)='" . $fecha_calendario[0] . "' and Id=1 and borrado=1 group by fecha");

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

        /* empezamos a pintar la tabla */
        //lo guardamos en una vble $html
        $html = '';
        $html = $html . "<h2>" . $meses[intval($fecha_calendario[1])] . " de " . $fecha_calendario[0] . " <abbr title='S&oacute;lo se pueden agregar eventos en d&iacute;as h&aacute;biles y en fechas futuras (o la fecha actual).'></abbr></h2>";
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
        $html = $html . "</table>";
        
        $mesanterior = date("Y-m-d", mktime(0, 0, 0, $fecha_calendario[1] - 1, 01, $fecha_calendario[0]));
//        $mesanterior=explode('-',$mesanterior);
//        $mesA=(string)$mesanterior[1];
//        $anioA=(string)$mesanterior[0];
        $messiguiente = date("Y-m-d", mktime(0, 0, 0, $fecha_calendario[1] + 1, 01, $fecha_calendario[0]));
//        $messiguiente=explode('-',$messiguiente);
//        $mesP=(string)$messiguiente[1];
//        $anioP=(string)$messiguiente[0];
        $html = $html . "<p class='toggle'>&laquo; <a href='#' onClick='cambiaMes(this);' rel='$mesanterior' class='anterior'>Mes Anterior</a> - <a href='#' onClick='cambiaMes(this);' class='siguiente' rel='$messiguiente'>Mes Siguiente</a> &raquo;</p>";

        //return View::make('calendar',array('html'=>$html));
        return $html;
    }
    
    public function evento_nuevo(){
        $fecha=Input::get('dia')."-".Input::get('mes')."-".Input::get('anio');
        
        $listar_eventos=$this->listar_evento();
        
        return View::make('eventos', array('fecha' => $fecha,'listar_eventos' => $listar_eventos));
    }

    //BORRAR 26-12-2014
    private function fecha($valor) {
        $timer = explode(" ", $valor);
        $fecha = explode("-", $timer[0]);
        $fechex = $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0];
        return $fechex;
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

    //esta funcion la llama evento_nuevo()
    public function listar_evento() {
        $fecha=Input::get('anio')."-".Input::get('mes')."-".Input::get('dia');
        
        $query = parte::where('fecha', '=', $fecha)
                        ->where('Id','=', Session::get('Id'))
                        ->where('borrado','=', '1')
                        ->orderBy('IdParte', 'asc')
                        ->get();
        
//        $query = calendario::where('fecha', '=', $fecha)
//                ->orderBy('id', 'asc')
//                ->get();

        $html = '<table>';
        $html = $html.'<tr><td colspan="3"><b>Listado de eventos</b></td></tr>';
        for ($i = 0; $i < count($query); $i++) {
            $html = $html . "<tr class='bgtr'><td colspan='2'>" . $query[$i]->descripcion . "</td><td><a href='#' class='eliminar_evento' onClick='borrarEvento(" . $query[$i]->IdParte . ",".Input::get('anio').",".Input::get('mes').",".Input::get('dia').");' title='Eliminar este parte'><div  id='evIcono" . $query[$i]->IdParte . "'><img src='" . URL::asset('img/delete.png') . "' height='10' width='10'></div></a></td></tr>";
            $html = $html . "<tr class='bgtr2'><td colspan='2'><b>" . $query[$i]->tipo . "</b></td><td><b>" . $query[$i]->horas . "</b></td></tr>";
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

}
