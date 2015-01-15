<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('me logeo');
$I->amOnPage('/');
$I->fillField('nick', 'oscar');
$I->fillField('password', 'oscar');
$I->click('Entrar');
$I->amOnPage('/main');
$I->see('OSCAR MORO FERNANDEZ');

//dar de alta un parte el dia 11-1-2015
$I->sendAjaxGetRequest('guardar_evento',array("evento"=> "mierdoido codeception", "fecha"=> "11-1-2015", "horas"=> "7", "tipo"=> "Vacaciones", "accion"=> "guardar_evento"));

//editar este parte
//$I->sendAjaxGetRequest('guardar_evento',array("evento"=> "mierdoido codeception", "fecha"=> "11-1-2015", "horas"=> "7", "tipo"=> "Vacaciones", "accion"=> "guardar_evento"));
