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
