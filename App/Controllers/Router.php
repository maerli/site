<?php
	namespace App\Controllers;
	define("IS_GET",$_SERVER['REQUEST_METHOD'] === 'GET');
	class Router
	{
		public $path = array();
		public $methods = array();
		public $posts = array();
		public $post_methods = array();
		public static $views_path = "";
		public static $static_path = "";
		public static $url;
		public $sub = "";
		public static function module($path){
			if(file_exists($path)){
				include($path);
				if($export){
					return $export;
				}else{
					echo "the module you trying load its not valid!";
				}

			}else{
				return ("File $path do not exists!");
			}
		}
		public function __construct($sub = ""){
			Router::$url  = str_replace( '/first/', '/', parse_url( $_SERVER['REQUEST_URI'] )['path'] );
			$this->sub = $sub;
		}

		public function setRoute($sub){
			$this->sub = $sub;
		}
		public function use($path, $func){
			$previous_route = $this->sub;
			$this->setRoute($path);
			$func($this);

			// $this->routes()
			$this->setRoute($previous_route);
		}
		static public function set($cmd,$path=""){
			if($cmd === 'views'){
				Router::$views_path = $path;
			}else if($cmd === 'static'){
				Router::$static_path = $path;
				$staticpath = Router::$static_path.Router::$url;
				if(file_exists($staticpath) && !is_dir($staticpath)){
					// if(preg_match('/css/',Router::$url)){
					// 	header('Content-Type','text/css');
					// }
				//if(preg_match('/png/',Router::$url)){

					//}
					$imginfo = getimagesize($staticpath);
					if(preg_match('/png/',$staticpath)){
						header("Content-Type: image/png");
					 header("Content-Length: " . filesize($staticpath));
					 readfile($staticpath);
					}

					// header('Content-type',"{$imginfo['mime']}");
					if(preg_match('/css/',$staticpath)){
							header("Content-Type: text/css");
						 header("Content-Length: " . filesize($staticpath));
						 readfile($staticpath);
					}
					if(preg_match('/js/',$staticpath)){
							header("Content-Type: text/javascript");
						 header("Content-Length: " . filesize($staticpath));
						 readfile($staticpath);
					}

					//readfile($staticpath);
					die();
				}
			} else{
				$request = new Request;
				$response = new Response($request);
				call_user_func($cmd,$request,$response);
			}
  // Container para lista de protocolos
		}
		public function get($path,$method){
			$this->path[] = '/'.trim($this->sub.$path,'/');
			$this->methods[] = $method;
		}
		public function post($path,$method){
			$this->posts[] = '/'.trim($this->sub.$path,'/');
			$this->post_methods[] = $method;
		}
		public function both($path,$get_function,$post_function){
			$this->path[] = '/'.trim($this->sub.$path,'/');
			$this->methods[] = $get_function;
			$this->posts[] = '/'.trim($this->sub.$path,'/');
			$this->post_methods[] = $post_function;

		}
		public function submit(){
			$url = Router::$url;
			if(IS_GET){
				$search = $this->path;
				$method = $this->methods;

			}else{
				$search = $this->posts;
				$method = $this->post_methods;
			}$path = Router::$static_path.$url;
				if(file_exists($path) && !is_dir($path)){
					include(Router::$static_path.$url);
					exit;
				}

			$found_a_route = false;
			foreach ($search as $key => $value) {
				if( preg_match("#^$value$#",$url)){
					$request = new Request;
					$response = new Response( $request );
					call_user_func($method[$key],$request,$response);
					$found_a_route = true;
				}else{
					$values = explode('/',trim( $value,'/'));
					$slices = explode('/',trim( $url, '/'));
					$match = array();
					if(count($values) === count($slices)){
						$newurl = preg_replace_callback('/:[a-z]+/',function($matches) use ($values,$slices,&$match){
							$pos = array_search($matches[0], $values);
							$match[trim( $matches[0], ':')] = $slices[$pos];
							return $slices[$pos];
						},$value);

						if($url === $newurl){
							$found_a_route = true;
							$request = new Request($match);
							$response = new Response($request);
							call_user_func($method[$key],$request,$response);
						}
					}

				}
			}
			// if no routes found
			if(!$found_a_route){
				$request = new Request;
				$response = new Response($request);
				$response->status(404);
				$response->send('not ok!');
				print_r($this->path);
			}

		}
		function routes($path){
			return new Routes($path,$this);
		}
	}

  class Routes{
		private $path;
		private $router;
		function __construct($path,&$router){
			$this->path = $path;
			$this->router = $router;
		}
		function get($func){
			$this->router->get($this->path,$func);
			return $this;
		}
		function post($func){
			$this->router->post($this->path, $func);
			return $this;
		}
	}
