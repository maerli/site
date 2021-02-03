<?php
namespace App\Controllers;
class App{
static function init(){
	$route = new Router();
	//Router::routes('/api',$routes);

	//set a public folther without login
	Router::set('static','public');
	
	$route->set(function($req,$res){
		
	});

	Router::set('static','private');


	Router::set("views","App/Views");


	//rotas
	$route->get('/',function($req,$res){
		$res->render('index');
	});

	$route->submit();
}
}
