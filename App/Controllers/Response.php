<?php
namespace App\Controllers;
class Response
{
	public $request;
	function __construct( $request){
		$this->request = $request;
	}
	public function module($path){
		if(file_exists($path)){
			include($path);
			if($export){
				return $export;
			}else{
				echo "the module you trying load its not valid!";
			}

		}else{
			return ("File $file do not exists!");
		}
	}
	public function _render($path, $args=[]){
		if(file_exists($path)){
			ob_start();
			include($path);
			$var = ob_get_contents();
			ob_end_clean();
			return $var;
		}else{
			return ("File $path do not exists!");
		}
	}
	public function __get($name){
		switch ($name) {
			case 'cookie':
				return $_COOKIE;
				break;
			case 'params':
				return $this->request->params;
			default:
				throw new \Exception( "Not accept atrr $name",1);
				break;
		}
	}
	function send($str){
		echo $str;
	}
	function json($array, $rewrite = true){
		if($rewrite){
			header("Content-Type: application/json");
		}
		echo json_encode($array);
	}
	function status($code){
		http_response_code($code);
	}
	function setCookie($name,$value,$expires){
		setcookie($name, $value, $expires);
	}
	function render($file, $args=array()){
		$path = Router::$views_path.'/'.$file.'.php';
		echo $this->_render($path, $args);
	}
	function error($msg){
		$this->send($msg);
	}
}
