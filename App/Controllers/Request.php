<?php
namespace App\Controllers;
class Request
{
	public $params = array();
	public $body = array();
	public function __get($name){
		switch( $name){
			case 'session':
				if(!isset($_SESSION)){
					session_start();
				}
			return $_SESSION;
			break;
			case 'query':
				if($this->method === 'GET'){
					return $_GET;
				}else{
					return $_POST;
				}
				default:
					echo 'nothig';
			}
	}
	function setSession($name,$value){
		if(!isset($_SESSION)){
			session_start();
		}
		$_SESSION[$name] = $value;

	}
	function __construct($array = [], $body = []){
		$this->path = str_replace( '/orm/', '/', parse_url( $_SERVER['REQUEST_URI'] )['path'] );
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->params = $array;
		if(!IS_GET){
			$json = file_get_contents('php://input');
			$data = json_decode($json, true);
			$this->body = $data;
		}
	}
}
