<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//Route::get('/', function()
//{
//	return View::make('calendar');
//});

Route::get('/','CalendarController@login');
Route::post('logeado','CalendarController@postLogin');
Route::get('logout','CalendarController@logout');

Route::get('main','CalendarController@generar_calendario_inicio');
Route::get('generar_calendario','CalendarController@generar_calendario');
Route::get('guardar_evento','CalendarController@guardar_evento');
Route::get('listar_evento','CalendarController@listar_evento');
Route::get('borrar_evento','CalendarController@borrar_evento');

Route::get('evento_nuevo','CalendarController@evento_nuevo');
Route::get('listarMes','CalendarController@listarMes');
Route::get('editarParte','CalendarController@editarParte');
Route::get('editarParteOK','CalendarController@editarParteOK');

Route::get('buscar','CalendarController@buscar');
Route::get('buscarOK','CalendarController@buscarOK');

//listados
Route::get('listadoTipo','CalendarController@listadoTipo');
Route::get('listadoTipoOK','CalendarController@listadoTipoOK');

//Trabajador
Route::get('listadoTrab','TrabajadorController@listadoTrab');
Route::get('editarTrab','TrabajadorController@editarTrab');
Route::get('editarTrabOK','TrabajadorController@editarTrabOK');
Route::get('borrarTrabajadorOK','TrabajadorController@borrarTrabajadorOK');
Route::get('altaTrab','TrabajadorController@altaTrab');
Route::get('altaTrabOK','TrabajadorController@altaTrabOK');

//ayuda
Route::get('ayuda','CalendarController@ayuda');
Route::get('parte_alta','CalendarController@parteAlta');
